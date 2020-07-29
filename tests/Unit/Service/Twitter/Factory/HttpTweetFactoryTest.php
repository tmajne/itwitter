<?php

declare(strict_types=1);

namespace Tmajne\Test\Unit\Service\Twitter\Factory;

use InvalidArgumentException;
use Tmajne\Service\Twitter\Dto\TweetDto;
use Tmajne\Service\Twitter\Factory\HttpTweetFactory;
use Tmajne\Test\Unit\TmajneTestCase;

class HttpTweetFactoryTest extends TmajneTestCase
{
    /**
     * @dataProvider tweetsProvider
     * @param array $data
     */
    public function testCanBeCreatedByValidData(array $data): void
    {
        $factory = new HttpTweetFactory();
        $dtos = $factory->createFromTimeline($data);

        $count = count($data);
        $this->assertCount($count, $dtos);
        for ($i = 0; $i < $count; $i++) {
            /** @var TweetDto $dto */
            $dto = $dtos[$i];
            $expected = $data[$i];

            $this->assertSame($expected['id_str'], $dto->id);
            $this->assertSame($expected['text'], $dto->text);
            $this->assertSame($expected['created_at'], $dto->created->format('Y-m-d H:i:s'));
            $this->assertSame($expected['retweet_count'], $dto->retweets);
            $this->assertSame($expected['favorite_count'], $dto->favorites);
            $this->assertSame($expected['user']['id'], $dto->user->id);
            $this->assertSame($expected['user']['name'], $dto->user->name);
            $this->assertSame($expected['user']['screen_name'], $dto->user->screen);
        }
    }

    public function testCanBeCreatedFromInvalidData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key: text does not exist in data');

        $faker = $this->faker();
        $row = [
            'id_str' => (string) $faker->numberBetween(1, 1000000),
            'created_at' => $faker->date('Y-m-d H:i:s'),
            'retweet_count' => $faker->numberBetween(0, 200),
            'favorite_count' => $faker->numberBetween(0, 200),
            'user' => [
                'id' => (string) $faker->numberBetween(1, 1000000),
                'name' => $faker->firstName,
                'screen_name' => $faker->word
            ]
        ];

        $factory = new HttpTweetFactory();
        $factory->createFromTimeline([$row]);
    }

    public function testCanBeCreatedFromInvalidUserData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key: name does not exist in user data');

        $faker = $this->faker();
        $row = [
            'id_str' => (string) $faker->numberBetween(1, 1000000),
            'text' => $faker->text,
            'created_at' => $faker->date('Y-m-d H:i:s'),
            'retweet_count' => $faker->numberBetween(0, 200),
            'favorite_count' => $faker->numberBetween(0, 200),
            'user' => [
                'id' => (string) $faker->numberBetween(1, 1000000),
                'screen_name' => $faker->word
            ]
        ];

        $factory = new HttpTweetFactory();
        $factory->createFromTimeline([$row]);
    }

    public function tweetsProvider(): array
    {
        $faker = $this->faker();
        $count = $faker->numberBetween(5, 10);

        $data = [];
        for ($i = 0; $i < $count; $i++) {
            $row = [
                'id_str' => (string) $faker->numberBetween(1, 1000000),
                'text' => $faker->text,
                'created_at' => $faker->date('Y-m-d H:i:s'),
                'retweet_count' => $faker->numberBetween(0, 200),
                'favorite_count' => $faker->numberBetween(0, 200),
                'user' => [
                    'id' => (string) $faker->numberBetween(1, 1000000),
                    'name' => $faker->firstName,
                    'screen_name' => $faker->word
                ]
            ];
            $data[] = $row;
        }

        return [
            [$data]
        ];
    }
}
