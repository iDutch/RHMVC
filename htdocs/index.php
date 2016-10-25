<?php
session_start();
$_SESSION['language_iso_code'] = 'en';
$_SESSION['language_id'] = 2;

use core\RHMVC\Router;

if (isset($_SERVER['IS_DEVEL'])) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

require __DIR__ . '/../config/settings.global.php';

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    throw new Exception('RHMVC initialisation error: No composer autoload file found! Did you run: \'php composer.phar install\'?');
}
require __DIR__ . '/../vendor/autoload.php';

//require __DIR__ . '/../system/core/RHMVC/DBAdapter.php';
require __DIR__ . '/../system/core/RHMVC/Router.php';
require __DIR__ . '/../system/core/RHMVC/Route.php';
require __DIR__ . '/../system/core/RHMVC/Translator.php';
require __DIR__ . '/../system/core/RHMVC/HMVC.php';
require __DIR__ . '/../system/core/RHMVC/AbstractController.php';
require __DIR__ . '/../system/core/RHMVC/AbstractModel.php';
require __DIR__ . '/../system/core/RHMVC/Helper.php';
require __DIR__ . '/../system/core/RHMVC/View.php';

require __DIR__ . '/../system/libs/Logger/Logger.php';
//require __DIR__ . '/../system/libs/PDODebugger/PDODebugger.php';

ActiveRecord\Config::initialize(function($cfg)
{
    $dbconfig = require CONFIG_DIR . '/database.local.php';
    $cfg->set_model_directory(MODEL_DIR);
    $cfg->set_connections($dbconfig);
});

$router = new Router();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->getRoute($uri)->dispatch();
