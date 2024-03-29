<?php
/*
 * Local configuration file to provide any overrides to your app.php configuration.
 * Copy and save this file as app_local.php and make changes as required.
 * Note: It is not recommended to commit files with credentials such as app_local.php
 * into source code version control.
 */
return [

    'Settings' => [
        'TradeTimeOut' => 300
    ],
    /*
     * Debug Level:
     *
     * Production Mode:
     * false: No error messages, errors, or warnings shown.
     *
     * Development Mode:
     * true: Errors and warnings shown.
     */
    'debug' => filter_var(env('DEBUG', true), FILTER_VALIDATE_BOOLEAN),

    /*
     * Security and encryption configuration
     *
     * - salt - A random string used in security hashing methods.
     *   The salt value is also used as the encryption key.
     *   You should treat it as extremely sensitive data.
     */
    'Security' => [
        'salt' => env('SECURITY_SALT', '8552b2a5eaf12ed1500d8bce2607485dd40fb5f6760fc2e6e3fd2e6d04bb130f'),
    ],

    /*
     * Connection information used by the ORM to connect
     * to your application's datastores.
     *
     * See app.php for more configuration options.
     */
    'Api' => [
        'token' => 'af12ed1500d8bce2607485dd40fb5f6760fc2e6e3fd2e6'
    ],
    'Datasources' => [
        'default' => [
            'host' => 'localhost',
            'username' => 'unihub',
            'password' => 'REawHmVwdbFkVajE',
            'database' => 'unihub',
            'url' => env('DATABASE_URL', null),
        ],

        'debug_kit' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Sqlite',
            'database' => TMP . 'debug_kit.sqlite',
            'encoding' => 'utf8',
            'cacheMetadata' => true,
        ],

        /*
         * The test connection is used during the test suite.
         */
        'test' => [
            'host' => 'localhost',
            //'port' => 'non_standard_port_number',
            'username' => 'my_app',
            'password' => 'secret',
            'database' => 'test_myapp',
            //'schema' => 'myapp',
            'url' => env('DATABASE_TEST_URL', 'sqlite://127.0.0.1/tests.sqlite'),
        ],
    ],

    /*
     * Email configuration.
     *
     * Host and credential configuration in case you are using SmtpTransport
     *
     * See app.php for more configuration options.
     */
    'EmailTransport' => [
        'default' => [
            'host' => 'localhost',
            'port' => 25,
            'username' => null,
            'password' => null,
            'client' => null,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
    ],

    'OAuth' => [
        'clientId' => '270577235544-rtermudlcug7desccd5upopeikksc338.apps.googleusercontent.com',
        'clientSecret' => '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',
        'redirectUri' => 'https://unihub.fi/callback',
        // other configurations specific to the provider
    ],

    'SocialAuth' => [
        'providers' => [
            'google' => [
                'className' => 'League\OAuth2\Client\Provider\Google',
                'options' => [
                    'clientId' => '270577235544-rtermudlcug7desccd5upopeikksc338.apps.googleusercontent.com',
                    'clientSecret' => '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',
                    'redirectUri' => 'https://unihub.com/social-auth/callback/google'
                ],
            ],
        ],
    ],
    'JWT' => [
        'SecretKey' => 'kjashgfjkhasgfasjtghakrasf383jhbkjbgfaksj'
    ]
];
