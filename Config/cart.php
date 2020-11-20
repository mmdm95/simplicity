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
            'users' => [
                'table_name' => 'users',
                'columns' => [
                    'id' => 'id',
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                ],
            ],
            'products' => [
                'table_name' => 'products',
                'columns' => [
                    'id' => 'id',
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                ],
            ],
            'product_property' => [
                'table_name' => 'product_property',
                'columns' => [
                    'id' => 'id',
                    'code' => 'code',
                    'product_id' => 'product_id',
                    'stock_count' => 'stock_count',
                    'max_cart_count' => 'max_cart_count',
                    'price' => 'price',
                    'discounted_price' => 'discounted_price',
                    'tax_rate' => 'tax_rate',
                    'is_available' => 'is_available',
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                    'code' => 'VARCHAR(20) NOT NULL',
                    'product_id' => 'INT(11) UNSIGNED NOT NULL',
                    'stock_count' => 'INT(11) UNSIGNED NOT NULL',
                    'max_cart_count' => 'INT(11) UNSIGNED NOT NULL',
                    'price' => 'DECIMAL(23, 3) UNSIGNED NOT NULL',
                    'discounted_price' => 'DECIMAL(23, 3) UNSIGNED NOT NULL',
                    'tax_rate' => 'DECIMAL(5, 2) UNSIGNED NOT NULL DEFAULT 0',
                    'is_available' => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT 1',
                ],
                'constraints' => [
                    'ADD CONSTRAINT UC_Code UNIQUE (code)',
                    'ADD CONSTRAINT fk_pp_p FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE',
                ],
            ],
            'carts' => [
                'table_name' => 'carts',
                'columns' => [
                    'id' => 'id',
                    'user_id' => 'user_id',
                    'name' => 'name',
                    'created_at' => 'created_at',
                    'expire_at' => 'expire_at',
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                    'user_id' => 'INT(11) UNSIGNED NOT NULL',
                    'name' => 'VARCHAR(255) NOT NULL',
                    'created_at' => 'INT(11) UNSIGNED NOT NULL',
                    'expire_at' => 'INT(11) UNSIGNED NOT NULL',
                ],
                'constraints' => [
                    'ADD CONSTRAINT UC_UserID UNIQUE (user_id,name)',
                ],
            ],
            'cart_item' => [
                'table_name' => 'cart_item',
                'columns' => [
                    'id' => 'id',
                    'cart_id' => 'cart_id',
                    'product_property_id' => 'product_property_id',
                    'qnt' => 'qnt',
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                    'cart_id' => 'INT(11) UNSIGNED NOT NULL',
                    'product_property_id' => 'INT(11) UNSIGNED NOT NULL',
                    'qnt' => 'INT(11) UNSIGNED NOT NULL',
                ],
                'constraints' => [
                    'ADD CONSTRAINT fk_ci_c FOREIGN KEY(cart_id) REFERENCES carts(id) ON DELETE CASCADE ON UPDATE CASCADE',
                    'ADD CONSTRAINT fk_ci_pp FOREIGN KEY(product_property_id) REFERENCES product_property(id) ON DELETE CASCADE ON UPDATE CASCADE',
                ],
            ],
        ],
    ],
];
