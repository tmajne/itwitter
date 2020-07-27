<?php

declare(strict_types=1);

namespace Tmajne;

use Tmajne\Provider\DotenvProvider;
use Tmajne\Provider\TwitterProvider;

class Kernel
{
    public function init(): void
    {
        DotenvProvider::init();
        TwitterProvider::init();
    }
}
