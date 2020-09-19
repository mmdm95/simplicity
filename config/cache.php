<?php

return [
    /**
     * If you need all your pages to cache, then set variable to true,
     * otherwise set it to false
     *
     * Note: If you want some of your pages have/haven't cache,
     * you can config it from your controller before page rendering
     * by passing true/false to [cachePage] function
     */
    'cache_page' => false,

    /**
     * Time to have cached page in seconds
     *
     * Note:
     *   1. After this time a new cache file will create
     *   2. You must specify it according to your preferences and your application policies.
     */
    'cache_time' => 18000, // 5 hours
];