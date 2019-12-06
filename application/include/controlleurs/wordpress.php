<?php
\MVC\Classe\Session::start();
$app = new MVC\Classe\Modular($name,'wordpress',$url_params);
//echo "Dawn Wordpress Error!";
/*
 * Avoid Warning on my version ....
 *
  on application/modules/wordpress/wp-admin/menu-header.php:74
	if(!is_array($menu)){$menu = array();}

  on application/modules/wordpress/wp-admin/includes/plugin.php:2047

        if(isset($_wp_menu_nopriv)) {
            foreach (array_keys($_wp_submenu_nopriv) as $key) {
                if (isset($_wp_submenu_nopriv[$key][$pagenow])) {
                    return false;
                }
                if (isset($plugin_page) && isset($_wp_submenu_nopriv[$key][$plugin_page])) {
                    return false;
                }
            }
        }


 */


//echo $app->load('wordpress'); die();
$templateData = array('app' => $app);