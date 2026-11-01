<?php

/**
 * Package SAND\Api
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 */

namespace SAND\Api\Contract;

/**
 * Stockage des soumissions reçues par la route Ereferer.
 *
 * Le framework valide la requête ; l'hôte implémente cette interface et
 * déclare sa classe dans la constante API_DEPOT_SOUMISSIONS.
 * La classe est instanciée sans argument.
 */
interface DepotSoumissions
{
    /**
     * Crée la soumission, ou la met à jour si id_externe est déjà connu.
     *
     * @param array{
     *     id_externe: string,
     *     titre: string,
     *     contenu: string,
     *     categories: array<int|string, scalar>,
     *     image: ?string,
     *     meta_titre: ?string,
     *     meta_description: ?string,
     *     source_image: ?string,
     *     forcer: bool
     * } $soumission Champs déjà validés ; le contenu est du HTML à assainir par l'hôte.
     *
     * @return array{identifiant?: int|string|null, statut?: string, url?: ?string}
     *     statut : 'en_attente' (par défaut) ou 'publie' ; url : adresse de l'article publié.
     *
     * @throws SoumissionNonModifiable si la soumission est publiée et que forcer vaut false.
     */
    public function enregistrer(array $soumission): array;

    /**
     * Supprime (ou dépublie) la soumission ; false si id_externe est inconnu.
     */
    public function supprimer(string $idExterne): bool;
}
