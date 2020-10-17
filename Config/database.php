<?php

return [
    // Define database connections.
    //
    // Allowed parameters in array: [mysql, sqlite, pgsql, sqlsrv]
    //
    // This parameters are NOT --case sensitive--
    //
    // Support multiple database.
    // For multiple database, append followed codes after default array.
    //    'read' => [
    //        'slave1' => [
    //            'type' => 'mysql',
    //            'dsn' => 'mysql:host=localhost;dbname=database;charset=utf8',
    //            'username' => 'root',
    //            'password' => '',
    //            'options' => [
    //
    //            ]
    //        ],
    //        /*
    //         * Your other databases...
    //         */
    //    ],
    //    'write' => [
    //        'master' => [
    //            'type' => 'mysql',
    //            'dsn' => 'mysql:host=localhost;dbname=database;charset=utf8',
    //            'username' => 'root',
    //            'password' => '',
    //            'options' => [
    //
    //            ]
    //        ],
    //        /*
    //         * Your other databases...
    //         */
    //    ]
    'databases' => [
        'default' => [
            'type' => 'mysql',
            'dsn' => 'mysql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] .
                ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8',
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'options' => []
        ]
    ]
];