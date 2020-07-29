<?php

declare(strict_types=1);

namespace Tmajne\Test\Unit\Service\Twitter\Dto;

use ReflectionClass;
use Tmajne\Service\Twitter\Dto\UserDto;
use Tmajne\Test\Unit\TmajneTestCase;

class UserDtoTest extends TmajneTestCase
{
    public function testUserDtoStructure(): void
    {
        $reflection = new ReflectionClass(UserDto::class);
        $attributes = $reflection->getProperties();

        $this->assertClassHasAttribute('id', UserDto::class);
        $this->assertClassHasAttribute('name', UserDto::class);
        $this->assertClassHasAttribute('screen', UserDto::class);
        $this->assertCount(3, $attributes);
    }
}
