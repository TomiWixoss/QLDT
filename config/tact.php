<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Loyalty Points Configuration
    |--------------------------------------------------------------------------
    |
    | Points value in VND. 1 point = points_value VND
    | Default: 1000 (1 point = 1,000đ)
    |
    */
    'points_value' => env('TACT_POINTS_VALUE', 1000),

    /*
    |--------------------------------------------------------------------------
    | Points Earning Rate
    |--------------------------------------------------------------------------
    |
    | Amount in VND required to earn 1 point
    | Default: 100000 (100,000đ = 1 point)
    |
    */
    'points_per_amount' => env('TACT_POINTS_PER_AMOUNT', 100000),
];
