<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 7-5-15
 * Time: 16:58
 */

class Profiles {

    private $platform;
    private $platform_settings;

    public function __construct($platform)
    {
        $this->platform = $platform;
        $this->platform_settings = $this->loadPlatformSettings($platform);
    }

    private function loadPlatformSettings($platform){
        $local = array();

        $global = require __DIR__ . '/../config/platforms.global.php';
        if(file_exists(__DIR__ . '/../config/platforms.local.php')){
            $local = require __DIR__ . '/../config/platforms.local.php';
        }
        $settings = array_merge($global, $local);

        return $settings[$platform];
    }

    private function getFromCache($request)
    {
        $mapper = new Mapper($this->platform_settings['api_type']);

        //Setup memcached
        $memcached = new Memcached;
        $memcached->addServer('127.0.0.1', 11211);

        //Load profiles from Memcached when exists else do API call
        $key_location = md5($this->platform.$request);
        if (!$profiles = $memcached->get($key_location)) {
            //Cache mapped response so we don't need to map everytime.
            $profiles = $mapper->mapResponse((array) json_decode(file_get_contents($this->platform_settings['url'] . $this->platform_settings['api'] . '?' . $this->platform_settings['defaultquery'] . '&' . $request)));
            $memcached->set($key_location, $profiles, $this->platform_settings['cache_ttl'] || 3600);
        }
        return $profiles;
    }

    public function get($query_string)
    {
        $mapper = new Mapper($this->platform_settings['api_type']);

        $request = http_build_query($mapper->mapRequest($query_string));

        return $this->getFromCache($request);
    }

    public function getPlatformSettings()
    {
        return $this->platform_settings;
    }

} 
