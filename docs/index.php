<?php
ini_set('display_errors', 1);

require __DIR__ . '/../system/core/RHMVC/Router.php';
require __DIR__ . '/../system/core/RHMVC/Dispatcher.php';
require __DIR__ . '/../system/core/RHMVC/AbstractController.php';
require __DIR__ . '/../system/core/RHMVC/AbstractModel.php';
require __DIR__ . '/../system/core/RHMVC/Helper.php';
require __DIR__ . '/../system/core/RHMVC/View.php';

$router = new Router();

$uri = $_SERVER['REQUEST_URI'];
$route = $router->getRoute($uri);

//var_dump($route['content']); exit;

$dispatcher = new Dispatcher();
$dispatcher->dispatch($route);
