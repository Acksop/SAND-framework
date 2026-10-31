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

    /**
     * Ouvre un tampon de sortie puis lève une exception sans le refermer.
     */
    public function leverDansUnTampon()
    {
        ob_start();
        echo 'sortie partielle';
        throw new \RuntimeException('tampon laissé ouvert');
    }
}
