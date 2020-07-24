<?php

declare(strict_types=1);

namespace Tmajne\Service\Twitter;

class Http
{
    private array $options = [
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => 0
    ];

    // https://api.twitter.com/oauth2/token
    // client credentials

    // $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => "nMznkpFRTMCuNMsmALzel9FgPlmWQDWg"]);

    //https://api.twitter.com/oauth2/token

    public static function from_consumer_and_token(array $parameters = null): self
    {
        $parameters = $parameters ?: [];
        $defaults = [
            'oauth_version' => '2.0',
            'oauth_consumer_key' => 'jyEhs5zul8srRzpd7YaLU7mlR'
            'oauth_consumer_secret' => 'Wk8RLyOy5p7mvouY88FKk0oxqLoBwg5PNYyoTQi8gnBHUH4F4q'
        ];

        $parameters = array_merge($defaults, $parameters);

        return new self($http_method, $http_url, $parameters);
    }


    private function request(string $url, string $method, array $data = [])
    {
        $headers = ['Expect:'];
        if ($method === 'GET' && $data) {
            $url .= '?' . http_build_query($data, '', '&');
        }

        //$request = OAuth\Request::from_consumer_and_token($this->consumer, $this->token, $method, $resource);
        //$request->sign_request(new OAuth\SignatureMethod_HMAC_SHA1, $this->consumer, $this->token);
        //$headers[] = $request->to_header();

        $options = [
                CURLOPT_URL => $url,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $headers ?? [],
            ] + $this->options;

        if ($method === 'POST') {
            $options += [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_SAFE_UPLOAD => true,
            ];
        }

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new Exception('Server error: ' . curl_error($curl));
        }

        var_dump($result);
        exit;


        if (strpos(curl_getinfo($curl, CURLINFO_CONTENT_TYPE), 'application/json') !== false) {
            $payload = @json_decode($result, false, 128, JSON_BIGINT_AS_STRING); // intentionally @
            if ($payload === false) {
                throw new Exception('Invalid server response');
            }
        }

        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($code >= 400) {
            throw new Exception(isset($payload->errors[0]->message)
                ? $payload->errors[0]->message
                : "Server error #$code with answer $result",
                $code
            );
        } elseif ($code === 204) {
            $payload = true;
        }

        return $payload;
    }
}
