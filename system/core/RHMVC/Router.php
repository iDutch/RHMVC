<?php

class Router
{

    private $routerconfig = array();

    public function __construct()
    {
        $this->routerconfig = require __DIR__ . '/../../../config/routes.global.php';
    }

    public function getRoute($uri)
    {
        $uri = str_replace($this->routerconfig['basepath'], '', $uri);
        foreach ($this->routerconfig['routes'] as $route => $routeinfo) {
            if(preg_match('#'.$route.'#', $uri, $matches)) {
                foreach ($routeinfo as $section => $controllers) {
                    if (is_array($controllers)) {
                        foreach($controllers as $k => $options) {
                            if (is_array($options)) {
                                foreach ($matches as $key => $value) {
                                    if (array_key_exists($key, $routeinfo[$section][$k]['params']) && !empty($value)) {
                                        $routeinfo[$section][$k]['params'][$key] = $value;
                                    }
                                }
                            }
                        }
                    }
                }
                return $routeinfo;
            }
        }
        return $this->routerconfig['routes']['^/404'];
    }

} 