<?php

return [
    /**
     * Base path to prepend other directory names
     */
    'base_dir' => 'public/',

    /**
     * The path that should check for all built assets
     */
    'build_dir' => 'public/build/',

    'rules' => [
        /**
         * It will just apply to build_dir path from above.
         * It should be the following format:
         *
         *   [
         *     directory name => [
         *       // if copy be first, is better because it'll copy files
         *       // first and then if test is matched for move, then files
         *       // will move to dir_name
         *       copy => [
         *         test => a regex should pass,
         *         overwrite => true|false,
         *         exclude => a regex or an array of files with/without extension
         *                    that you don't want to change with operation
         *       ],
         *       move => [
         *         test => a regex should pass,
         *         overwrite => true|false,
         *         exclude => a regex or an array of files with/without extension
         *                    that you don't want to change with operation
         *       ]
         *     ],
         *     exclude => a regex or an array of files with/without
         *                extension that you don't want to change at all
         *   ]
         */

        'js' => [
            'move' => [
                'test' => '/\.(mjs|js|jsx)(\.map)?$/i',
                'overwrite' => true,
            ],
        ],

        'css' => [
            'move' => [
                'test' => '/\.(css|scss|sass)(\.map)?$/i',
                'overwrite' => true,
            ],
        ],

        'image' => [
            'move' => [
                'test' => '/\.(bmp|svg|png|jpe?g|gif)$/i',
                'overwrite' => true,
            ],
        ],

        'fonts' => [
            'move' => [
                'test' => '/\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/i',
                'overwrite' => true,
            ],
        ],

        'exclude' => ['manifest.json'],
    ],
];