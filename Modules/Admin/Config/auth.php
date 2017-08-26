<?php

return [

    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => \Modules\Admin\Entities\Admin::class,
        ],

    ],

    'passwords' => [

        'admins' => [
            'provider' => 'admins',
            'email'    => 'admin.auth.emails.password',
            'table'    => 'password_resets',
            'expire'   => 60,
        ],
    ],

];
