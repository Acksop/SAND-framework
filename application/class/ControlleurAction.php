<?php

namespace MVC\Classe;

use MVC\Object\Environment;

class ControlleurAction
{
    /** Longueur maximale d'un message d'exception journalisé. */
    private const LONGUEUR_MESSAGE_JOURNAL = 200;

    /**
     * Appelle une action depuis un gabarit : 'nom' appelle NomAction::default(),
     * 'nom.methode' appelle NomAction::methode(), avec $data comme arguments.
     *
     * La classe est chargée depuis ACTION_PATH, sauf si elle est déjà définie.
     * Action introuvable (ou nom invalide) : journalisée dans app.error.log,
     * renvoie ''. Action qui lève une exception : journalisée, relancée en mode
     * débogage (Environment::debug()), sinon renvoie ''.
     */
    public static function inserer($action, $data = array())
    {
        //on extrait la classe d'appel de l'action
        $action = explode('.', (string) $action);
        $class = ucfirst($action[0]) . "Action";
        $appel = $class . '::' . ($action[1] ?? 'default');

        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $class)) {
            self::journaliser('action « ' . $appel . ' » introuvable : nom invalide');
            return '';
        }

        try {
            $fichier = ACTION_PATH . DIRECTORY_SEPARATOR . $class . ".php";
            if (!class_exists($class, false) && is_file($fichier)) {
                require_once $fichier;
            }
            if (!class_exists($class, false)) {
                $detail = Environment::debug() ? ' : ' . $fichier : '';
                self::journaliser('action « ' . $appel . ' » introuvable' . $detail);
                return '';
            }
            //On charge la classe Action de façon réflexive
            $slot = new $class();
            //méthode pointée (« nom.methode ») si fournie, sinon la méthode default()
            $method = $action[1] ?? 'default';

            return $slot->$method(...$data);
        } catch (\Throwable $e) {
            $message = 'action « ' . $appel . ' » : ' . get_class($e) . ' : ' . self::assainir($e->getMessage());
            if (Environment::debug()) {
                $message .= ' (' . $e->getFile() . ':' . $e->getLine() . ')';
            }
            self::journaliser($message);
            if (Environment::debug()) {
                throw $e;
            }

            return '';
        }
    }

    /**
     * Message d'exception sur une ligne, tronqué, sans la racine du projet.
     */
    private static function assainir(string $message): string
    {
        $message = str_replace(dirname(APPLICATION_PATH), '…', $message);

        return mb_substr(self::surUneLigne($message), 0, self::LONGUEUR_MESSAGE_JOURNAL);
    }

    private static function surUneLigne(string $message): string
    {
        return (string) preg_replace('/[\x00-\x1F\x7F]+/', ' ', $message);
    }

    private static function journaliser(string $message): void
    {
        Logger::addLog('error', '[ControlleurAction::inserer] ' . self::surUneLigne($message));
    }
}
