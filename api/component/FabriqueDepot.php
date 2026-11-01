<?php

/**
 * Package SAND\Api
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 */

namespace SAND\Api\Component;

use SAND\Api\Contract\DepotSoumissions;

/**
 * Instancie le dépôt de soumissions déclaré par la constante API_DEPOT_SOUMISSIONS.
 */
final class FabriqueDepot
{
    /**
     * @param mixed $classe nom de classe, ou null/'' si aucun dépôt n'est configuré
     * @throws \LogicException si la classe n'existe pas ou n'implémente pas DepotSoumissions
     */
    public static function creer(mixed $classe): ?DepotSoumissions
    {
        if ($classe === null || $classe === '') {
            return null;
        }
        if (!is_string($classe) || !class_exists($classe) || !is_subclass_of($classe, DepotSoumissions::class)) {
            throw new \LogicException(
                'API_DEPOT_SOUMISSIONS : la classe déclarée doit implémenter ' . DepotSoumissions::class
            );
        }

        return new $classe();
    }

    public static function depuisConfiguration(): ?DepotSoumissions
    {
        return self::creer(defined('API_DEPOT_SOUMISSIONS') ? API_DEPOT_SOUMISSIONS : null);
    }
}
