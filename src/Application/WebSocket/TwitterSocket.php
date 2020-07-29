<?php

declare(strict_types=1);

namespace Tmajne\Application\WebSocket;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\WebSocket\WsConnection;
use SplObjectStorage;
use Tmajne\Service\Twitter\Twitter;

class TwitterSocket implements MessageComponentInterface
{
    private const DEFAULT_USER = 'bbc';

    private SplObjectStorage $clients;
    private Twitter $twitter;

    public function __construct() {
        $this->clients = new SplObjectStorage;
        $this->twitter = new Twitter();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        echo "New connection! ({$conn->resourceId})\n";
        $tweets = $this->getTweets(self::DEFAULT_USER);
        $this->setClientData($conn, self::DEFAULT_USER, md5($tweets));
        $conn->send($tweets);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo "Connection {$from->resourceId} sending message: $msg\n";
        $tweets = $this->getTweets($msg);
        $this->setClientData($from, $msg, md5($tweets));
        $from->send($tweets);
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->send(json_encode(['error' => $e->getMessage()]));
    }

    public function onLoop($options = array())
    {
        echo "onLooop\n";

        $loopTweets = [];
        foreach($this->clients as $client) {
            /** @var WsConnection $client */
            ['user' => $user, 'lastTweetsChecksum' => $lastTweetsChecksum] = $this->clients[$client];

            if (empty($loopTweets[$user])) {
                $loopTweets[$user] = $this->getTweets($user);
            }
            $tweets = $loopTweets[$user];
            $tweetsChecksum = md5($tweets);

            if ($lastTweetsChecksum === $tweetsChecksum) {
                continue;
            }

            $this->setClientData($client, $user, $tweetsChecksum);
            $client->send($tweets);
        }
        unset($loopTweets);
    }

    private function setClientData(WsConnection $client, string $user, string $checksum): void
    {
        $this->clients[$client] = [
            'user' => $user,
            'lastTweetsChecksum' => $checksum
        ];
    }

    private function getTweets($user): string
    {
        //$count = rand(2, 10);
        $count = 10;
        $tweets = $this->twitter->userTimeline($user, $count);
        return json_encode($tweets);
    }
}
