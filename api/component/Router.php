<?php

/**
 * Package MVC\Api
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 */

namespace MVC\Api\Component;

use MVC\Api\Controller\AbstractController;
use MVC\Classe\Logger;
use MVC\Object\Environment;

/**
 * Dispatcher de l'API, appelé en premier par Application pour /wp-json/… et /api/….
 *
 * Résolution en deux temps :
 *   1. routes explicites (RouteRegistry, fichier API_FICHIER_ROUTES) ;
 *   2. convention /{namespace}/{resource}/{id?}, avec « wp/v2 » pour /wp-json/wp/v2/….
 *
 * L'API est le seul endroit du framework où d'autres verbes que GET et POST
 * sont admis (annexe 20). Toute réponse est du JSON, erreurs comprises.
 */
final class Router
{
    /** Méthode HTTP + collection ou élément => action du contrôleur. */
    private const ACTIONS = [
        'GET' => ['collection' => 'getItems', 'item' => 'getItem'],
        'HEAD' => ['collection' => 'getItems', 'item' => 'getItem'],
        'POST' => ['collection' => 'createItem'],
        'PUT' => ['item' => 'updateItem'],
        'PATCH' => ['item' => 'updateItem'],
        'DELETE' => ['item' => 'deleteItem'],
        'OPTIONS' => ['collection' => 'schema', 'item' => 'schema'],
    ];

    private const ESPACE_CONTROLEURS = 'MVC\\Api\\Controller\\';

    /** Longueur maximale du message d'exception journalisé. */
    private const LONGUEUR_JOURNAL = 160;

    public function __construct(private Auth $auth, private RouteRegistry $registre)
    {
    }

    /**
     * Charge les clés (API_FICHIER_CLES, s'il existe) et les routes explicites.
     */
    public static function depuisConfiguration(): self
    {
        if (defined('API_FICHIER_CLES') && is_file(API_FICHIER_CLES)) {
            require_once API_FICHIER_CLES;
        }

        return new self(
            Auth::depuisConfiguration(),
            RouteRegistry::depuisFichier(defined('API_FICHIER_ROUTES') ? API_FICHIER_ROUTES : null)
        );
    }

    /**
     * Point d'entrée HTTP : traite la requête courante et émet la réponse JSON.
     */
    public static function dispatch(): void
    {
        $requete = Requete::depuisGlobales();
        try {
            $reponse = self::depuisConfiguration()->traiter($requete);
        } catch (\Throwable $e) {
            $reponse = self::erreurInterne($e);
        }
        $reponse->emettre($requete->methode() === 'HEAD');
    }

    public function traiter(Requete $requete): Response
    {
        try {
            return $this->resoudre($requete);
        } catch (ApiException $e) {
            return Response::erreur($e->getMessage(), $e->statut(), $e->entetes());
        } catch (\Throwable $e) {
            return self::erreurInterne($e);
        }
    }

    private function resoudre(Requete $requete): Response
    {
        $chemin = $requete->chemin();
        if ($chemin === null) {
            throw new ApiException('Chemin hors de l\'API', 404);
        }
        // l'authentification précède toute lecture du corps de la requête
        $this->auth->exiger($requete);

        $methode = $requete->methode() === 'HEAD' ? 'GET' : $requete->methode();
        $explicite = $this->registre->match($methode, $chemin);
        if ($explicite !== null) {
            [$rappel, $params] = $explicite;

            return self::normaliser($rappel($params, $requete->corpsJson() ?? []));
        }

        [$classe, $id] = self::resoudreControleur($chemin);
        $type = $id !== null ? 'item' : 'collection';
        $action = self::ACTIONS[$requete->methode()][$type] ?? null;
        if ($action === null || !is_callable([$classe, $action])) {
            throw new ApiException(
                'Méthode HTTP non autorisée sur cette ressource',
                405,
                ['Allow' => implode(', ', self::methodesAutorisees($classe, $type))]
            );
        }

        $parametres = $requete->parametres();
        if ($id !== null) {
            $parametres['id'] = $id;
        }

        return self::normaliser($classe::{$action}(...Arguments::pourMethode($classe, $action, $parametres)));
    }

    /**
     * @return array{0: class-string<AbstractController>, 1: ?string}
     */
    private static function resoudreControleur(string $chemin): array
    {
        $segments = $chemin === '' ? [] : explode('/', $chemin);
        $prefixe = '';
        if (($segments[0] ?? null) === 'wp') {
            array_shift($segments);
            $prefixe = 'wp\\';
        }
        [$espace, $ressource, $id] = array_pad($segments, 3, null);
        $valide = count($segments) <= 3
            && preg_match('/^v\d+$/', (string) $espace) === 1
            && preg_match('/^[a-z][a-z0-9_-]*$/i', (string) $ressource) === 1
            && ($id === null || preg_match('/^[A-Za-z0-9_-]+$/', $id) === 1);
        $classe = $valide ? self::ESPACE_CONTROLEURS . $prefixe . $espace . '\\' . self::camelize($ressource) : '';
        if ($classe === '' || !class_exists($classe) || !is_subclass_of($classe, AbstractController::class)) {
            throw new ApiException('Ressource introuvable', 404);
        }

        return [$classe, $id];
    }

    /**
     * @return array<int, string>
     */
    private static function methodesAutorisees(string $classe, string $type): array
    {
        $methodes = [];
        foreach (self::ACTIONS as $methode => $actions) {
            if (isset($actions[$type]) && is_callable([$classe, $actions[$type]])) {
                $methodes[] = $methode;
            }
        }

        return $methodes;
    }

    private static function normaliser(mixed $resultat): Response
    {
        if ($resultat instanceof Response) {
            return $resultat;
        }
        if (is_array($resultat)) {
            return new Response($resultat);
        }
        throw new \UnexpectedValueException('Un contrôleur d\'API doit renvoyer un tableau ou une Response');
    }

    /**
     * 500 générique. Le journal app.api.log ne garde que la classe de l'exception
     * et son message tronqué sur une ligne (ni trace, ni corps de la requête) ;
     * le message ne parvient au client qu'en débogage.
     */
    private static function erreurInterne(\Throwable $e): Response
    {
        $message = (string) preg_replace('/\s+/', ' ', $e->getMessage());
        Logger::addLog('api', get_class($e) . ' : ' . mb_strimwidth($message, 0, self::LONGUEUR_JOURNAL, '…'));

        return Response::erreur(Environment::debug() ? $e->getMessage() : 'Erreur interne', 500);
    }

    /**
     * posts -> Posts, mode-de-vie -> ModeDeVie
     */
    private static function camelize(string $ressource): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $ressource)));
    }
}
