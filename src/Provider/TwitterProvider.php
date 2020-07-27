<?php

declare(strict_types=1);

namespace Tmajne\Provider;

use Tmajne\Service\Twitter\Twitter;

class TwitterProvider
{
    public static function init(): void
    {
        Twitter::init($_ENV['TWITTER_KEY'], $_ENV['TWITTER_SECRET']);
    }
}
