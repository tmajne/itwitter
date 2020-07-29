<?php

use Tmajne\Kernel;
use Tmajne\Service\Twitter\Twitter;

require __DIR__ . '/../vendor/autoload.php';

(new Kernel())->init();

$user = 'hell';

$tweets = (new Twitter())->userTimeline($user, 2);

print_r($tweets);
