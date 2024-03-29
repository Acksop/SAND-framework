<?php

/**
 * Package MVC\Classe
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 * @version 3.5
 */

namespace MVC\Classe;

class Vue
{
    public $ecran;
    public $block_body;
    
    public function __construct($page_params)
    {
        //$templateData = array();
        $url_params = array();
        $templateData = $page_params;

        extract($page_params);
        //de base on ajoute les parametres du .model et ceux provenant de l'url
        foreach ($page_params['all_params'] as $key => $value) {
            //$templateData[$key] = $value;
            $_GET[$key] = $value;
            $url_params[$key] = $value;
        }


        if(!isset($engine)){$engine = 'blade';}
        $flag_exist = false;
        switch ($engine){
            case 'twig':
                if (file_exists(VIEW_PATH.DIRECTORY_SEPARATOR."view".DIRECTORY_SEPARATOR.$name.".html.twig")) {
                    $flag_exist = true;
                }
                break;
            case 'blade':
            default:
                if (file_exists(VIEW_PATH.DIRECTORY_SEPARATOR."view".DIRECTORY_SEPARATOR.$name.".blade.php")) {
                    $flag_exist = true;
                }
        }

        ob_start();

        if($flag_exist){
            //l'inclusion du controlleur doit renvoyer le tableau $templateData
            require CONTROLLER_PATH . DIRECTORY_SEPARATOR . $name . '.php';

            //WINWALKER TEMPLATING ENGINE
            $paths = new \SplPriorityQueue;

            $paths->insert(VIEW_PATH . DIRECTORY_SEPARATOR . "system", 100);
            $paths->insert(VIEW_PATH . DIRECTORY_SEPARATOR . "layout", 200);
            $paths->insert(VIEW_PATH . DIRECTORY_SEPARATOR . "view", 300);

            switch ($engine){
                case 'twig':
                    $renderer = new \Windwalker\Renderer\TwigRenderer($paths);
                    $renderer->addExtension(new \MVC\Classe\TwigControlleurAction);
                    $name .= '.html';
                    break;
                case 'blade':
                default:
                    $renderer = new \Windwalker\Renderer\BladeRenderer($paths, array('cache_path' => VIEW_PATH . DIRECTORY_SEPARATOR . "cache"));
            }

            foreach ($page_params as $key => $value) {
                $templateData[$key] = $value;
            }

            //WINWALKER TEMPLATING ENGINE RENDER
            echo $renderer->render($name, $templateData);
        } else {
            include CONTROLLER_PATH . DIRECTORY_SEPARATOR . $name . '.php';
        }
        $this->ecran = ob_get_clean();
        return $this;
    }
}
