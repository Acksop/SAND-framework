<?php

namespace MVC\Command;
/**
 * Class Cache
 * Commande d'aide principale du Framework
 * @package MVC\Command\Sand
 */
class Help
{
    /**
     * Méthode permettant d'afficher l'aide de la commande
     */
    public static function help()
    {
        print_r(<<<EOD
Le système de commande du SAND Framework vous permet d'appeler :

\t action : permettant de créer et modifier les actions
\t cache : permettant de gérer le cache du sytème
\t conduit : permattant de créer et modifier les conduits
\t page : permattant créer, dupliquer et modifier les pages
\t symfony : permettant de gérer les modules symfony

Le système de commande de l'application vous permet d'appeler :

\t log : ou tout autre commande que vous auriez créé dans le dossier console/command/app

Vous pouvez obtenir l'aide des sous-commande en spéciant :

php bin.php macommande:help

EOD
        );
    }
}
