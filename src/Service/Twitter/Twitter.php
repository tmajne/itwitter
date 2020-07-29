<?php

declare(strict_types=1);

namespace Tmajne\Service\Twitter;

use BadMethodCallException;
use Tmajne\Service\Twitter\Client\ClientInterface;
use Tmajne\Service\Twitter\Factory\FactoryInterface;

final class Twitter
{
    private static ClientInterface $CLIENT;
    private static FactoryInterface $FACTORY;

    private ClientInterface $client;
    private FactoryInterface $factory;

    public static function init(ClientInterface $client, FactoryInterface $factory): void
    {
        self::$CLIENT = $client;
        self::$FACTORY = $factory;
    }

    public function __construct(string $key = null, string $secret = null)
    {
        if(!self::$FACTORY || !self::$CLIENT) {
            throw new BadMethodCallException('You must call Twitter:init before');
        }

        $this->factory = self::$FACTORY;
        $this->client = self::$CLIENT;
    }

    public function userTimeline(string $user, int $count): array
    {
        $data = $this->client->userTimeline($user, $count);
        return $this->factory->createFromTimeline($data);
    }
}
