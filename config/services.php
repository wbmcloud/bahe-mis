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
     * 研发GMT服务器：111.230.140.74:50003
     * 外网GMT服务器：123.206.24.228:60031
     */
    'gmt' => [
        'schema' => 'tcp',
        'host' => env('GMT_HOST', '123.206.24.228'),
        'port' => env('GMT_PORT', 60031),
    ],
    /**
     * 游戏服务器IP地址列表
     */
    'game_server' => [
        'outer' => [
            [
                'host' => '123.206.24.228',
                'user' => 'root',
                'password' => '!QAZ8ik,9ol.',
                'path' => '/root/workdir/logs/',
            ]
        ],
        'inner' => [
            [
                'host' => '111.230.140.74',
                'user' => 'game',
                'password' => '!QAZ8ik,9ol.',
                'path' => '/export/game/workdir/logs/',
            ]
        ],
    ],

];
