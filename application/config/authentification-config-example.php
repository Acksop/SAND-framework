<?php
/**
 * Build a configuration array to pass to `Hybridauth\Hybridauth`
 *
 */
$config = [
//Location where to redirect users once they authenticate with a provider
    'callback' => \MVC\Url::link_rewrite(false, 'accueil', []),

//Providers specifics
    'providers' => [
        'GitHub' => [
            'enabled' => true,
            'keys' => ['id' => '', 'secret' => ''],
        ],

        'Google' => [
            'enabled' => true,
            'keys' => ['id' => '', 'secret' => ''],
        ],

        'Facebook' => [
            'enabled' => true,
            'keys' => ['id' => '', 'secret' => ''],
        ],

        'Twitter' => [
            'enabled' => true,
            'keys' => ['key' => '', 'secret' => ''],
        ]
    ],
    //optional : set debug mode
    'debug_mode' => true,
    // Path to file writeable by the web server. Required if 'debug_mode' is not false
    'debug_file' => LOG_PATH . DIRECTORY_SEPARATOR . 'hybridauth.log',

    /* optional : customize Curl settings
        // for more information on curl, refer to: http://www.php.net/manual/fr/function.curl-setopt.php
        'curl_options' => [
            // setting custom certificates
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CAINFO         => '/path/to/your/certificate.crt',

            // set a valid proxy ip address
            CURLOPT_PROXY => '*.*.*.*:*',

            // set a custom user agent
            CURLOPT_USERAGENT      => ''
        ] */
];
