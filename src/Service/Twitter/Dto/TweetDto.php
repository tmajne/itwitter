<?php

declare(strict_types=1);

namespace Tmajne\Service\Twitter\Dto;

use DateTimeInterface;

/**
 * @internal
 */
final class TweetDto
{
    public string $id;
    public string $text;
    public DateTimeInterface $created;
    public int $retweets;
    public int $favorites;
    public UserDto $user;
}