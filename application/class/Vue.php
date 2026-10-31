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

    /** @var string Racine des gabarits (view, layout, system). */
    private $racineVues;
    /** @var string Dossier des contrôleurs de page. */
    private $dossierControleurs;
    /** @var string|null Cache Blade (null : racine des vues/cache). */
    private $cacheBlade;

    /**
     * Les trois derniers paramètres sont facultatifs : par défaut VIEW_PATH,
     * CONTROLLER_PATH et VIEW_PATH/cache (les tests y passent leurs dossiers).
     */
    public function __construct($page_params, $racineVues = null, $dossierControleurs = null, $cacheBlade = null)
    {
        // Gardés en propriétés : extract() ci-dessous ne peut pas les écraser.
        $this->racineVues = $racineVues ?? VIEW_PATH;
        $this->dossierControleurs = $dossierControleurs ?? CONTROLLER_PATH;
        $this->cacheBlade = $cacheBlade;
        unset($racineVues, $dossierControleurs, $cacheBlade);

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
                if (file_exists($this->racineVues.DIRECTORY_SEPARATOR."view".DIRECTORY_SEPARATOR.$name.".html.twig")) {
                    $flag_exist = true;
                }
                break;
            case 'blade':
            default:
                if (file_exists($this->racineVues.DIRECTORY_SEPARATOR."view".DIRECTORY_SEPARATOR.$name.".blade.php")) {
                    $flag_exist = true;
                }
        }

        ob_start();

        if($flag_exist){
            //l'inclusion du controlleur doit renvoyer le tableau $templateData
            require $this->dossierControleurs . DIRECTORY_SEPARATOR . $name . '.php';

            foreach ($page_params as $key => $value) {
                $templateData[$key] = $value;
            }

            // Rendu windwalker/renderer 4 : view > layout > system, cache Blade
            echo Rendu::rendre(
                $engine === 'twig' ? 'twig' : 'blade',
                $name,
                $templateData,
                $this->racineVues,
                $this->cacheBlade
            );
        } else {
            include $this->dossierControleurs . DIRECTORY_SEPARATOR . $name . '.php';
        }
        $this->ecran = ob_get_clean();
        return $this;
    }
}
