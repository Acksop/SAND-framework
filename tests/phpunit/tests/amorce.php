<?php

/**
 * Amorce des tests PHPUnit du framework.
 *
 * Charge l'autoload Composer de la racine (constantes, paramètres, classes
 * MVC\*) puis l'action de test. Les fichiers application/config/*-parameters.php
 * doivent exister (copies des modèles .skel).
 */

// Valeurs de serveur attendues par les paramètres en ligne de commande.
$_SERVER['REQUEST_SCHEME'] = $_SERVER['REQUEST_SCHEME'] ?? 'http';
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';

require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestAction.php';
