<?php


namespace SAND\Classe\Implement;

/**
 * Conduit appelé par une route Symfony (application/config/files/routing.yml).
 *
 * Les paramètres de la route (ex. {id}) deviennent des propriétés du conduit
 * ($this->id) sans être déclarés : les propriétés dynamiques sont donc
 * autorisées (dépréciées sinon depuis PHP 8.2).
 */
#[\AllowDynamicProperties]
class Conduit extends Action
{
    /** @var array Données de la page transmises aux gabarits. */
    public $templateData = [];

    public function initialize($application)
    {

        //extract($application->modele->page);
        foreach ($application->url->page as $key => $value) {
                $this->templateData[$key] = $value;
        }
        //Export variable from conduit
        foreach ($application->route as $key => $value) {
            if ($key != "controller") {
                if ($key != "_route") {
                    $this->$key = $value;
                }
            }
        }
        return;
    }
}
