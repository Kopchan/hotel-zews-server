<?php

return [
    'can_book_with_exist_book' => env('HOTEL_CAN_BOOK_MORE'       , false),
    'max_book_period'          => env('HOTEL_MAX_BOOK_PERIOD'     , 30),
    'max_far_book_start'       => env('HOTEL_MAX_FAR_BOOK_START'  , 360),
    'is_price_counts_by_days'  => env('HOTEL_PRICE_COUNTS_BY_DAYS', false),
];
