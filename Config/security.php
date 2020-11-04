<?php

return [
    // Encryption and decryption has two keys for encode and decode
    /*
     * Main key for encrypt data.
     * Note: You must enter a base64 string
     */
    'main_key' => $_ENV['APP_MAIN_KEY'],

    /**
     * Second key to assure encryption.
     * Note: You must enter a base64 string
     */
    'assured_key' => $_ENV['APP_ASSURED_KEY'],
];