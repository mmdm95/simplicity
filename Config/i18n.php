<?php

use Sim\I18n\ISOLanguageCodes;

return [
    /**
     * Language directory
     */
    'language_dir' => BASE_ROOT . 'i18n',

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