<?php
session_start();

setlocale(LC_TIME, 'nl_NL');

use System\Core\RHMVC\Router;

if (isset($_SERVER['IS_DEVEL'])) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

require __DIR__ . '/../Config/settings.global.php';

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    throw new Exception('RHMVC initialisation error: No composer autoload file found! Did you run: \'php composer.phar install\'?');
}
require __DIR__ . '/../vendor/autoload.php';

ActiveRecord\Config::initialize(function($cfg)
{
    $dbconfig = require CONFIG_DIR . '/database.local.php';
    $cfg->set_model_directory(MODEL_DIR);
    $cfg->set_connections($dbconfig);
});

$router = new Router();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->getRoute($uri)->dispatch();
