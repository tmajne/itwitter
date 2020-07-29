<?php

declare(strict_types=1);

namespace Tmajne\Test\Unit\Service\Twitter\Dto;

use ReflectionClass;
use Tmajne\Service\Twitter\Dto\TweetDto;
use Tmajne\Test\Unit\TmajneTestCase;

class TweetDtoTest extends TmajneTestCase
{
    public function testUserDtoStructure(): void
    {
        $reflection = new ReflectionClass(TweetDto::class);
        $attributes = $reflection->getProperties();

        $this->assertClassHasAttribute('id', TweetDto::class);
        $this->assertClassHasAttribute('text', TweetDto::class);
        $this->assertClassHasAttribute('created', TweetDto::class);
        $this->assertClassHasAttribute('retweets', TweetDto::class);
        $this->assertClassHasAttribute('favorites', TweetDto::class);
        $this->assertClassHasAttribute('user', TweetDto::class);
        $this->assertCount(6, $attributes);
    }
}
