<?php

namespace System\Core\RHMVC;

use ActiveRecord\Model as ActiveRecordModel;
use WebSocket\Client;

abstract class AbstractModel extends ActiveRecordModel
{
    protected $_messages = null;

    /**
     * AbstractModel constructor.
     * @param array $attributes
     * @param bool $guard_attributes
     * @param bool $instantiating_via_find
     * @param bool $new_record
     */
    public function __construct(array $attributes=array(), $guard_attributes=true, $instantiating_via_find=false, $new_record=true)
    {
        $this->_messages = Messages::getInstance();
        parent::__construct($attributes, $guard_attributes, $instantiating_via_find, $new_record);
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
     * Send a websocket message
     * @param string $message
     * @param string $title
     * @param string $icon
     * @param string $type
     */
    protected function sendWebSocketMessage($message, $title = '', $icon = '', $type = 'info')
    {
        $client = new Client(WSS_URL);
        $message = (object) ['message' => $message, 'title' => $title, 'icon' => $icon, 'type' => $type];
        $client->send(json_encode($message));
    }
}
