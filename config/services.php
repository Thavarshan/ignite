<?php

use App\Clients\KRI\Event;
use App\Clients\KRI\Vehicle;

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
        'region' => env('AWS_DEFAULT_REGION', 'eu-west-2'),
    ],

    'slack' => [
        'duplicates' => env('SLACK_DUPLICATES_WEBHOOK'),
        'vrn_matches' => env('SLACK_VRN_MATCHES_WEBHOOK'),
        'notifications' => env('SLACK_NOTIFICATIONS_WEBHOOK'),
    ],

    'google' => [
        'maps' => [
            'key' => env('GOOGLE_MAPS_API_KEY'),
        ],
    ],

    'osrm' => [
        'url' => env('OSRM_API_URL', 'https://router.project-osrm.org'),
        'service' => 'route',
        'version' => 'v1',
        'topic' => 'validation',
        'routingProfile' => 'card',
    ],

    'fetchify' => [
        'url' => env('FETCHIFY_URL', 'https://api.craftyclicks.co.uk/address/1.1'),
        'keys' => [
            'find' => env('FETCHIFY_KEY_FIND'),
            'geocode' => env('FETCHIFY_KEY_GEOCODE'),
        ],
        'urls' => [
            'find' => env('FETCHIFY_URL_FIND', '/find'),
            'retrieve' => env('FETCHIFY_URL_RETRIEVE', '/retrieve'),
        ],
    ],

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from' => env('TWILIO_FROM'),
    ],

    'kri' => [
        'url' => env('KRI_API_URL', 'https://test.kri.ignite.io/api'),
        'key' => env('KRI_API_KEY'),
        'resources' => [
            'events' => Event::class,
            'vehicles' => Vehicle::class
        ],
    ],
];
