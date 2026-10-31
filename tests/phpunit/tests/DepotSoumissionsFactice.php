<?php

declare(strict_types=1);

namespace Tests;

use MVC\Api\Contrat\DepotSoumissions;
use MVC\Api\Contrat\SoumissionNonModifiable;

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
        if ($this->mode === 'refus') {
            throw new SoumissionNonModifiable('Soumission déjà publiée.');
        }
        if ($this->mode === 'panne') {
            throw new \RuntimeException('Échec SQL dans /chemin/serveur/secret.php');
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
        $this->supprimees[] = $idExterne;

        return $idExterne !== '404';
    }
}
