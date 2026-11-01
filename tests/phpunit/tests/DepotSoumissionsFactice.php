<?php

declare(strict_types=1);

namespace Tests;

use SAND\Api\Contract\DepotSoumissions;
use SAND\Api\Contract\SoumissionNonModifiable;

/**
 * Dépôt de soumissions des tests : garde en mémoire ce qu'il reçoit.
 */
final class DepotSoumissionsFactice implements DepotSoumissions
{
    /** @var array<int, array<string, mixed>> */
    public array $enregistrees = [];

    /** @var array<int, string> */
    public array $supprimees = [];

    /** Comportement de enregistrer() : 'attente', 'publie', 'refus' ou 'panne'. */
    public string $mode = 'attente';

    public function enregistrer(array $soumission): array
    {
        self::journaliser('enregistrer ' . $soumission['id_externe']);
        if ($this->mode === 'refus') {
            throw new SoumissionNonModifiable('Soumission déjà publiée.');
        }
        if ($this->mode === 'panne') {
            throw new \RuntimeException('Échec SQL ' . str_repeat('y', 500) . ' dans /chemin/serveur/secret.php');
        }
        $this->enregistrees[] = $soumission;

        return [
            'identifiant' => 'B-' . $soumission['id_externe'],
            'statut' => $this->mode === 'publie' ? 'publie' : 'en_attente',
            'url' => $this->mode === 'publie' ? 'https://exemple.test/article/' . $soumission['id_externe'] : null,
        ];
    }

    public function supprimer(string $idExterne): bool
    {
        self::journaliser('supprimer ' . $idExterne);
        $this->supprimees[] = $idExterne;

        return $idExterne !== '404';
    }

    /**
     * Tests HTTP (php -S) : les appels sont notés dans le fichier SAND_TEST_JOURNAL_DEPOT.
     */
    private static function journaliser(string $appel): void
    {
        if (defined('SAND_TEST_JOURNAL_DEPOT')) {
            file_put_contents(SAND_TEST_JOURNAL_DEPOT, $appel . PHP_EOL, FILE_APPEND);
        }
    }
}
