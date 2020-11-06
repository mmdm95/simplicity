<?php

return [
    /**
     * Please Read This Before Use:
     * - We can have three types of platform here:
     *     1. Desktop
     *     2. Tablet
     *     3. Mobile
     * - In each platform we have the following structure
     *     1. The [common] key
     *       - In this section we put all files that are common to all pages
     * - Structure of all keys are like:
     *   EXP:
     *       [
     *           'js' => [
     *               'top' => [
     *                   ...
     *               ],
     *               'bottom' => [
     *                   ...
     *               ],
     *               'title' => 'The Title',
     *               'etc.' => ...
     *           ],
     *           'css' => [
     *               ...
     *           ],
     *           'etc.' => ...
     *       ]
     *
     * NOTE:
     *   [common] has a key that use inside your specific page
     *   as [common] key, OK that's confusing look at example below:
     *
     *   [
     *     'common' => [
     *        'default' => [
     *           'js' => [
     *                     'top' => [
     *                     ],
     *                     'bottom' => [
     *                     ],
     *                   ]
     *           'css' => css files,
     *         ]
     *     ],
     *     'example-page' => [
     *       'common' => 'default',
     *       ...
     *     ]
     *   ]
     *
     * See, you can have multiple common for each part of your application.
     * Isn't it cool :)
     *
     * NOTE:
     *   1. [common] key just have [js] and [css] keys
     *   2. You must use slash (/) to separate folder and files from each other.
     *   3. key of other items must be name of the file after [design] path
     *     [EXP:
     *       1. Assume that we have a file named [index] in [app/design/view],
     *          so the key name will be [view/index].
     *       2. Now Assume that we have another file named [index] in [app/design/partial/a-folder],
     *          so the key name will be [partial/a-folder/index].
     *   4. BE CAREFUL! If note number 3 is not observe, then the config will not be available.
     *   5. The config is according to the bigger platform (priority considered here)
     *     [EXP:
     *       If you don't specified mobile config, it'll get config according to upper device,
     *       that is tablet, and so on.]
     *   6. It detect which device is trying to get config, automatically for you
     *   7. BE CAREFUL! Please enter unique js and css file paths,
     *      but it try to detect redundant js and css files according to src and href values
     *   8. Add js and css file as following manner
     *     Exp.
     *      htmlspecialchars('<script type="js type like text/javascript" src="the src"></script>'),
     *      e('<script type="js type like text/javascript" src="the src"></script>'),
     */
    'desktop' => [
        'common' => [
            'default' => [
                'js' => [
                    'top' => [
                    ],
                    'bottom' => [
                    ],
                ],
                'css' => [
                    'top' => [
                    ],
                    'bottom' => [
                    ],
                ],
            ],
        ],
        'view/index' => [
            'common' => 'default',
            'js' => [
                'top' => [
                ],
                'bottom' => [
                ],
            ],
            'css' => [
                'top' => [
                ],
                'bottom' => [
                ],
            ],
        ],
    ],
    'tablet' => [
        'default' => [
            'common' => [
                'js' => [
                    'top' => [
                    ],
                    'bottom' => [
                    ],
                ],
                'css' => [
                    'top' => [
                    ],
                    'bottom' => [
                    ],
                ],
            ],
        ],
    ],
    'mobile' => [
        'default' => [
            'common' => [
                'js' => [
                    'top' => [
                    ],
                    'bottom' => [
                    ],
                ],
                'css' => [
                    'top' => [
                    ],
                    'bottom' => [
                    ],
                ],
            ],
        ],
    ],
];