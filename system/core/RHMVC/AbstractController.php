<?php

use Metaphore\Cache;
use Metaphore\Store\MemcachedStore;

abstract class AbstractController
{

    protected $layout;

    public function __construct($layout)
    {
        $this->layout = $layout;
    }

    protected function invokeController($controller, $action, array $params = array())
    {
        $controller_file = __DIR__ . '/../../../application/controllers/' . $controller . '.php';

        if (!file_exists($controller_file)) {
            throw new Exception('Controller error: Cannot invoke controller: \'' . $controller_file . '\'');
        }

        require_once $controller_file;
        $controller = new $controller($this->layout);

        if (!method_exists($controller, $action)){
            throw new Exception('Controller error: ' . $controller . ' :: ' . $action . ' does not exist!');
        }

        return call_user_func_array(array($controller, $action), $params);
    }

    protected function loadModel($model)
    {
        $model_file = __DIR__ . '/../../../application/models/' . $model . '.php';

        if (!file_exists($model_file)) {
            throw new Exception('Controller error: Cannot load model: \'' . $model_file . '\'');
        }
        require_once $model_file;
    }

    protected function getConfig($name)
    {
        $global_config_file = __DIR__ . '/../../../config/' . $name . '.global.php';
        $local_config_file  = __DIR__ . '/../../../config/' . $name . '.local.php';

        if (file_exists($global_config_file)) {
            $global = require $global_config_file;
            if (file_exists($local_config_file)) {
                $local = require $local_config_file;
                return array_merge($global, $local);
            }
            return $global;
        }
        throw new Exception('Controller error: Cannot load config: \'' . __DIR__ . '/../../../config/' . $name . '.global.php\'');
    }

    protected function getLanguages()
    {
        $memcached = new Memcached;
        $memcached->addServer('127.0.0.1', 11211);

        $cache = new Cache(new MemcachedStore($memcached));

        return $cache->cache('languages', function() {
            return DBAdapter::getInstance()->query('
                SELECT id, iso_code, CAST(is_default AS UNSIGNED) AS is_default FROM languages
            ');
        }, 3600);
    }

}
