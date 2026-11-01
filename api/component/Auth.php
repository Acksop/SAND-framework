<?php

/**
 * Package MVC\Api
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 */

namespace MVC\Api\Component;

/**
 * Authentification par clé d'API (en-tête « Authorization: Bearer <clé> »),
 * indépendante des sessions web.
 *
 * Les lectures (GET, HEAD, OPTIONS) restent publiques ; les écritures
 * (POST, PUT, PATCH, DELETE) exigent une clé valide.
 *
 * Les clés ne sont jamais stockées en clair : seule leur empreinte SHA-256
 * est connue (constante API_KEYS, voir api/config/api_keys.php.skel) et
 * comparée avec hash_equals(). Clé absente ou invalide : même 401, même message.
 */
final class Auth
{
    private const METHODES_PUBLIQUES = ['GET', 'HEAD', 'OPTIONS'];
    private const MESSAGE = 'Authentification requise';

    /** Valeur factice de api_keys.php.skel : jamais acceptée, même copiée telle quelle. */
    public const CLE_FACTICE = 'remplacer-par-une-cle-generee-par-bin2hex-random_bytes-32';

    /** @var array<string, string> empreinte en minuscules => nom du client */
    private array $empreintes = [];

    /**
     * @param array<string, string> $empreintes empreinte SHA-256 de la clé => nom du client
     */
    public function __construct(array $empreintes)
    {
        // minuscules d'abord : une empreinte factice écrite en majuscules est aussi retirée
        foreach ($empreintes as $empreinte => $nom) {
            $this->empreintes[strtolower(trim((string) $empreinte))] = (string) $nom;
        }
        unset($this->empreintes[hash('sha256', self::CLE_FACTICE)]);
    }

    public static function depuisConfiguration(): self
    {
        return new self(defined('API_KEYS') && is_array(API_KEYS) ? API_KEYS : []);
    }

    /**
     * Ne fait rien pour une méthode publique ; sinon renvoie le nom du client
     * ou lève une ApiException 401.
     */
    public function exiger(Requete $requete): ?string
    {
        if (in_array($requete->methode(), self::METHODES_PUBLIQUES, true)) {
            return null;
        }
        $client = $this->client($requete->autorisation());
        if ($client === null) {
            throw new ApiException(self::MESSAGE, 401, ['WWW-Authenticate' => 'Bearer']);
        }

        return $client;
    }

    private function client(?string $autorisation): ?string
    {
        if ($autorisation === null || stripos($autorisation, 'Bearer ') !== 0) {
            return null;
        }
        $cle = trim(substr($autorisation, 7));
        if ($cle === '') {
            return null;
        }
        $empreinte = hash('sha256', $cle);
        $trouve = null;
        foreach ($this->empreintes as $stockee => $nom) {
            // pas d'arrêt anticipé : la durée ne dépend pas de la position de la clé
            if (hash_equals((string) $stockee, $empreinte) && $trouve === null) {
                $trouve = $nom;
            }
        }

        return $trouve;
    }
}
