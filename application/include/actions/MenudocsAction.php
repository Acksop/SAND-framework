<?php

use MVC\Classe\Implement\Action;
use MVC\Classe\Response;

class MenudocsAction extends Action
{
    public function default()
    {


        $files = array();

        if ($handle = opendir(DATA_PATH . '/docs')) {

            while (false !== ($entry = readdir($handle))) {

                if ($entry != "." && $entry != "..") {

                    $files[] = $entry;
                }
            }

            closedir($handle);
        }

        asort($files);

        return $this->render('menu-docs', array('files' => $files));
    }

}
