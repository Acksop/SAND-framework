<?php

namespace MVC\Command\Component;

use Tests\Behat\Gherkin\Loader\DirectoryLoaderTest;

/**
 * Classe Output permettant de créér des fichiers de sortie de données
 */
class Output
{
    /**
     * Fonction statique permettant d'écrire le fichier CSV de sortie
     *  en lui donnant un nom particulier
     *
     * @param string $name
     * @param array $agents_tab
     * @param \DateTime $date_debut
     * @return void
     */
    public static function createOutPutFile(string $name, array $agents_tab, \DateTime $date_debut)
    {
        $date_fin = new \DateTime('now');

        ob_start();

        echo "# ".$date_debut->format('d/m/Y H:i') . LINE_FEED;
        foreach ($agents_tab as $agent) {
            echo $agent . LINE_FEED;
        }
        echo "# FIN ".$date_fin->format('d/m/Y H:i') . LINE_FEED;

        $csv_file = ob_get_clean();

        file_put_contents(OUTPUT_PATH . DIRECTORY_SEPARATOR . $date_debut->format('Y-m-d-') . $name, $csv_file);
    }
}
