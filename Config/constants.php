<?php

use Sim\I18n\ISOLanguageCodes;

//**** Application Version ****
defined("APP_VERSION") OR define("APP_VERSION", "1.0.0");

/***************************************
 * You can add your constants here
 ***************************************/

defined("APP_LANGUAGE") OR define("APP_LANGUAGE", ISOLanguageCodes::LANGUAGE_ENGLISH);

defined("NOT_FOUND_ADMIN") OR define("NOT_FOUND_ADMIN", 'admin.page.notfound');
defined("NOT_FOUND") OR define("NOT_FOUND", 'page.notfound');

defined("SERVER_ERROR") OR define("SERVER_ERROR", 'page.servererror');

