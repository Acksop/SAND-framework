<?php

namespace MVC\Object;

/**
 * Class Guard
 *  Contrôle d'accès générique d'une page, d'un traitement ou d'un conduit.
 *  Les Guards nommés de l'application restent les méthodes Session::checkACL_*
 *  (annexe 20) ; ils s'appuient sur Guard::exiger().
 * @package MVC\Object
 */
class Guard
{
    /**
     * Exige que $_SESSION['acl_admin'] vaille l'une des valeurs données
     * (comparaison en chaîne : 1 et '1' sont égaux). Sinon, l'accès est refusé
     * par Session::refuserAcces(), qui termine la requête.
     *
     * Exemple : Guard::exiger(1) pour l'administrateur, Guard::exiger(2, 3)
     * pour deux profils.
     */
    public static function exiger(int|string ...$aclAutorisees): void
    {
        if (!self::autorise($aclAutorisees)) {
            Session::refuserAcces();
        }
    }

    /**
     * Décision du Guard, sans effet de bord.
     *
     * @param array<int|string> $aclAutorisees
     */
    private static function autorise(array $aclAutorisees): bool
    {
        $acl = $_SESSION['acl_admin'] ?? null;
        if ($acl === null || !is_scalar($acl)) {
            return false;
        }

        return in_array((string) $acl, array_map('strval', $aclAutorisees), true);
    }
}
