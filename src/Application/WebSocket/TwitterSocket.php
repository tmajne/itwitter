<?php

declare(strict_types=1);

namespace Tmajne\Application\WebSocket;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;
use Tmajne\Service\Twitter\Twitter;

class TwitterSocket implements MessageComponentInterface
{
    protected SplObjectStorage $clients;
    private ?string $lastTweetsChecksum = null;

    public function __construct() {
        $this->clients = new SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";

        $conn->send(
            $this->getTweets()
        );
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $receivers = count($this->clients) - 1;
        echo "Connection {$from->resourceId} sending message to $receivers other connection\n";

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function onLoop($options = array())
    {
        $tweets = $this->getTweets();
        $tweetsChecksum = md5($tweets);

        if ($tweetsChecksum !== $this->lastTweetsChecksum) {
            foreach($this->clients as $client) {
                $client->send($tweets);
            }
            $this->lastTweetsChecksum = md5($tweets);
        } else {
            echo "Nic się nie zmieniło\n";
        }
    }

    private function getTweets(): string
    {
        $user = 'bbc';
        $tweets = (new Twitter())->userTimeline($user, 10);
        return json_encode($tweets);
    }
}
