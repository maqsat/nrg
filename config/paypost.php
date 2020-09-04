<?php
/**
 * Created by PhpStorm.
 * User: dosarkz
 * Date: 2018-12-13
 * Time: 12:36
 */

return [
    'test_mode' =>  env('PAY_POST_TEST_MODE', true),
    'stages' => [
        'test' => [
            'url' => 'https://testpay.post.kz',
            'token' => '43aa10b199c03d5342907e7d6f3f9217e88a9824',
            'key' => env('PAY_POST_TEST_KEY', 'DEMO'),
        ],
        'prod' => [
            'url' => 'https://pay.post.kz',
            'token' => 'abe5c50959ad1d64c3d6bab61adfb8dae95c73f2',
            'key' => env('PAY_POST_PROD_KEY'),
        ]
    ],
    'back_link' => 'http://nrg-max.kz/home',
    'urls' => [
        'generateUrl' => '/api/v0/orders/payment/',
    ]
];
