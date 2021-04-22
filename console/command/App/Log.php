<?php

namespace MVC\Command\App;

/**
 * Class Log
 * Commande Console permettant de manipuler les logs de la base de donnée
 * @package MVC\Command\GPeX
 */
class Log
{
    /**
     * Méthode permettant d'afficher l'aide de la commande
     */
    public static function help()
    {
        print "help ?";
    }

    /**
     * Méthode permettant de nettoyer les logs dont la date est iinférieur à aujourd'hui moins $nbMonth mois
     *
     * @param int $nbMonth
     */
    public static function clearOlderThan($nbMonth = 1)
    {
        $ajh = new \DateTime('now');
        $olderThan = $ajh->modify('-1 month');
        $olderThan = $olderThan->format('Y-m-d');

        //connection à la base de données
        /*$bdd = new \MVC\Classe\Bdd('bdd1');
        $sql = "DELETE FROM logs WHERE date_creation < {$olderThan};";
        $bdd->faireSQLRequete($sql);*/

        print "Log older than $nbMonth month cleared ! \n\n";
    }
}
