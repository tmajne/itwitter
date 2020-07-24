<?php

declare(strict_types=1);

namespace Tmajne\Service\Twitter;

class Twitter
{
    private string $accessToken;
    private string $accessTokenSecret;

    public function __construct(string $accessToken, string $accessTokenSecret)
    {
        $this->accessToken = $accessToken;
        $this->accessTokenSecret = $accessTokenSecret;
    }


    public function say()
    {
        echo 'cokolwiek';
    }
}
