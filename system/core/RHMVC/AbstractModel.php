<?php

abstract class AbstractModel extends HMVC
{

    protected $model_config = array();

    public function __construct()
    {
        $this->loadModelConfig(get_called_class());
    }

    protected function loadModelConfig($classname)
    {
        $classname = strtolower($classname);
        $config_global_file = __DIR__ . '/../../../config/models/' .$classname. '.global.php';
        $config_local_file = __DIR__ . '/../../../config/models/' .$classname. '.local.php';

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
