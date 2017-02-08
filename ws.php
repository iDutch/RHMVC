<?php

use System\Libs\WSServer\WSServer as Chat;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;

require __DIR__ . '/vendor/autoload.php';

$global_ws_config_file = __DIR__ . '/Config/websocket.global.php';
$local_ws_config_file = __DIR__ . '/Config/websocket.local.php';

if (file_exists($global_ws_config_file)){
    $global_ws_config = require $global_ws_config_file;
    $local_ws_config = [];
    if (file_exists($local_ws_config_file)) {
        $local_ws_config = require $global_ws_config_file;
    }
    $ws_config = array_merge($global_ws_config, $local_ws_config);
} else {
    throw new Exception('WebSocker server error: Could not load config file: \''.$global_ws_config_file.'\'');
}

$ws = new WsServer(new Chat);
$ws->disableVersion(0); // old, bad, protocol version
// Make sure you're running this with the right permissions
$server = IoServer::factory(new HttpServer($ws), $ws_config['ws_port']);
$server->run();
