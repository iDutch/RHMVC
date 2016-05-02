<?php
session_start();
$_SESSION['language_iso_code'] = 'en';
$_SESSION['language_id'] = 2;


if (isset($_SERVER['IS_DEVEL'])) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}



if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    throw new Exception('RHMVC initialisation error: No composer autoload file found! Did you run: \'php composer.phar install\'?');
}
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../system/core/RHMVC/Router.php';
require __DIR__ . '/../system/core/RHMVC/Dispatcher.php';
require __DIR__ . '/../system/core/RHMVC/Translator.php';
require __DIR__ . '/../system/core/RHMVC/HMVC.php';
require __DIR__ . '/../system/core/RHMVC/AbstractController.php';
require __DIR__ . '/../system/core/RHMVC/AbstractModel.php';
require __DIR__ . '/../system/core/RHMVC/Helper.php';
require __DIR__ . '/../system/core/RHMVC/View.php';
require __DIR__ . '/../system/core/RHMVC/DBAdapter.php';

require __DIR__ . '/../application/controllers/ACLTrait.php';
require __DIR__ . '/../system/libs/Logger/Logger.php';

$router = new Router();

$uri = $_SERVER['REQUEST_URI'];
$route = $router->getRoute($uri);

$dispatcher = new Dispatcher();
$dispatcher->dispatch($route);

