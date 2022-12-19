<?php


namespace SAND\Classe\Implement;

class Conduit extends Action
{
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
