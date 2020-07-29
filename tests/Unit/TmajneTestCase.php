<?php

declare(strict_types=1);

namespace Tmajne\Test\Unit;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class TmajneTestCase extends TestCase
{
    protected function faker(): Generator
    {
        return Factory::create();
    }
}
