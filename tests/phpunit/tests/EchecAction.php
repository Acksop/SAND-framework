<?php

/**
 * Action de test en erreur : ControlleurAction::inserer('echec.lever') doit
 * journaliser l'exception et renvoyer '' hors débogage.
 */
// phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace -- les actions SAND sont globales
class EchecAction extends \MVC\Classe\Implement\Action
{
    public function lever($message = 'échec')
    {
        throw new \RuntimeException($message);
    }
}
