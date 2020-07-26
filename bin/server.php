<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Tmajne\Application\Twitter;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Twitter()
        )
    ),
    8880
);

$server->run();
