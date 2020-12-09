<?php
/**
 * Build a configuration array to pass to `Hybridauth\Hybridauth`
 *
 */
$config = [
//Location where to redirect users once they authenticate with a provider
    //Ne fonctionne pas car on est sur un serveur a l'intérieur d'un réseau personnel
    //'callback' => $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/" . \MVC\Classe\Url::link_rewrite(false, 'compte', []),
    'callback' => PATH_URL . \MVC\Classe\Url::link_rewrite(false, 'compte', []),


//Providers specifics
    'providers' => [
        'GitHub' => [
            'enabled' => true,
            'keys' => ['id' => '4cc55bcafbf8ea77ae14', 'secret' => ''],
        ],

        'Google' => [
            'enabled' => true,
            'keys' => ['id' => '686670374445-mhktaj9gp08p6oiu8e1aue3ckua6e3s3.apps.googleusercontent.com', 'secret' => ''],
        ],

        'Facebook' => [
            'enabled' => true,
            'keys' => ['id' => '432266300978748', 'secret' => ''],
        ],

        'Twitter' => [
            'enabled' => true,
            'keys' => ['key' => 'gnsmsVW0EkdpSVeqwCOG7V1H5', 'secret' => ''],
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