<?php

use System\Libs\WSServer\WSServer as Chat;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;

require __DIR__ . '/vendor/autoload.php';

$ws = new WsServer(new Chat);
$ws->disableVersion(0); // old, bad, protocol version
// Make sure you're running this as root
$server = IoServer::factory(new HttpServer($ws), 8080);
$server->run();
