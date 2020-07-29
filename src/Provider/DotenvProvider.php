<?php

declare(strict_types=1);

namespace Tmajne\Provider;

use Dotenv\Dotenv;

class DotenvProvider
{
    public static function init(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
    }
}
