<?php
$app = new MVC\Classe\Modular($name,'prestashop',$url_params);
//echo "Dawn Prestashop Error!";
/*
 * Avoid Warning on my version ....
 *
  on application/modules/prestashop/config/config.inc.php:125

    define('__PS_BASE_URI__', '/prestashop'.$context->shop->getBaseURI());

 */
//echo $app->load('prestashop');die();
$templateData = array('app' => $app);