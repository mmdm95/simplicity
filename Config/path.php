<?php

/**
 * Key options are used in many classes, so DO NOT change them (keys),
 * but you can add config to this
 *
 * NOTE:
 *   1. You can have your own structure with change path of folders from here
 */
return [
    // App path(s)
    'app' => BASE_ROOT . 'app/',
    // Logic paths
    'logic' => BASE_ROOT . 'app/logic/',
    'abstract' => BASE_ROOT . 'app/logic/abstract/',
    'adapter' => BASE_ROOT . 'app/logic/adapter/',
    'command' => BASE_ROOT . 'app/logic/command/',
    'controller' => BASE_ROOT . 'app/logic/controller/',
    'filter' => BASE_ROOT . 'app/logic/filter/',
    'handler' => BASE_ROOT . 'app/logic/handler/',
    'helper' => BASE_ROOT . 'app/logic/helper/',
    'interface' => BASE_ROOT . 'app/logic/interface/',
    'middleware' => BASE_ROOT . 'app/logic/middleware/',
    'model' => BASE_ROOT . 'app/logic/model/',
    'util' => BASE_ROOT . 'app/logic/util/',
    'validation' => BASE_ROOT . 'app/logic/validation/',
    // Design paths
    'design' => BASE_ROOT . 'app/design/',
    'view' => BASE_ROOT . 'app/design/view/',
    'layout' => BASE_ROOT . 'app/design/layout/',
    'partial' => BASE_ROOT . 'app/design/partial/',
    'error' => BASE_ROOT . 'app/design/error/',
    'design_config' => BASE_ROOT . 'app/design/config/',

    // Data path(s)
    'cache' => BASE_ROOT . 'data/cache/',
    'draft' => BASE_ROOT . 'data/draft/',
    'log' => BASE_ROOT . 'data/log/',

    // Translation path(s)
    'i18n' => BASE_ROOT . 'i18n/',

    // Core path(s)
    'core' => BASE_ROOT . 'core/',

    // Vendor path(s)
    'vendor' => BASE_ROOT . 'vendor/',

    // Config path(s)
    'config' => BASE_ROOT . 'config/',

    // Public path(s)
    'public' => BASE_ROOT . 'public/',

    // Resource path(s)
    'resource' => BASE_ROOT . 'resource/',

    // Manifest path
    'manifest' => BASE_ROOT . 'public/build/manifest.json',

    // Config file(s) path
    'default_config' => [
        'main' => __DIR__ . '/config.php',
        'router' => __DIR__ . '/router.php',
        'captcha' => __DIR__ . '/captcha.php',
        'i18n' => __DIR__ . '/i18n.php',
        'auth' => __DIR__ . '/auth.php',
        'database' => __DIR__ . '/database.php',
        'log' => __DIR__ . '/log.php',
        'mail' => __DIR__ . '/mail.php',
        'payment' => __DIR__ . '/payment.php',
        'security' => __DIR__ . '/security.php',
        // webpack config
        // DO NOT change it at all
        'webpack' => __DIR__ . '/webpack.php',
        // Designer asset paths
        'includes' => BASE_ROOT . 'app/design/config/includes.php',
    ]
];
