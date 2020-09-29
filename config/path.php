<?php

$dir_one_level_up = '../';

/**
 * Key options are used in many classes, so DO NOT change them (keys),
 * but you can add config to this
 *
 * NOTE:
 *   1. You can have your own structure with change path of folders from here
 */
return [
    // App path(s)
    'app' => __DIR__ . '/' . $dir_one_level_up . 'app/',
    // Logic paths
    'logic' => __DIR__ . '/' . $dir_one_level_up . 'app/logic/',
    'abstract' => __DIR__ . '/' . $dir_one_level_up . 'app/logic/abstract/',
    'adapter' => __DIR__ . '/' . $dir_one_level_up . 'app/logic/adapter/',
    'command' => __DIR__ . '/' . $dir_one_level_up . 'app/logic/command/',
    'controller' => __DIR__ . '/' . $dir_one_level_up . 'app/logic/controller/',
    'filter' => __DIR__ . '/' . $dir_one_level_up . 'app/logic/filter/',
    'handler' => __DIR__ . '/' . $dir_one_level_up . 'app/logic/handler/',
    'helper' => __DIR__ . '/' . $dir_one_level_up . 'app/logic/helper/',
    'interface' => __DIR__ . '/' . $dir_one_level_up . 'app/logic/interface/',
    'middleware' => __DIR__ . '/' . $dir_one_level_up . 'app/logic/middleware/',
    'model' => __DIR__ . '/' . $dir_one_level_up . 'app/logic/model/',
    'util' => __DIR__ . '/' . $dir_one_level_up . 'app/logic/util/',
    'validation' => __DIR__ . '/' . $dir_one_level_up . 'app/logic/validation/',
    // Design paths
    'design' => __DIR__ . '/' . $dir_one_level_up . 'app/design/',
    'view' => __DIR__ . '/' . $dir_one_level_up . 'app/design/view/',
    'layout' => __DIR__ . '/' . $dir_one_level_up . 'app/design/layout/',
    'partial' => __DIR__ . '/' . $dir_one_level_up . 'app/design/partial/',
    'error' => __DIR__ . '/' . $dir_one_level_up . 'app/design/error/',
    'design_config' => __DIR__ . '/' . $dir_one_level_up . 'app/design/config/',

    // Data path(s)
    'cache' => __DIR__ . '/' . $dir_one_level_up . 'data/cache/',
    'draft' => __DIR__ . '/' . $dir_one_level_up . 'data/draft/',
    'log' => __DIR__ . '/' . $dir_one_level_up . 'data/log/',

    // Translation path(s)
    'i18n' => __DIR__ . '/' . $dir_one_level_up . 'i18n/',

    // Core path(s)
    'core' => __DIR__ . '/' . $dir_one_level_up . 'core/',

    // Vendor path(s)
    'vendor' => __DIR__ . '/' . $dir_one_level_up . 'vendor/',

    // Config path(s)
    'config' => __DIR__ . '/' . $dir_one_level_up . 'config/',

    // Public path(s)
    'public' => __DIR__ . '/' . $dir_one_level_up . 'public/',

    // Resource path(s)
    'resource' => __DIR__ . '/' . $dir_one_level_up . 'resource/',

    // Manifest path
    'manifest' => __DIR__ . '/' . $dir_one_level_up . 'public/build/manifest.json',

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
        'includes' => __DIR__ . '/' . $dir_one_level_up . 'app/design/config/includes.php',
    ]
];
