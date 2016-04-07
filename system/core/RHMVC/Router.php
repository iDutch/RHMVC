<?php

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