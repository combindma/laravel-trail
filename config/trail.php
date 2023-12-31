<?php

return [
    /*
     * The prefix key under which data is saved to the cookies.
     */
    'prefix' => env('TAIL_COOKIE_PREFIX', config('app.name', 'laravel')).'_',

    /*
     * The cookie duration in seconds used to store data. By default, we use 180 days.
     */
    'cookie_duration' => env('TAIL_COOKIE_DURATION', 60 * 24 * 180),

    /*
     * Enable or disable script rendering. Useful for local development.
     */
    'enabled' => env('TAIL_ENABLED', false),
];
