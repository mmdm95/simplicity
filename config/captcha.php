<?php

return [
    /**
     * Font filename for captcha generation
     */
    'font' => null,

    /**
     * Time to live of captcha to validate
     * after this time, captcha is not valid anymore
     *
     * Note: time must be in seconds
     *
     * Default is 600s or 10min
     */
    'expiration' => 600,
];