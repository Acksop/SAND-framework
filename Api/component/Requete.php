<?php

/**
 * Package MVC\Api
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 */

namespace MVC\Api\Component;

/**
 * Requête reçue par l'API : méthode, chemin, paramètres et en-tête Authorization.
 * Construite depuis les variables globales en production, à la main dans les tests.
 *
 * Le corps n'est lu qu'à la première demande (après l'authentification, dans
 * Router), dans la limite de API_TAILLE_MAX_CORPS octets (sinon 413) ; le JSON
 * est décodé avec une profondeur maximale de PROFONDEUR_JSON (sinon 400).
 */
final class Requete
{
    /** Préfixes qui désignent l'API (premier segment du chemin, casse exacte). */
    public const PREFIXES = ['wp-json', 'api'];
    public const PROFONDEUR_JSON = 32;

    private string $methode;
    private ?string $corpsLu = null;

    /**
     * @param string|\Closure $corps     corps brut, ou lecteur fn(int $octets): string
     * @param int|null        $tailleMax octets admis (défaut API_TAILLE_MAX_CORPS)
     */
    public function __construct(
        string $methode,
        private string $uri,
        private string|\Closure $corps = '',
        private ?string $autorisation = null,
        private ?int $tailleMax = null
    ) {
        $this->methode = strtoupper($methode);
    }

    /**
     * @param string $flux source du corps (php://input ; un fichier dans les tests)
     */
    public static function depuisGlobales(string $flux = 'php://input'): self
    {
        $longueur = $_SERVER['CONTENT_LENGTH'] ?? null;
        $lecteur = static function (int $octets) use ($flux, $longueur): string {
            if (is_numeric($longueur) && (int) $longueur >= $octets) {
                return str_repeat(' ', $octets); // annoncé trop long : refusé sans lire le flux
            }
            $ressource = str_starts_with($flux, 'php://') || is_readable($flux) ? fopen($flux, 'rb') : false;
            if ($ressource === false) {
                return '';
            }
            $contenu = stream_get_contents($ressource, $octets);
            fclose($ressource);

            return $contenu === false ? '' : $contenu;
        };

        return new self(
            (string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            (string) ($_SERVER['REQUEST_URI'] ?? '/'),
            $lecteur,
            self::enteteAutorisation()
        );
    }

    /**
     * Chemin de l'API sans le préfixe (« wp/v2/posts »), ou null si l'URI ne
     * commence pas par /wp-json ou /api (après BASE_SERVER_DIRECTORY).
     * Les barres obliques répétées sont ignorées ; « %2F » n'est pas décodé.
     */
    public static function cheminApi(string $uri): ?string
    {
        $chemin = preg_split('/[?#]/', $uri, 2)[0];
        $segments = array_values(array_filter(explode('/', $chemin), static fn ($s) => $s !== ''));
        $base = defined('BASE_SERVER_DIRECTORY') ? (string) BASE_SERVER_DIRECTORY : '';
        foreach (array_filter(explode('/', $base), static fn ($s) => $s !== '') as $sousRepertoire) {
            if (($segments[0] ?? null) === $sousRepertoire) {
                array_shift($segments);
            }
        }
        if (!in_array($segments[0] ?? null, self::PREFIXES, true)) {
            return null;
        }
        array_shift($segments);

        return implode('/', $segments);
    }

    public function methode(): string
    {
        return $this->methode;
    }

    public function chemin(): ?string
    {
        return self::cheminApi($this->uri);
    }

    public function autorisation(): ?string
    {
        return $this->autorisation;
    }

    /**
     * Paramètres de la chaîne de requête (un paramètre sans « = » vaut '')
     * et, sous la clé « body », le corps JSON décodé.
     *
     * @return array<string, mixed>
     */
    public function parametres(): array
    {
        $parametres = [];
        $chaine = preg_split('/[?#]/', $this->uri . '#', 3);
        parse_str(str_contains($this->uri, '?') ? (string) $chaine[1] : '', $parametres);
        unset($parametres['body']);
        $corps = $this->corpsJson();
        if ($corps !== null) {
            $parametres['body'] = $corps;
        }

        return $parametres;
    }

    /**
     * @return array<mixed>|null null si le corps est vide
     * @throws ApiException 413 si le corps dépasse la limite, 400 s'il n'est pas un objet JSON
     */
    public function corpsJson(): ?array
    {
        $corps = $this->corps();
        if (trim($corps) === '') {
            return null;
        }
        $decode = json_decode($corps, true, self::PROFONDEUR_JSON);
        if (!is_array($decode)) {
            throw new ApiException('Corps JSON invalide', 400);
        }

        return $decode;
    }

    private function corps(): string
    {
        if ($this->corpsLu === null) {
            $max = $this->tailleMax ?? (int) API_TAILLE_MAX_CORPS;
            $brut = $this->corps instanceof \Closure ? (string) ($this->corps)($max + 1) : $this->corps;
            if (strlen($brut) > $max) {
                throw new ApiException('Corps de requête trop volumineux', 413);
            }
            $this->corpsLu = $brut;
        }

        return $this->corpsLu;
    }

    /**
     * Apache + PHP-FPM ne transmet pas toujours HTTP_AUTHORIZATION :
     * voir la règle E=HTTP_AUTHORIZATION de public/.htaccess.
     */
    private static function enteteAutorisation(): ?string
    {
        foreach (['HTTP_AUTHORIZATION', 'REDIRECT_HTTP_AUTHORIZATION'] as $cle) {
            if (isset($_SERVER[$cle]) && $_SERVER[$cle] !== '') {
                return (string) $_SERVER[$cle];
            }
        }
        if (function_exists('apache_request_headers')) {
            foreach (apache_request_headers() as $nom => $valeur) {
                if (strcasecmp((string) $nom, 'Authorization') === 0) {
                    return (string) $valeur;
                }
            }
        }

        return null;
    }
}
