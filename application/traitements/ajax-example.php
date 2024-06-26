<?php

header("Content-Type: text/plain");

\MVC\Object\Session::createAndTestSession();

$bdd = new Bdd();
$dns = \MVC\Domain\Dns::getDNS($bdd, $url_params['ip']);
$alias = array();
$reverseDns = \MVC\Domain\Dns::getReverseDNS($bdd, $url_params['ip']);
foreach ($reverseDns as $row) {
    $tab = \MVC\Domain\Dns::getAliasFromDNS($bdd, $row['valeur2_dns']);
    $alias[] = $tab;
}

//Affichage
echo "<h3>DNS : </h3><br />";
foreach ($dns as $row) {
    echo $row['valeur1_dns'];
    echo "<br/>";
}
echo "<br />";
echo "<h3>Reverse DNS : </h3><br />";
foreach ($reverseDns as $row) {
    echo $row['valeur2_dns'];
    echo "<br/>";
}
echo "<br />";
echo "<h3>Alias : </h3><br />";

if (isset($alias[0][0]['valeur1_dns'])) {
    foreach ($alias as $rAlias) {
        foreach ($rAlias as $row) {
            echo $row['valeur1_dns'];
            echo "<br/>";
        }
    }
}
