<?php

ini_set('error_reporting', ~E_ALL);
ini_set('display_errors', 0);

/**
 * GitList: an elegant and modern git repository viewer
 * http://gitlist.org
 */

if (!ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}

if (php_sapi_name() == 'cli-server' && file_exists(substr($_SERVER['REQUEST_URI'], 1))) {
    return false;
}

if (!is_writable(__DIR__ . DIRECTORY_SEPARATOR . 'cache')) {
    die(sprintf('The "%s" folder must be writable for GitList to run.', __DIR__ . DIRECTORY_SEPARATOR . 'cache'));
}

require 'vendor/autoload.php';

$config = GitList\Config::fromFile(__DIR__.'/config.ini');

if ($config->get('date', 'timezone')) {
    date_default_timezone_set($config->get('date', 'timezone'));
}

$app = require 'boot.php';
$app->run();

