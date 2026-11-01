<?php

/**
 * Package SAND\Classe
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 * @version 3.5
 */

namespace SAND\Classe;

/**
 * Construction des liens du framework (relatifs, absolus, assets).
 * Les méthodes statiques publiques de Url délèguent à cette classe.
 */
class UrlLiens
{
    public static function asset_rewrite($urlAsset)
    {
        return '/' . BASE_SERVER_DIRECTORY . $urlAsset;
    }

    public static function link_rewrite($isControlPatern, $page, $params = array())
    {
        if ($isControlPatern) {
            return self::controlLink_rewrite($page, $params);
        }
        return self::link_rewrite_slashParam($page, $params);
    }

    public static function absolute_link_rewrite($isControlPatern, $page, $params = array())
    {
        return self::racineHote() . self::link_rewrite($isControlPatern, $page, $params);
    }

    public static function getRootDirectoryUrl()
    {
        $url = self::racineHote();
        if (substr($url . "/" . BASE_SERVER_DIRECTORY, -1) == "/") {
            return substr($url . "/" . BASE_SERVER_DIRECTORY, 0, -1);
        }
        return $url . "/" . BASE_SERVER_DIRECTORY;
    }

    private static function link_rewrite_slashParam($page, $params = array())
    {
        return '/' . BASE_SERVER_DIRECTORY . $page . self::parametres($params);
    }

    private static function controlLink_rewrite($page, $params = array())
    {
        return '/' . BASE_SERVER_DIRECTORY . 'control' . '/' . $page . self::parametres($params);
    }

    /**
     * Paramètres sous la forme /cle/valeur ; null ou non-tableau : aucun paramètre.
     */
    private static function parametres($params): string
    {
        $stringParams = '';
        if (is_array($params)) {
            foreach ($params as $key => $values) {
                $stringParams .= "/" . $key . "/" . $values;
            }
        }
        return $stringParams;
    }

    /**
     * Schéma et hôte de la requête (schéma de PATH_URL s'il diffère), sans slash final ;
     * PATH_URL hors requête HTTP.
     */
    private static function racineHote(): string
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
            $scheme_tab = explode("://", PATH_URL);
            if ($scheme !== $scheme_tab[0]) {
                $scheme = $scheme_tab[0];
            }
            $url = $scheme . "://" . $_SERVER['HTTP_HOST'];
        } else {
            $url = PATH_URL;
        }
        if (substr($url, -1) == "/") {
            $url = substr($url, 0, -1);
        }
        return $url;
    }
}
