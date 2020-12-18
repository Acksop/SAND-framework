<?php
set_include_path(MODULES_PATH . '/wanewsletter/:' . get_include_path());
$app = new MVC\Classe\Modular($name, 'wanewsletter', $url_params);
$templateData = array('app' => $app);