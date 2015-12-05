<?php

abstract class AbstractModel
{

    protected $model_config = array();

    public function __construct()
    {

    }

    protected function loadModelConfig($classname)
    {
        $classname = strtolower($classname);
        $config_global_file = __DIR__ . '/../../config/' .$classname. '.global.php';
        $config_local_file = __DIR__ . '/../../config/' .$classname. '.local.php';

        $global = array();
        $local = array();

        if (file_exists($config_global_file)) {
            $global = require $config_global_file;
            if (file_exists($config_local_file)) {
                $local = require $config_local_file;
            }
        }

        $this->model_config = array_merge($global, $local);
    }

}
