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
        $this->detectLanguage($uri);
        foreach ($this->routerconfig['routes'] as $route => $routeinfo) {
            if(preg_match('#^(/([a-z]{2}))?'.$route.'$#', $uri, $matches)) {
                if(!empty($routeinfo['methods']) && !in_array($_SERVER['REQUEST_METHOD'], explode('|', $routeinfo['methods']))) {
                    return $this->routerconfig['routes']['/405'];
                }
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
        return $this->routerconfig['routes']['/404'];
    }

    private function detectLanguage($uri)
    {
        if (preg_match('#^/(?<language_iso_code>[a-z]{2})#', $uri, $matches)) {
            $language = DBAdapter::getInstance()->query('SELECT id, iso_code FROM language WHERE LOWER(iso_code) = :iso_code', array(
               'iso_code' => array('value' => $matches['language_iso_code'])
            ));
            if (count($language) == 1) {
                $_SESSION['language_iso_code'] = strtolower($language[0]->iso_code);
                $_SESSION['language_id'] = $language[0]->id;
            } else {
                $language = DBAdapter::getInstance()->query('SELECT id, iso_code FROM language WHERE is_default = 1');
                $_SESSION['language_iso_code'] = strtolower($language[0]->iso_code);
                $_SESSION['language_id'] = $language[0]->id;
            }
        } else if (!isset($_SESSION['language_iso_code']) || !isset($_SESSION['language_id'])) {
            $language = DBAdapter::getInstance()->query('SELECT id, iso_code FROM language WHERE is_default = 1');
            $_SESSION['language_iso_code'] = strtolower($language[0]->iso_code);
            $_SESSION['language_id'] = $language[0]->id;
        }
    }

} 