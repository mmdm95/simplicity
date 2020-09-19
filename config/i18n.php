<?php

use Sim\I18n\ISOLanguageCodes;

$dir_one_level_up = '../';

return [
    /**
     * Language directory
     */
    'language_dir' => __DIR__ . '/' . $dir_one_level_up . 'i18n',

    /**
     * Site language
     * Default is ** en ** or ** ISOLanguageCodes::LANGUAGE_ENGLISH **
     */
    'language' => ISOLanguageCodes::LANGUAGE_PERSIAN_FARSI,

    /**
     * Is it a RTL language
     * Default is ** false **
     */
    'is_rtl' => true,
];