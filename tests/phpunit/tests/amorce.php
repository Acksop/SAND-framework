<?php

/**
 * Amorce des tests PHPUnit du framework.
 *
 * Charge l'autoload Composer de la racine (constantes, paramètres, classes
 * MVC\*) puis l'action de test. Les fichiers application/config/*-parameters.php
 * doivent exister (copies des modèles .skel).
 */

// Dépréciations émises pendant le chargement (autoload, classes de test) :
// relevées ici, avant tout chargement, et vérifiées par RenduTest.
$GLOBALS['SAND_DEPRECIATIONS'] = [];
set_error_handler(
    static function (int $niveau, string $message, string $fichier = '', int $ligne = 0): bool {
        $GLOBALS['SAND_DEPRECIATIONS'][] = $message . ' (' . $fichier . ':' . $ligne . ')';

        return true;
    },
    E_DEPRECATED | E_USER_DEPRECATED
);

// Valeurs de serveur attendues par les paramètres en ligne de commande.
$_SERVER['REQUEST_SCHEME'] = $_SERVER['REQUEST_SCHEME'] ?? 'http';
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';

require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestAction.php';
