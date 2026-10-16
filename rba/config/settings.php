
<?php

return [
    'app' => [
        'APP_DOMAIN' => 'rba.farwell-consultants.com',
        'VERIFY_SSL' => true,
        'PASSMARK' => 0.6,
    ],

    'lms' => [
        'status' => 'test',
        'live' => [
            'EDX_KEY' => 'login-service-client-id',
            'EDX_SECRET' => 'ufKJTwuYri2Fa8eW8jZ0OfA9mOfGvAkwvaCrV9M57fo02FLYbXUvdNOFdqdomi2Izy0Aq0pCJWgxolDmj1O3uWPCKbiAD75hHbZmtapGrZkVpr2nTVFPYkL5XQjbsLg9',
            'EDX_CONNECT' => true,
            'COOKIE_DOMAIN' => 'learning.rba.farwell-consultants.com',
            'LMS_DOMAIN' => 'https://learning.rba.farwell-consultants.com',
            'LMS_BASE' => 'https://learning.rba.farwell-consultants.com',
            'CMS_BASE' => 'https://studio.learning.rba.farwell-consultants.com',
            'LMS_REGISTRATION_URL' => 'https://learning.rba.farwell-consultants.com/user_api/v1/account/registration/',
            'LMS_LOGIN_URL' => 'https://learning.rba.farwell-consultants.com/user_api/v1/account/login_session/',
            'LMS_RESET_PASSWORD_PAGE' => 'https://learning.rba.farwell-consultants.com/user_api/v1/account/password_reset/',
            'LMS_RESET_PASSWORD_API_URL' => 'https://learning.rba.farwell-consultants.com/user_api/v1/account/password_reset/',
            'edxWebServiceApiToken' => 'c90685ce8bc75a8e03ad35deb28a5fade4a4cc87',
            'default_token' => '133ad7f0ecd269b63637a522dbb529c50475a493'
        ],

    ],

    'zoom' =>[
        'ZOOM_API_URL'=>"https://api.zoom.us/v2/",
        'ZOOM_API_KEY'=>"gZ3yJ6bhRauRrmZTSyk9Gg",
        'ZOOM_API_SECRET'=>"41Z9cZusqrZbeG7UUwgfIZ60UgXaBTgY",
        'ZOOM_ACCOUNT_ID'=> "Ju4g39PgRSmvyF_wvYsLbg"

     ],
     
    'sendgrid' => [
        'asm_group_id' => env('SENDGRID_ASM_GROUP_ID', 33351),
        'mail_driver' => env('MAIL_MAILER', 'smtp'),
        'mail_host' => env('MAIL_HOST', 'smtp.sendgrid.net'),
        'mail_port' => env('MAIL_PORT', 587),
        'mail_encryption' =>env('MAIL_ENCRYPTION', 'tls'),
        'mail_username' => env('MAIL_USERNAME','apikey'),
        'mail_password' => getenv('SENDGRID_API_KEY'),
        'mail_from_address' => env('MAIL_FROM_ADDRESS', 'support@farwell-consultants.com'),
        'mail_from_name' => env('MAIL_FROM_NAME', "Rwanda Bankers' Association"),
    ],
];
