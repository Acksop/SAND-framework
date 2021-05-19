<?php

namespace MVC\Component;

/**
 *  Composant permettant de créér des fichiers de sortie de données
 * @package Default
 */
class Output
{
    /**
     * Fonction statique permettant d'écrire un fichier de sortie
     *  en lui donnant un nom particulier
     *
     * @param string $name
     * @param string $data
     * @return void
     */
    public static function createOutPutFile(string $name, $data)
    {

        $date_debut = new \DateTime('now');

        ob_start();

        echo "# ".$date_debut->format('d/m/Y H:i:s') . LINE_FEED;
        echo $data . LINE_FEED;
        $date_fin = new \DateTime('now');
        echo "# FIN ".$date_fin->format('d/m/Y H:i:s') . LINE_FEED;

        $csv_file = ob_get_clean();

        //on insère la date du jour au début du nom de fichier
        $name = $date_debut->format("Y-m-d-").$name;

        file_put_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . $name, $csv_file);
    }
}
