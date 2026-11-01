<?php

namespace SAND\Object;

/**
 * Class Guard
 *  Contrôle d'accès générique d'une page, d'un traitement ou d'un conduit.
 *  Les Guards nommés de l'application restent les méthodes Session::checkACL_*
 *  (annexe 20) ; ils s'appuient sur Guard::exiger().
 * @package SAND\Object
 */
class Guard
{
    /**
     * Exige que $_SESSION[SESSION_CLE_ACL] (par défaut 'acl_admin') vaille
     * l'une des valeurs données. Seuls un entier ou une chaîne d'entier sont
     * acceptés (1 et '1' sont égaux ; true, 1.0 ou 'admin' sont refusés).
     * Sinon, l'accès est refusé par Session::refuserAcces(), qui termine la
     * requête.
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
        $acl = $_SESSION[SESSION_CLE_ACL] ?? null;
        $estEntier = is_int($acl) || (is_string($acl) && preg_match('/^-?\d+$/', $acl) === 1);
        if (!$estEntier) {
            return false;
        }

        return in_array((string) $acl, array_map('strval', $aclAutorisees), true);
    }
}
