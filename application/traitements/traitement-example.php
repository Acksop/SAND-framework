<?php

require OBJETS_PATH.DIRECTORY_SEPARATOR."host.class.php";

$path = PATH_URL;
$bdd = new Bdd();

Host::addHost($bdd,$_POST['ip'],$_POST['nom']);

switch($_POST['from']){
    case "vlan-zone+" :
    case "vlan-zone" :
        header("Location: $path{$_POST['from']}/vlan/{$_POST['vlan']}/alert/2/host/{$_POST['ip']}");
        break;
    case "ip-zone+" :
    case "ip-zone" :
        header("Location: $path{$_POST['from']}/motif-ip/{$_POST['motif-ip']}/alert/2/host/{$_POST['ip']}");
        break;
    default:
        header("Location: $path");
}