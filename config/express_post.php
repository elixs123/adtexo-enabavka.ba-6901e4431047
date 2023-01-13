<?php

return [
    
    'types' => [
        'express_one' => 'ExpressOne',
        'city_express' => 'CityExpress',
    ],
    
    'types_per_country' => [
        'bih' => [
            'express_one' => 'ExpressOne',
        ],
        'srb' => [
            'city_express' => 'CityExpress',
        ],
    ],
    
    'countries' => [
        'bih' => [
            'express_one',
        ],
        'srb' => [
            'city_express',
        ],
    ],
    
    'document_ref_prefix' => env('EXPRESS_POST_DOCUMENT_PREFIX', 'BN'),
];
