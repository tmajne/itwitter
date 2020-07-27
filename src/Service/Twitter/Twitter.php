<?php

declare(strict_types=1);

namespace Tmajne\Service\Twitter;

use Tmajne\Service\Twitter\Client\HttpClient;
use Tmajne\Service\Twitter\Factory\TweetFactory;

final class Twitter
{
    private static string $KEY;
    private static string $SECRET;

    private string $key;
    private string $secret;

    public static function init(string $key, string $secret): void
    {
        self::$KEY = $key;
        self::$SECRET = $secret;
    }

    public function __construct(string $key = null, string $secret = null)
    {
        $this->key = $key ?? self::$KEY;
        $this->secret = $secret ?? self::$SECRET;
    }

    public function userTimeline(string $user, int $count): array
    {
        $factory = new TweetFactory();
        $http = new HttpClient($this->key, $this->secret);
        $data = $http->userTimeline($user, $count);
        return $factory->createFromTimeline($data);
    }
}
