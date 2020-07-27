<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Tmajne\Application\WebSocket\TwitterSocket;
use Tmajne\Kernel;

(new Kernel())->init();

$twitterSocket = new TwitterSocket();
$server = IoServer::factory(
    new HttpServer(
        new WsServer($twitterSocket)
    ),
    $_ENV['WEBSOCKET_PORT']
);

$server->loop->addPeriodicTimer(
    $_ENV['TWITTER_REFRESH_INTERVAL'],
    function() use ($server, $twitterSocket) {
        $twitterSocket->onLoop(['server' => $server]);
    }
);

echo "Server is running ... \n";

$server->run();
