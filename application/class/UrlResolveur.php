<?php

/**
 * Package SAND\Classe
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 * @version 3.5
 */

namespace SAND\Classe;

use SAND\Object\Environment;

/**
 * Résolution du fichier contrôleur d'une page : page MVC, traitement PRG,
 * réponse REST ou HTTP selon la méthode, puis repli sur le contrôleur d'erreur.
 */
class UrlResolveur
{
    /**
     * @param array $page page analysée par Url (name, control, params…)
     * @param string $method méthode HTTP
     * @param bool $appRequest requête d'une application (curl…)
     * @return array [page, pageFile]
     */
    public static function resoudre(array $page, $method, $appRequest): array
    {
        $pageFile = CONTROLLERS_PATH . DIRECTORY_SEPARATOR . $page['name'] . '.php';
        //verification de l'existence de la page dans les controlleurs
        if ($page['control']) {
            $pageFile = TRAITEMENT_PATH . DIRECTORY_SEPARATOR . $page['name'] . '.php';
        } else {
            //recherche du fichier controlleur correpondant HTTP1.1 ou HTTP1.0
            switch ($method) {
                //cas des requètes HTTP1.1
                case 'GET':
                case 'POST':
                    if ($appRequest) {
                        $page['name'] = ucfirst($page['name']);
                        $pageFile = self::reponse($page['name']);
                        // aucune réponse REST/HTTP pour cette page : on sert la page MVC classique
                        $pageMvc = CONTROLLERS_PATH . DIRECTORY_SEPARATOR . lcfirst($page['name']) . '.php';
                        if (!file_exists($pageFile) && file_exists($pageMvc)) {
                            $page['name'] = lcfirst($page['name']);
                            $pageFile = $pageMvc;
                        }
                    } else {
                        $pageFile = CONTROLLERS_PATH . DIRECTORY_SEPARATOR . $page['name'] . '.php';
                        if (!file_exists($pageFile)) {
                            $page['name'] = ucfirst($page['name']);
                            $pageFile = self::reponse($page['name']);
                        }
                    }
                    break;
                case 'PUT':
                case 'DELETE':
                    $page['name'] = ucfirst($page['name']);
                    $pageFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $page['name'] . 'RestReponse.php';
            }
        }

        //Test sur l'existence de la page actuelle et renvoi sur le controlleur 404 d'erreur si besoin
        if (!file_exists($pageFile)) {
            if ($appRequest) {
                $page['name'] = 'Error';
                $pageFile = self::reponse($page['name']);
            } else {
                $page['name'] = 'error';
                $pageFile = CONTROLLERS_PATH . DIRECTORY_SEPARATOR . $page['name'] . '.php';
                if (!file_exists($pageFile)) {
                    $page['name'] = ucfirst($page['name']);
                    $pageFile = self::reponse($page['name']);
                    if (!file_exists($pageFile)) {
                        self::controleurErreurIntrouvable($page['name']);
                    }
                }
            }
        }
        return [$page, $pageFile];
    }

    /**
     * Fichier *RestReponse.php s'il existe, sinon *HttpReponse.php.
     */
    private static function reponse(string $nom): string
    {
        $pageFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $nom . 'RestReponse.php';
        if (!file_exists($pageFile)) {
            $pageFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $nom . 'HttpReponse.php';
        }
        return $pageFile;
    }

    /**
     * Aucun contrôleur d'erreur : erreur journalisée, code 500, arrêt.
     */
    private static function controleurErreurIntrouvable(string $nom): void
    {
        $erreur = "le controlleur 404 d'erreur n'existe pas : \n"
            . CONTROLLERS_PATH . DIRECTORY_SEPARATOR . $nom . '.php' . "\n"
            . "ou l'un des controlleurs 404 (REST ou HTTP) n'existe pas : \n"
            . CONTROLLER_PATH . DIRECTORY_SEPARATOR . $nom . 'RestReponse.php \n'
            . " ou \n"
            . CONTROLLER_PATH . DIRECTORY_SEPARATOR . $nom . 'HttpReponse.php';
        // erreur journalisée ; les chemins du serveur ne sont affichés qu'en DEBUG
        Logger::addLog('error', $erreur);
        http_response_code(500);
        die(self::messageErreurConfiguration($erreur, Environment::debug()));
    }

    /**
     * Message affiché pour une erreur de configuration : le détail (chemins du
     * serveur compris) n'est montré qu'en mode débogage.
     */
    private static function messageErreurConfiguration(string $detail, bool $debug): string
    {
        return $debug ? $detail : "Erreur de configuration : contrôleur d'erreur introuvable.";
    }
}
