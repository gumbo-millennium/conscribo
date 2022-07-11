<?php

declare(strict_types=1);

return [
    /**
     * Account name, used for all calls to the API
     */
    'account' => env('CONSCRIBO_ACCOUNT'),

    /**
     * Authentication information, used when no session is present.
     */
    'auth' => [
        'username' => env('CONSCRIBO_USERNAME'),
        'passphrase' => env('CONSCRIBO_PASSPHRASE'),
    ],

    /**
     * Object definitions, used to request the correct fields from the API
     */
    'objects' => [
        'user' => [
            'resource' => 'persoon',
            'fields' => [
                'code',
            ],
        ],

        'group' => [
            'resource' => 'orgaan',
            'fields' => [
                'code',
                'naam',
            ],
        ],
    ],
];
