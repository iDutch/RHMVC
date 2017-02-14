<?php

namespace System\Core\RHMVC;

use Application\Models\Language;
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
            if((preg_match('#^(/(?<language>[a-z]{2}))?'.$route.'$#', $uri, $matches) && $get_method === self::URI) || ($name == $routename && $get_method === self::NAME)) {
                if ($get_method === self::URI) {
                    //Set language
                    $language = !empty($matches['language']) ? strtolower($matches['language']) : null;
                    $this->setLanguage($language);
                }
                if (!in_array($_SERVER['REQUEST_METHOD'], explode('|', $routedata['methods'])) && $get_method === self::URI) {
                    return new Route($this->routerconfig['routes']['405']);
                }
                return new Route($routedata, $matches);
            }
        }
        return new Route($this->routerconfig['routes']['404']);
    }

    private function setLanguage($lang = '')
    {
        $language = Language::first(['conditions' => ['iso_code = ?', strtoupper($lang)]]);
        if (count($language)) { //Language exists? Then set it.
            $_SESSION['language'] = $language->attributes();
        } else { //Not exists? Set to default language
            $_SESSION['language'] = Language::first(['conditions' => ['is_default = ?', 1]])->attributes();
        }
        setlocale(LC_TIME, $_SESSION['language']['locale']);
    }

    public static function loadRoute($uri)
    {
        $router =  new self();
        return $router->getRoute($uri, self::URI)->dispatch();
    }

    public static function getURLByRouteName($name, $params = [], $canonical = false)
    {
        $router =  new self();
        return $canonical ? APP_URL . $router->getRoute($name, self::NAME)->getURL($params) : $router->getRoute($name, self::NAME)->getURL($params);
    }

}
