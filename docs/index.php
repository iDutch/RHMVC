<?php
if (isset($_SERVER['IS_DEVEL'])) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

if (!file_exists(__DIR__ . '/../composer.lock')) {
    throw new Exception('RHMVC initialisation error: No \'composer.lock\' file found! Did you run: \'php composer.phar install\'?');
}
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../system/core/RHMVC/Router.php';
require __DIR__ . '/../system/core/RHMVC/Dispatcher.php';
require __DIR__ . '/../system/core/RHMVC/AbstractController.php';
require __DIR__ . '/../system/core/RHMVC/AbstractModel.php';
require __DIR__ . '/../system/core/RHMVC/Helper.php';
require __DIR__ . '/../system/core/RHMVC/View.php';
require __DIR__ . '/../system/core/RHMVC/DBAdapter.php';

$router = new Router();

$uri = $_SERVER['REQUEST_URI'];
$route = $router->getRoute($uri);

$dispatcher = new Dispatcher();
$dispatcher->dispatch($route);
