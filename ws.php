<?php

use System\Libs\WSServer\WSServer as Chat;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;

require __DIR__ . '/vendor/autoload.php';

// Run the server application through the WebSocket protocol on port 8080
//$app = new Ratchet\App('192.168.1.15', 8080);
//$app->route('/chat', new Chat());
//$app->route('/echo', new Ratchet\Server\EchoServer, array('*'));
//$app->run();

$ws = new WsServer(new Chat);
$ws->disableVersion(0); // old, bad, protocol version
// Make sure you're running this as root
$server = IoServer::factory(new HttpServer($ws), 8080);
$server->run();
