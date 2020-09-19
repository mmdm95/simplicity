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
    //    'read' => array(
    //        'slave1' => array(
    //            'type' => 'mysql',
    //            'dsn' => 'mysql:host=localhost;dbname=database;charset=utf8',
    //            'username' => 'root',
    //            'password' => '',
    //            'options' => [
    //
    //            ]
    //        ) // ,
    //        /*
    //         * Your other databases...
    //         */
    //    ),
    //    'write' => array(
    //        'master' => array(
    //            'type' => 'mysql',
    //            'dsn' => 'mysql:host=localhost;dbname=database;charset=utf8',
    //            'username' => 'root',
    //            'password' => '',
    //            'options' => [
    //
    //            ]
    //        ) // ,
    //        /*
    //         * Your other databases...
    //         */
    //    )
    'databases' => array(
        'default' => array(
            'type' => 'mysql',
            'dsn' => 'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') .
                ';dbname=' . getenv('DB_NAME') . ';charset=utf8',
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD') ?? '',
            'options' => []
        )
    )
];