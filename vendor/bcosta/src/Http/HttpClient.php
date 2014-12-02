<?php

namespace BCosta\Http;

/**
 * Class Sfa_Http_Http
 * @package BCosta\Http
 */
class HttpClient
{
    public static function request($url, array $config)
    {
        $default = [
            'method' => 'GET',
            'headers' => [],
            'verbose' => 0
        ];

        $config = array_merge($default, $config);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => $config['method'],
            CURLOPT_URL => $url,
            CURLOPT_POST => $config['post'] ? 1 : 0,
            CURLOPT_POSTFIELDS => $config['data'],
            CURLOPT_VERBOSE => $config['verbose'],
            CURLOPT_RETURNTRANSFER => 1,
        ]);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public static function post($url, array $config)
    {
        return self::request($url, array_merge([
            'method' => 'POST',
            'post' => count($config['data'])
        ], $config));
    }
}