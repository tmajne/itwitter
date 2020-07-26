<?php

use Tmajne\Service\Twitter\Twitter;

require __DIR__ . '/../vendor/autoload.php';

$key = 'jyEhs5zul8srRzpd7YaLU7mlR';
$secret = 'Wk8RLyOy5p7mvouY88FKk0oxqLoBwg5PNYyoTQi8gnBHUH4F4q';

$user = 'bbc';

$twitter = new Twitter($key, $secret);
$tweets = $twitter->userTimeline($user, 2);

print_r($tweets);