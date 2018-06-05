<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    /**
     * 游戏服务器IP地址列表
     */
    'game_servers' => [
        [
            'game_type' => 1,
            'city_id' => 2340,
            'game_server_id' => 1,
            'host' => '123.206.24.228',
            'path' => '/export/game/workdir/logs/',
        ],
        [
            'city_id' => 2280,
            'game_type' => 1,
            'game_server_id' => 3,
            'host' => '140.143.56.247',
            'path' => '/export/game/workdir/logs/',
        ],
        [
            'game_type' => 2,
            'game_server_id' => 4,
            'host' => '139.199.115.26',
            'path' => '/export/game/workdir/logs/',
        ]
    ],
];
