<?php

namespace MVC\Command\Component;

/**
 *  Composant permettant de logger les erreurs de la commande courante
 * @package Default
 */
class Error
{
    /**
     * Fonction courante permettant de logger les erreurs obtenues dans un fichier
     *
     * @param array $errors
     * @return void
     */
    public static function logErrors(array $errors)
    {
        $date = new \DateTime('now');
        // log connection errors to the web service
        ob_start();
        foreach ($errors as $key0 => $value0) {
            echo LINE_FEED.LINE_FEED."$key0 : ";
            if (is_array($value0)) {
                foreach ($value0 as $key1 => $value1) {
                    echo LINE_FEED.LINE_FEED."--$key1 : ";
                    if (is_array($value1)) {
                        foreach ($value1 as $key2 => $value2) {
                            echo LINE_FEED.LINE_FEED."----$key2 : ";
                            if (is_array($value2)) {
                                foreach ($value2 as $key3 => $value3) {
                                    echo LINE_FEED.LINE_FEED."------$key3 : ";
                                    if (is_array($value3)) {
                                        print_r("------>".$value3. " : array");
                                    } else {
                                        print_r("------>".$value3);
                                    }
                                }
                            } else {
                                print_r("---->".$value2);
                            }
                        }
                    } else {
                        print_r("-->".$value1);
                    }
                }
            } else {
                print_r($value0);
            }
        }
        $write_string = ob_get_clean();
        file_put_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . "output" . DIRECTORY_SEPARATOR . ENV . DIRECTORY_SEPARATOR . "errors".$date->format("Y-m-d").".log", $write_string);

        return;
    }
}
