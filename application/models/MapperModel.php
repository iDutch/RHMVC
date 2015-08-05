<?php

class MapperModel extends AbstractModel {

    private $mapper_settings;

    public function __construct($api_type)
    {
        $settings = require __DIR__ . '/../../config/mapper.global.php';
        $this->mapper_settings = $settings[$api_type];
    }

    public function mapRequest(array $data)
    {
        $rtn = array();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->mapper_settings['mapper']['request'])) {
                if ($this->mapper_settings['mapper']['request'][$key] instanceof \Closure) {
                    $rtn = array_merge($rtn, $this->mapper_settings['mapper']['request'][$key]($value));
                } else {
                    $rtn[$this->mapper_settings['mapper']['request'][$key]] = $value;
                }
            }
        }
        return $rtn;
    }

    public function mapResponse(array $data)
    {
        $count = 0;
        $rtn = array();
        if (array_key_exists('result', $data)){ //Islive
            $data = $data['result'];
        }
        foreach ($data as $row => $keys) {
            if(in_array($row, $this->mapper_settings['pre_filter'], true)){
                continue;
            }
            $rtn[$count] = array(); //Just to be safe :P
            foreach ($keys as $key => $value) {
                if (array_key_exists($key, $this->mapper_settings['mapper']['response'])) {
                    if ($this->mapper_settings['mapper']['response'][$key] instanceof \Closure) {
                        $rtn[$count] = array_merge($rtn[$row], $this->mapper_settings['mapper']['response'][$key]($value));
                    } else {
                        $rtn[$count][$this->mapper_settings['mapper']['response'][$key]] = $value;
                    }
                }
            }
            $count++;
        }
        foreach ($rtn as $row => $keys) {
            foreach ($keys as $key => $value) {
                //Remove profiles that have no city specified
                if($key === 'city' && empty($value)) {
                    unset($rtn[$row]);
                }
            }
        }
        return $rtn;
    }


    public static function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        return array($r, $g, $b);
    }

} 
