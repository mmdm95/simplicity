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
    'app' => BASE_ROOT . 'App/',
    // Logic paths
    'logic' => BASE_ROOT . 'App/Logic/',
    'abstract' => BASE_ROOT . 'App/Logic/Abstracts/',
    'adapter' => BASE_ROOT . 'App/Logic/Adapters/',
    'command' => BASE_ROOT . 'App/Logic/Commands/',
    'controller' => BASE_ROOT . 'App/Logic/Controllers/',
    'filter' => BASE_ROOT . 'App/Logic/Filters/',
    'handler' => BASE_ROOT . 'App/Logic/Handlers/',
    'helper' => BASE_ROOT . 'App/Logic/Helpers/',
    'interface' => BASE_ROOT . 'App/Logic/Interfaces/',
    'middleware' => BASE_ROOT . 'App/Logic/Middlewares/',
    'model' => BASE_ROOT . 'App/Logic/Models/',
    'util' => BASE_ROOT . 'App/Logic/Utils/',
    'validation' => BASE_ROOT . 'App/Logic/Validations/',
    // Design paths
    'design' => BASE_ROOT . 'App/Design/',
    'view' => BASE_ROOT . 'App/Design/view/',
    'layout' => BASE_ROOT . 'App/Design/layout/',
    'partial' => BASE_ROOT . 'App/Design/partial/',
    'error' => BASE_ROOT . 'App/Design/error/',
    'design_config' => BASE_ROOT . 'App/Design/config/',

    // Data path(s)
    'cache' => BASE_ROOT . 'Data/Cache/',
    'draft' => BASE_ROOT . 'Data/Drafts/',
    'log' => BASE_ROOT . 'Data/Logs/',

    // Translation path(s)
    'i18n' => BASE_ROOT . 'I18n/',

    // Vendor path(s)
    'vendor' => BASE_ROOT . 'vendor/',

    // Config path(s)
    'config' => BASE_ROOT . 'Config/',

    // Public path(s)
    'public' => BASE_ROOT . 'public/',

    // Resource path(s)
    'resource' => BASE_ROOT . 'resource/',

    // Manifest path
    'manifest' => BASE_ROOT . 'public/build/manifest.json',

    // Config file(s) path
    'default_config' => [
        'auth' => __DIR__ . '/auth.php',
        'cache' => __DIR__ . '/cache.php',
        'captcha' => __DIR__ . '/captcha.php',
        'cart' => __DIR__ . '/cart.php',
        'main' => __DIR__ . '/config.php',
        'csrf' => __DIR__ . '/csrf.php',
        'database' => __DIR__ . '/database.php',
        'hit' => __DIR__ . '/hit-counter.php',
        'i18n' => __DIR__ . '/i18n.php',
        'log' => __DIR__ . '/log.php',
        'mail' => __DIR__ . '/mail.php',
        'payment' => __DIR__ . '/payment.php',
        'router' => __DIR__ . '/router.php',
        'security' => __DIR__ . '/security.php',
        // webpack config
        // DO NOT change it at all
        'webpack' => __DIR__ . '/webpack.php',
        // Designer asset paths
        'includes' => BASE_ROOT . 'App/Design/config/includes.php',
    ]
];
