<?php

namespace System\Core\RHMVC;

use System\Libs\Messenger\Messenger;
use WebSocket\Client as WSClient;
use Exception;

abstract class AbstractController
{

    protected $layout = null;
    protected $_messenger = null;
    protected $_translator = null;

    /**
     * AbstractController constructor.
     * @param null $layout
     * @param Messenger $messenger
     * @param Translator $translator
     */
    public function __construct($layout = null, Messenger $messenger, Translator $translator)
    {
        $this->_messenger = $messenger;
        $this->_translator = $translator;
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

        $ServiceContainer = ServiceContainer::getInstance();
        $controller = new $controller($this->layout, $ServiceContainer->get('messenger'));

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

    /**
     * Connect to WS server and send a message
     * @param string $message
     * @param string $title
     * @param string $icon
     * @param string $type
     */
    protected function sendWebSocketMessage($message, $title = '', $icon = '', $type = 'info')
    {
        $ws_config = $this->getConfig('websocket');
        $client = new WSClient($ws_config['ws_url']);
        $message = (object) ['message' => $message, 'title' => $title, 'icon' => $icon, 'type' => $type];
        $client->send(json_encode($message));
    }

}
