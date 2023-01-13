<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Payment configuration
	|--------------------------------------------------------------------------
	|
	*/
    
    /*
     * Types
     */
    'type' => [
        'personal_takeover',
        'express_post',
    ],

    
    /*
     * Services
     */
    'services' => [
        /*
         * Personal takeover
         */
        'personal_takeover_bih' => [
            'document_id' => 0,
            'amount' => 1,
            'currency' => 'KM',
        ],
        'personal_takeover_srb' => [
            'document_id' => 0,
            'amount' => 1,
            'currency' => 'RSD',
        ],
        
        /*
         * Express Post
         */
        'express_one' => [
            'document_id' => 2,
            'amount' => 7,
            'currency' => 'KM',
        ],
        'city_express' => [
            'document_id' => 2,
            'amount' => 7,
            'currency' => 'RSD',
        ],
    ],
];
