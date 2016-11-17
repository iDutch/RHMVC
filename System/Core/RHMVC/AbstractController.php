<?php

namespace System\Core\RHMVC;

use Plasticbrain\FlashMessages\FlashMessages;
use Exception;

abstract class AbstractController
{

    protected $layout = null;
    protected $flashmessages = null;

    /**
     * Constructor
     * @param type $layout
     */
    public function __construct($layout = null)
    {
        $this->flashmessages = new FlashMessages();
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
        $controller = 'Application\\Controllers\\' . $controller;
        if (!class_exists($controller)) {
            throw new Exception('AbstractController error: Controller \'' . $controller . '\' not found!');
        }

        $controller = new $controller($this->layout);

        if (!method_exists($controller, $action)) {
            throw new Exception('AbstractController error: ' . $controller . ' :: ' . $action . ' does not exist!');
        }

        return call_user_func_array(array($controller, $action), $params);
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
        $local_config_file = CONFIG_DIR . $name . '.local.php';

        if (file_exists($global_config_file)) {
            $global = require $global_config_file;
            if (file_exists($local_config_file)) {
                $local = require $local_config_file;
                return array_merge($global, $local);
            }
            return $global;
        }
        throw new Exception('AbstractController error: Cannot load config: \'' . CONFIG_DIR . $name . '.global.php\'');
    }

    /**
     * Redirector
     * @param type $url
     */
    protected function redirect($url)
    {
        header('Location: ' . $url);
    }

}
