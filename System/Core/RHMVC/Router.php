<?php

namespace System\Core\RHMVC;

use Exception;

class Router
{

    const URI = 1;
    const NAME = 2;

    private $routerconfig = array();

    public function __construct()
    {
        $global_config_file = CONFIG_DIR . 'routes.global.php';
        $local_config_file = CONFIG_DIR . 'routes.local.php';

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

    public function getRoute($search, $get_method = self::URI)
    {
        $uri = $routename = null;
        if ($get_method === self::URI) {
            $uri = str_replace($this->routerconfig['basepath'], '', $search);
        } else {
            $routename = $search;
        }
        foreach ($this->routerconfig['routes'] as $name => $routedata) {
            $route = $routedata['route'];
            if((preg_match('#^(/([a-z]{2}))?'.$route.'$#', $uri, $matches) && $get_method === self::URI) || ($name == $routename && $get_method === self::NAME)) {
                if (!in_array($_SERVER['REQUEST_METHOD'], explode('|', $routedata['methods'])) && $get_method === self::URI) {
                    return new Route($this->routerconfig['routes']['405']);
                }
                return new Route($routedata, $matches);
            }
        }
        return new Route($this->routerconfig['routes']['404']);
    }

    public static function loadRoute($uri)
    {
        $router =  new self();
        return $router->getRoute($uri, self::URI)->dispatch();
    }

    /**
     * Parse route to regex
     *
     * @param $route
     * @return String
     */
    private function toRegex($route)
    {
        if (preg_match_all('#((\\[[\\w-\\\\/]+[\\]]*)|((\\[)?(\\:[\\w-]+(\\[[\\w-\\\\]+\\]|\\([\\w|]+\\))(\\+|({[0-9]+}|{[0-9]+,[0-9]+}))?(\\/)?)(\\])*))#', $route, $matches)) {
            foreach ($matches[0] as $match) {
                $replaced_match = preg_replace('#([\\[]?):([\\w-]+)(\\[[\\w-]+\\]|\\([\\w-|]+\\))(\\+|({[0-9]+}|{[0-9]+,[0-9]+}))?([\\]]*)#','$1?<$2>$3$4$6', $match);
                if (strpos($match,':') === false) { //Route part is optional but not a variable
                    $replaced_match = str_replace(['[',']'], ['(',')?'], $replaced_match);
                } else {
                    if (preg_match('#^([\[]+)#', $replaced_match, $submatches)) { //Opening bracket found indicating variable URL par
                        //Replace with parenthesis
                        $replaced_match = str_replace('[','(', $submatches[0]) . substr($replaced_match, strlen($submatches[0]));
                    }
                    if (preg_match('#([\]]+)$#', $replaced_match, $submatches)) { // One or more closing brackets found? Replace them with closing parentheses each with a '?'
                        //Closing optional variable URL part
                        $replaced_match = substr($replaced_match, 0, -strlen($submatches[0])) . str_replace(']',')?', $submatches[0]);
                    }
                    //Prepend parenthesis
                    $replaced_match = '(' . $replaced_match;
                    //Closing mandatory variable url part
                    $replaced_match = $replaced_match . ')';
                }
                $route = str_replace($match, $replaced_match, $route);
            }
            return $route;
        }
        return $route;
    }

    public static function getURLByRouteName($name, $params = [])
    {
        $router =  new self();
        return $router->getRoute($name, self::NAME)->getURL($params);
    }

}
