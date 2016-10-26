<?php

namespace core\RHMVC;

use Plasticbrain\FlashMessages\FlashMessages;

abstract class AbstractController
{

    protected $layout = null;

    public function __construct($layout = null)
    {
        $this->layout = $layout; //Pass layout to controllers so they can alter the main layout when needed
    }

    /**
     * Call any conroller action with an array of params
     * @param string $controller
     * @param string $action
     * @param array $params
     * @return string
     * @throws Exception
     */
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

    /**
     * Load a model
     * @param string $model
     * @return \core\RHMVC\model
     * @throws Exception
     */
    protected function loadModel($model)
    {
        $model_file = __DIR__ . '/../../../application/models/' . $model . '.php';

        if (!file_exists($model_file)) {
            throw new Exception('HMVC error: Cannot load model: \'' . $model_file . '\'');
        }
        require_once $model_file;

        return new $model();
    }

    /**
     * Grab config file
     * @param string $name
     * @return array
     * @throws Exception
     */
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
        throw new Exception('HMVC error: Cannot load config: \'' . CONFIG_DIR . $name . '.global.php\'');
    }

    /**
     * Function wrapper for FlashMessages object
     * @return FlashMessages
     */
    protected function flashMessage()
    {
        return new FlashMessages();
    }

}
