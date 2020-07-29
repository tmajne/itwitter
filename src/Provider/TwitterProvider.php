<?php

declare(strict_types=1);

namespace Tmajne\Provider;

use Tmajne\Service\Twitter\Client\HttpClient;
use Tmajne\Service\Twitter\Factory\HttpTweetFactory;
use Tmajne\Service\Twitter\Twitter;

class TwitterProvider
{
    public static function init(): void
    {
        Twitter::init(
            new HttpClient($_ENV['TWITTER_KEY'], $_ENV['TWITTER_SECRET']),
            new HttpTweetFactory()
        );
    }
}
