<?php

return [
    'settings' => [
        'displayErrorDetails' => ($_SERVER['SERVER_NAME'] == "localhost") ? true : false, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        // Monolog settings
        'logger' => [
            'name' => 'nullupload-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => Monolog\Logger::DEBUG,
        ],
        'twigSettings' => [
            'enableTwigDebug' => ($_SERVER['SERVER_NAME'] == "localhost") ? true : false,
            'twigTemplatesPath' => __DIR__ . '/../templates/',
            'twigCacheTemplatesPath' => __DIR__ . '/../_tcache/'
        ]
        ,
        'nullupload' => [
            'root' => __DIR__ . '/',
            'uploadPath' => '../uploads',
            'maxFileSize' => 101,
            'maxLimit' => 1000 * 1000 * 1000 * 8,
            'mimesForb' => [
                'application/x-msdownload',
                'application/x-ms-installer',
                'application/x-elf',
                'application/x-sh',
                'application/octet-stream',
                'application/x-ms-dos-executable',
                'application/x-msdos-program',
                'application/exe',
                'application/x-ole-storage',
                'text/mspg-legacyinfo',
                'application/com',
                'application/x-com',
                'application/hta',
                'application/x-java-applet', 'application/x-java-applet;version=1.1', 'application/x-java-bean', 'application/x-java-bean;version=1.1', 'application/x-java-vm/java-applet', 'application/x-java-vm/java-beans',
                'application/bat', 'application/x-bat', 'application/textedit',
                'application/x-vbs', 'text/vbs', 'text/vbscript',
                'text/scriptlet',
                'application/x-ms-shortcut',
                'application/x-setupscript',
                'application/x-httpd-php', 'text/php', 'text/x-php', 'application/php', 'magnus-internal/shellcgi', 'application/x-php', 'application/x-httpd-php5cgi', 'application/x-httpd-php',
                'application/x-httpd-eperl',
                'application/x-python',
                'application/vnd.android.package-archive',
                'application/x-executable'
            ],
            'admin' => [
                'directory' => '',
                'user' => '',
                'password' => ''
            ],
            'enableFileEncryption' => false
        ],
        'database' => [
            'server' => 'localhost',
            'database' => 'nullupload',
            'username' => 'nullupload',
            'password' => 'asdasdf'
        ],
    ],
];
