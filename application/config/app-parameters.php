<?php


// De base laisser vide,
// sauf si l'application est hebergé sur de multiples sous-repertoire en ajoutant le slash_final
// par exemple : "my-app/"
//          ou : "my-app/dev/
define("BASE_SERVER_DIRECTORY", "");

// Optionnel! il n'est nécessaire que si vous l'utilisez dans les fichier de traitement
define('PATH_URL', $_SERVER['REQUEST_SCHEME'] . "://www.sand-framework.infoartsmedia.org");