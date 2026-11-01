<?php

/**
 * Package SAND\Classe
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 * @version 3.5
 */

namespace SAND\Classe;

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

    /** @var string|null Titre de la page rendue (page_title de son .model). */
    private static $titrePage = null;
    /** @var string|null Description de la page rendue (description de son .model). */
    private static $descriptionPage = null;

    /**
     * Titre de la page : page_title de la première vue rendue qui en a un
     * (null si aucune). Un site hôte qui embarque un module s'en sert pour son <title>.
     */
    public static function titrePage(): ?string
    {
        return self::$titrePage;
    }

    /**
     * Description de la page : description du .model de la vue qui a fixé
     * titrePage() (null si absente).
     */
    public static function descriptionPage(): ?string
    {
        return self::$descriptionPage;
    }

    /**
     * Oublie le titre et la description retenus (tests, traitement de
     * plusieurs pages dans un même processus).
     */
    public static function reinitialiserPage(): void
    {
        self::$titrePage = null;
        self::$descriptionPage = null;
    }

    /**
     * Retient page_title et description de la première vue qui a un titre.
     */
    private static function retenirPage(array $page_params): void
    {
        if (self::$titrePage !== null || !isset($page_params['page_title']) || !is_scalar($page_params['page_title'])) {
            return;
        }
        self::$titrePage = (string) $page_params['page_title'];
        $description = $page_params['description'] ?? null;
        self::$descriptionPage = is_scalar($description) ? (string) $description : null;
    }

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
        self::retenirPage($page_params);

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
