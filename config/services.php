<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

        // Configure Google Analytics 4 (GA4) measurement_id
    'ga4' => [
        'measurementId' => env('GA_MEASUREMENT_ID'),
    ],

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        'min_score' => env('RECAPTCHA_MIN_SCORE', .5),
        // 'min_score' => env('RECAPTCHA_MIN_SCORE', 5),
    ],

    'kbank' => [
    'secret_key' => env('KBANK_SECRET_KEY'),
    'public_key' => env('KBANK_PUBLIC_KEY'),
    'mid'        => env('KBANK_MID'),
    'card_url'   => env('KBANK_CARD_URL'),
    'qr_url'     => env('KBANK_QR_URL'),
    'alipay_url' => env('KBANK_ALIPAY_URL'),
    'prod_url' => env('KBANK_PROD_URL'),
],
];
