<?php

return [
    /*
     * The prefix key under which data is saved to the cookies.
     */
    'prefix' => env('TAIL_COOKIE_PREFIX', config('app.name', 'laravel').'_'),
];
