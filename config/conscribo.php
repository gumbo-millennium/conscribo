<?php

declare(strict_types=1);

return [
    'auth' => [
        'account' => env('CONSCRIBO_ACCOUNT'),
        'username' => env('CONSCRIBO_USERNAME'),
        'passphrase' => env('CONSCRIBO_PASSPHRASE'),
    ],

    'objects' => [
        'user' => [
            'model' => App\User::class,
            'resource' => 'persoon',
            'fields' => [
                'id' => 'conscribo_id',
                // further fields to auto-apply to the user model
            ],
        ],

        'group' => [
            'model' => App\Group::class,
            'resource' => 'orgaan',
            'fields' => [
                'id' => 'conscribo_id',
                'naam' => 'name',
                // further fields to auto-apply to the group model
            ],
        ],
    ],
];
