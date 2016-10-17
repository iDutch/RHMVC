<?php

namespace core\RHMVC;

use core\RHMVC\Route;

class Router
{

    private $routerconfig = array();

    public function __construct()
    {
        $global_config_file = __DIR__ . '/../../../config/routes.global.php';
        $local_config_file = __DIR__ . '/../../../config/routes.local.php';

        if (file_exists($global_config_file)) {
            $global = require $global_config_file;
            $this->routerconfig = $global;
            if(file_exists($local_config_file)){
                $local = $local_config_file;
                $this->routerconfig = array_merge($global, $local);
            }
        } else {
            throw new Exception('Router error: Cannot load global config!');
        }
    }

    public function getRoute($uri)
    {
        $uri = str_replace($this->routerconfig['basepath'], '', $uri);
        foreach ($this->routerconfig['routes'] as $route => $routedata) {
            if(preg_match('#^(/([a-z]{2}))?'.$route.'$#', $uri, $matches)) {
                if (!in_array($_SERVER['REQUEST_METHOD'], explode('|', $routedata['methods']))) {
                    return new Route($this->routerconfig['routes']['/405']);
                }
                return new Route($routedata, $matches);
            }
        }
        return new Route($this->routerconfig['routes']['/404']);
    }
    
    public static function loadRoute($uri)
    {
        $router =  new self();
        return $router->getRoute($uri)->dispatch();
    }

}
