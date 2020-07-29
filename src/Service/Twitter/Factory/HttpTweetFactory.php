<?php

declare(strict_types=1);

namespace Tmajne\Service\Twitter\Factory;

use DateTimeImmutable;
use InvalidArgumentException;
use Tmajne\Service\Twitter\Dto\TweetDto;
use Tmajne\Service\Twitter\Dto\UserDto;

/**
 * @internal
 */
final class HttpTweetFactory implements FactoryInterface
{
    public function createFromTimeline(array $data): array
    {
        $result = [];
        foreach ($data as $row) {
            $this->validateTimelineData($row);

            $tweet = new TweetDto();
            $tweet->id = (string) $row['id_str'];
            $tweet->text = $row['text'];
            $tweet->created = new DateTimeImmutable($row['created_at']);
            $tweet->retweets = $row['retweet_count'];
            $tweet->favorites = $row['favorite_count'];

            $userData = $row['user'];
            $user = new UserDto();
            $user->id = (string) $userData['id'];
            $user->name = $userData['name'];
            $user->screen = $userData['screen_name'];
            $tweet->user = $user;

            $result[] = $tweet;
        }

        return $result;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validateTimelineData(array $data): void
    {
        $requiredKeys = [
            'id_str', 'text', 'created_at', 'retweet_count', 'favorite_count', 'user'
        ];
        $requiredUserKeys = [
            'id', 'name', 'screen_name'
        ];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new InvalidArgumentException("Key: $key does not exist in data");
            }
        }

        foreach ($requiredUserKeys as $key) {
            if (!array_key_exists($key, $data['user'])) {
                throw new InvalidArgumentException("Key: $key does not exist in user data");
            }
        }
    }
}
