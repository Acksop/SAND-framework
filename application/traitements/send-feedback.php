<?php

/**
 *  Controlleur de traitement permettant de créér un individu sa période d'hébergement associé
 */


\SAND\Object\Session::createAndTestSession();

$note = $_POST['glsr-custom-options'];

header('location:'. \SAND\Classe\Url::link_rewrite(false, "donate", []));