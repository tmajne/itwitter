<?php

declare(strict_types=1);

namespace Tmajne\Service\Twitter\Factory;

use Tmajne\Service\Twitter\Dto\TweetDto;

interface FactoryInterface
{
    /**
     * @param array $data
     * @return TweetDto[]
     */
    public function createFromTimeline(array $data): array;
}
