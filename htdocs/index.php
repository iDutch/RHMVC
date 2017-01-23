<?php

session_start();

use System\Core\RHMVC\Router;
use System\Libs\Logger\Logger;
use Application\Models\User;

if ((isset($_SERVER['IS_DEVELOPMENT']) && $_SERVER['IS_DEVELOPMENT'] == "True") || (isset($_SERVER['IS_TEST']) && $_SERVER['IS_TEST'] == "True")) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

require __DIR__ . '/../Config/settings.global.php';

try {
    if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
        throw new Exception('RHMVC initialisation error: No composer autoload file found! Did you run: \'php composer.phar install\'?');
    }
    require __DIR__ . '/../vendor/autoload.php';

    ActiveRecord\Config::initialize(function ($cfg) {
        $dbconfig = require CONFIG_DIR . '/database.local.php';
        $cfg->set_model_directory(MODEL_DIR);
        $cfg->set_connections($dbconfig);
    });
    ActiveRecord\Connection::$datetime_format = 'Y-m-d H:i:s';

    if (!isset($_SESSION['user'])) {
        User::authenticateByCookie();
    }

    $router = new Router();

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $router->getRoute($uri)->dispatch();
} catch (Exception $e) {
    if ((isset($_SERVER['IS_DEVELOPMENT']) && $_SERVER['IS_DEVELOPMENT'] == "True") || (isset($_SERVER['IS_TEST']) && $_SERVER['IS_TEST'] == "True")) {
        echo $e->getMessage();
    }

    Logger::log('Caught exception: ' . $e->getMessage(), Logger::EXCEPTION);

    $router = new Router();
    $router->getRoute('418', Router::NAME)->dispatch();
}