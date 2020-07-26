<?php

declare(strict_types=1);

namespace Tmajne\Service\Twitter\Client;

/**
 * @internal
 */
final class HttpClient implements ClientInterface
{
    private CONST URL = 'https://api.twitter.com';
    private string $key;
    private string $secret;

    public function __construct(string $key, string $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    public function userTimeline(string $user, int $count = 10): array
    {
        $uri = '/1.1/statuses/user_timeline.json';
        $method = 'GET';
        $data = [
            'screen_name' => $user,
            'count' => $count
        ];

        $token = $this->authorizationToken();
        $headers = [
            "Authorization: {$token['token_type']} {$token['access_token']}"
        ];

        return $this->request($uri, $method, $data, $headers);
    }

    private function authorizationToken(): array
    {
        $uri = '/oauth2/token';
        $method = 'POST';
        $data = ['grant_type' => 'client_credentials'];
        $headers = ['Authorization: Basic ' . base64_encode($this->key . ':' . $this->secret)];

        return $this->request($uri, $method, $data, $headers);
    }

    private function request(string $uri, string $method, array $data = [], array $headers = []): ?array
    {
        $result = null;
        $ch = null;

        try {
            $ch = curl_init();
            $url = self::URL . $uri;

            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $options = [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $headers
            ];

            if ('GET' === $method) {
                $options[CURLOPT_URL] .= '?' . http_build_query($data, '', '&');
                $options[CURLOPT_CUSTOMREQUEST] = 'GET';
            } else if ('POST' === $method) {
                $options[CURLOPT_POST] = 1;
                $options[CURLOPT_POSTFIELDS] = http_build_query($data);
            }

            curl_setopt_array($ch, $options);
            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new \Exception('Server error: ' . curl_error($ch));
            }

            if (strpos(curl_getinfo($ch, CURLINFO_CONTENT_TYPE), 'application/json') !== false) {
                $result = json_decode($result, true, 128, JSON_BIGINT_AS_STRING);
                if ($result === false) {
                    throw new \Exception('Invalid server response');
                }
            }

        } catch (\Throwable $exception) {
            throw $exception;
        } finally {
            curl_close($ch);
        }

        return $result;
    }
}
