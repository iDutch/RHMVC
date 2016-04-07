<?php

use Metaphore\Cache;
use Metaphore\Store\MemcachedStore;

abstract class AbstractController extends HMVC
{

    public function __construct($layout = null)
    {
        $this->layout = $layout;
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
                SELECT id, iso_code, is_default, is_online, is_enabled FROM language
            ');
        }, 3600);
    }

}
