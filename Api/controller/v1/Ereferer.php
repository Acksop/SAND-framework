<?php

/**
 * Package MVC\Api
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 */

namespace MVC\Api\Controller\v1;

use MVC\Api\Component\FabriqueDepot;
use MVC\Api\Component\Response;
use MVC\Api\Contrat\DepotSoumissions;
use MVC\Api\Contrat\SoumissionNonModifiable;
use MVC\Api\Controller\AbstractController;

/**
 * Point d'entrée compatible avec le contrat du plugin WordPress « Article submit »
 * d'ereferer.com : POST /wp-json/v1/ereferer (ou /api/v1/ereferer), clé d'API exigée.
 *
 * Le framework authentifie et valide ; l'enregistrement est confié au dépôt
 * de l'hôte (interface DepotSoumissions, constante API_DEPOT_SOUMISSIONS).
 * Sans dépôt configuré, la route répond 501.
 *
 * Corps JSON (clés du plugin) :
 *   test_connection bool, id int (obligatoire), delete bool,
 *   post_title string et post_content string (obligatoires hors test et suppression),
 *   categories array, front_image URL http(s), meta_title, meta_description,
 *   custom_field (source de l'image) string, force bool.
 */
class Ereferer extends AbstractController
{
    private const CHAINES_FACULTATIVES = [
        'meta_title' => 'meta_titre',
        'meta_description' => 'meta_description',
        'custom_field' => 'source_image',
    ];

    /** Longueurs maximales (caractères) ; au-delà : 422 incorrect_post ou incorrect_field. */
    public const LONGUEURS_MAX = [
        'post_title' => 255,
        'post_content' => 200000,
        'front_image' => 2048,
        'meta_title' => 255,
        'meta_description' => 500,
        'custom_field' => 255,
    ];
    public const CATEGORIES_MAX = 20;
    /** Nombre maximal d'entrées au premier niveau du corps JSON. */
    public const ENTREES_MAX = 50;

    private static ?DepotSoumissions $depot = null;

    /**
     * Dépôt imposé (tests) ; null revient à la constante API_DEPOT_SOUMISSIONS.
     */
    public static function utiliserDepot(?DepotSoumissions $depot): void
    {
        self::$depot = $depot;
    }

    /**
     * POST /v1/ereferer : test de connexion, suppression, création ou mise à jour,
     * selon le contenu du corps (comme Isubmission_Post_Endpoint::run() du plugin).
     */
    public static function createItem(array $body = []): Response
    {
        $depot = self::$depot ?? FabriqueDepot::depuisConfiguration();
        if ($depot === null) {
            return self::echec('not_implemented', 'Aucun dépôt de soumissions n\'est configuré.', 501);
        }
        if (count($body) > self::ENTREES_MAX) {
            return self::echec('incorrect_field', 'Trop d\'entrées dans le corps de la requête.');
        }
        if (self::vrai($body['test_connection'] ?? false)) {
            return self::succes(['code' => 'success_connection', 'message' => 'Success connection']);
        }
        $id = $body['id'] ?? null;
        if (!(is_int($id) || (is_string($id) && ctype_digit($id))) || (int) $id <= 0) {
            return self::echec('incorrect_post_id', 'L\'identifiant (id) doit être un entier positif.');
        }
        if (self::vrai($body['delete'] ?? false)) {
            return $depot->supprimer((string) $id)
                ? self::succes(['code' => 'success_delete', 'message' => 'Success'])
                : self::echec('failed_delete', 'Soumission inconnue.', 404);
        }
        $erreur = self::valider($body);
        if ($erreur !== null) {
            return $erreur;
        }
        try {
            $resultat = $depot->enregistrer(self::normaliser($body, (string) $id));
        } catch (SoumissionNonModifiable $e) {
            return self::echec('post_not_editable', 'Cette soumission a déjà été publiée.', 409);
        }

        return self::reponseEnregistrement($resultat);
    }

    private static function valider(array $body): ?Response
    {
        foreach (['post_title', 'post_content'] as $champ) {
            if (!is_string($body[$champ] ?? null) || trim($body[$champ]) === '') {
                return self::echec('incorrect_post', 'Le titre et le contenu ne peuvent pas être vides.');
            }
            if (mb_strlen($body[$champ]) > self::LONGUEURS_MAX[$champ]) {
                return self::echec('incorrect_post', self::tropLong($champ));
            }
        }
        $categories = $body['categories'] ?? [];
        if (!is_array($categories) || array_filter($categories, static fn ($c) => !is_scalar($c)) !== []) {
            return self::echec('incorrect_field', 'categories doit être une liste de valeurs simples.');
        }
        if (count($categories) > self::CATEGORIES_MAX) {
            return self::echec('incorrect_field', 'Au plus ' . self::CATEGORIES_MAX . ' catégories.');
        }
        $image = $body['front_image'] ?? null;
        if ($image !== null && $image !== '' && !self::estUrlWeb($image)) {
            return self::echec('incorrect_field', 'front_image doit être une URL http(s).');
        }
        foreach (['front_image', ...array_keys(self::CHAINES_FACULTATIVES)] as $champ) {
            if (isset($body[$champ]) && !is_string($body[$champ])) {
                return self::echec('incorrect_field', $champ . ' doit être une chaîne.');
            }
            if (isset($body[$champ]) && mb_strlen($body[$champ]) > self::LONGUEURS_MAX[$champ]) {
                return self::echec('incorrect_field', self::tropLong($champ));
            }
        }

        return null;
    }

    private static function tropLong(string $champ): string
    {
        return $champ . ' dépasse ' . self::LONGUEURS_MAX[$champ] . ' caractères.';
    }

    private static function vrai(mixed $valeur): bool
    {
        return filter_var($valeur, FILTER_VALIDATE_BOOLEAN);
    }

    private static function estUrlWeb(mixed $url): bool
    {
        return is_string($url)
            && filter_var($url, FILTER_VALIDATE_URL) !== false
            && in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true);
    }

    /**
     * @return array<string, mixed> soumission au format de DepotSoumissions::enregistrer()
     */
    private static function normaliser(array $body, string $id): array
    {
        // même nettoyage que le plugin d'origine (espace fine insécable encodée)
        $titre = (string) preg_replace('/%e2%80%89/i', '', $body['post_title']);
        $soumission = [
            'id_externe' => $id,
            'titre' => trim($titre),
            'contenu' => $body['post_content'],
            'categories' => array_values($body['categories'] ?? []),
            'image' => ($body['front_image'] ?? '') !== '' ? $body['front_image'] : null,
        ];
        foreach (self::CHAINES_FACULTATIVES as $champ => $cle) {
            $soumission[$cle] = ($body[$champ] ?? '') !== '' ? $body[$champ] : null;
        }
        $soumission['forcer'] = self::vrai($body['force'] ?? false);

        return $soumission;
    }

    private static function reponseEnregistrement(array $resultat): Response
    {
        $publie = ($resultat['statut'] ?? 'en_attente') === 'publie';

        return self::succes([
            'code' => $publie ? 'publish_success' : 'publish_pending',
            'message' => 'Success',
            'publish_status' => $publie ? 'publish' : 'pending',
            'article_url' => $publie ? ($resultat['url'] ?? null) : null,
            'identifiant' => $resultat['identifiant'] ?? null,
        ]);
    }

    private static function succes(array $donnees): Response
    {
        return new Response(['status' => true] + $donnees);
    }

    private static function echec(string $code, string $message, int $statut = 422): Response
    {
        return new Response(['status' => false, 'code' => $code, 'message' => $message], $statut);
    }
}
