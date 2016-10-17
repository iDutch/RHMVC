<?php

namespace RHMVC;

abstract class HMVC
{

    protected $layout = null;

    protected function invokeController($controller, $action, array $params = array())
    {
        $controller_file = __DIR__ . '/../../../application/controllers/' . $controller . '.php';

        if (!file_exists($controller_file)) {
            throw new Exception('HMVC error: Cannot invoke controller: \'' . $controller_file . '\'');
        }

        require_once $controller_file;
        $controller = new $controller($this->layout);

        if (!method_exists($controller, $action)){
            if (!isset($_SERVER['IS_DEVEL'])) {
                
            }
            throw new Exception('HMVC error: ' . $controller . ' :: ' . $action . ' does not exist!');
        }

        return call_user_func_array(array($controller, $action), $params);
    }

    protected function loadModel($model)
    {
        $model_file = __DIR__ . '/../../../application/models/' . $model . '.php';

        if (!file_exists($model_file)) {
            throw new Exception('HMVC error: Cannot load model: \'' . $model_file . '\'');
        }
        require_once $model_file;

        return new $model();
    }
    
    protected function getConfig($name)
    {
        $global_config_file = CONFIG_DIR . $name . '.global.php';
        $local_config_file  = CONFIG_DIR . $name . '.local.php';

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

    protected function flashMessage()
    {
        return new \Plasticbrain\FlashMessages\FlashMessages();
    }

}
