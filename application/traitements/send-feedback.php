<?php

/**
 *  Controlleur de traitement permettant de créér un individu sa période d'hébergement associé
 */


\MVC\Object\Session::createAndTestSession();

$note = $_POST['glsr-custom-options'];

header('location:'.\MVC\Classe\Url::link_rewrite(false, "donate", []));