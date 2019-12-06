<?php


class module
{
    static public function help(){
        print "explaination of the command\n\n";
    }

    static public function add(){
        print "adding module...\n\n";
    }

    static public function remove(){
        print "removing module...\n\n";
    }

    static private function addSymfony(){
        /*
         * composer create-project symfony/website-skeleton my_module_name
         *
         * add symbolic links (not necessary, it comes with the way you progam on sand-module)
         *
         * add controlleur method
         * add model file
         * add blade view
         */
    }

    static private function addWorpress(){
        /*
         * git clone https://github.com/WordPress/WordPress.git wordpress
         * git checkout -b actualdev origin:5.3-branch
         *
         * create database if not
         *
         * create wp-config.php
         * add symbolic links
         * add controlleur method
         * add model file
         * add blade view
         */
    }

    static private function addPrestashop(){
        /*
         * git clone https://github.com/PrestaShop/PrestaShop.git prestashop
         * git checkout -b actualdev tags:1.7.6.2
         *
         * create database if not
         *
         * modify the /config/config.inc.php
         * add symbolic links
         * add controlleur method
         * add model file
         * add blade view
         *
         */


    }
}