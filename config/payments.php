<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Registration Fee
    |--------------------------------------------------------------------------
    |
    | Default registration fee in IDR, charged on every application.
    | Can be overridden per school in a future release.
    |
    */
    'registration_fee' => (int) env('PAYMENT_REGISTRATION_FEE', 250000),
];
