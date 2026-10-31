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
 */
final class Requete
{
    /** Préfixes qui désignent l'API (premier segment du chemin). */
    public const PREFIXES = ['wp-json', 'api'];

    private string $methode;

    public function __construct(
        string $methode,
        private string $uri,
        private string $corps = '',
        private ?string $autorisation = null
    ) {
        $this->methode = strtoupper($methode);
    }

    public static function depuisGlobales(): self
    {
        $corps = file_get_contents('php://input');

        return new self(
            (string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            (string) ($_SERVER['REQUEST_URI'] ?? '/'),
            $corps === false ? '' : $corps,
            self::enteteAutorisation()
        );
    }

    /**
     * Chemin de l'API sans le préfixe (« wp/v2/posts »), ou null si l'URI
     * ne commence pas par /wp-json ou /api (après BASE_SERVER_DIRECTORY).
     */
    public static function cheminApi(string $uri): ?string
    {
        $chemin = (string) parse_url($uri, PHP_URL_PATH);
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
     * et, sous la clé « body », le corps JSON décodé s'il s'agit d'un objet.
     *
     * @return array<string, mixed>
     */
    public function parametres(): array
    {
        $parametres = [];
        parse_str((string) parse_url($this->uri, PHP_URL_QUERY), $parametres);
        unset($parametres['body']);
        $corps = $this->corpsJson();
        if ($corps !== null) {
            $parametres['body'] = $corps;
        }

        return $parametres;
    }

    /**
     * @return array<mixed>|null
     */
    public function corpsJson(): ?array
    {
        if ($this->corps === '') {
            return null;
        }
        $decode = json_decode($this->corps, true);

        return is_array($decode) ? $decode : null;
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
