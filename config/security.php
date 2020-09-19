<?php

return [
    // Encryption and decryption has two keys for encode and decode
    /*
     * Main key for encrypt data.
     * Note: You must enter a base64 string
     */
    'main_key' => getenv('APP_MAIN_KEY'),

    /**
     * Second key to assure encryption.
     * Note: You must enter a base64 string
     */
    'assured_key' => getenv('APP_ASSURED_KEY'),
];