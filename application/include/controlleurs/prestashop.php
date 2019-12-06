<?php
$app = new MVC\Classe\Modular($name,'prestashop',$url_params);
//echo "Dawn Prestashop Error!";
echo $app->load('prestashop');die();
$templateData = array('app' => $app);