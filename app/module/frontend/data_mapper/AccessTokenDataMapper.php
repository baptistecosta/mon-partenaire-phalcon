<?php

namespace MyTennisPal\Frontend\Model;

use BCosta\Http\HttpClient;

class AccessTokenDataMapper
{
    public function request($email, $password)
    {
        $response = HttpClient::post('http://my-tennis-pal.vagrant/api/auth', [
            'data' => [
                'grantType' => 'password',
                'clientId' => 'mytennispal-frontend',
                'clientSecret' => 's51Dz38e4cZ8',
                'email' => $email,
                'password' => $password
            ]
        ]);
        return json_decode($response);
    }
}