<?php

return [
    /**
     * Usually you don't need to change structure
     */
    'structure' => [
        /**
         * Follow this only rule and you'll never have
         * problem with config file(s):
         * -- DO NOT CHANGE ANY KEY, JUST CHANGE VALUES --
         *
         * Please do not change keys to prevent
         * any problem :)
         */
        'blueprints' => [
            /**
             * lib table alias name => [
             *   'table_name' => actual table's name
             *   'columns' => [columns' name array],
             *   'types' => [
             *     column's name from columns section above => the sql type etc.
             *     ...
             *   ],
             *   'constraints' => [
             *     the keys are not important and values are the constraints
             *     ...
             *   ],
             *   ...
             * ],
             * ...
             *
             * Note:
             *   Please do not change keys and just
             *   change values of them
             */
            'hits' => [
                'table_name' => 'hits',
                'columns' => [
                    'id' => 'id',
                    'url' => 'url',
                    'type' => 'type',
                    'view_count' => 'view_count',
                    'unique_view_count' => 'unique_view_count',
                    'from_time' => 'from_time',
                    'to_time' => 'to_time',
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                    'url' => 'TEXT NOT NULL',
                    'type' => 'INT(5) UNSIGNED DEFAULT NULL',
                    'view_count' => 'BIGINT(20) UNSIGNED NOT NULL  DEFAULT 0',
                    'unique_view_count' => 'BIGINT(20) UNSIGNED NOT NULL  DEFAULT 0',
                    'from_time' => 'INT(11) UNSIGNED NOT NULL',
                    'to_time' => 'INT(11) UNSIGNED NOT NULL',
                ],
            ],
        ],
    ],
];