<?php

declare(strict_types=1);

namespace Tmajne\Service\Twitter;

use Tmajne\Service\Twitter\Client\HttpClient;
use Tmajne\Service\Twitter\Factory\TweetFactory;

final class Twitter
{
    private string $key;
    private string $secret;

    public function __construct(string $key, string $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    public function userTimeline(string $user, int $count): array
    {
        $factory = new TweetFactory();
        $http = new HttpClient($this->key, $this->secret);
        $data = $http->userTimeline($user, $count);
        return $factory->createFromTimeline($data);
    }
}
