<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Client Language Lines
    |--------------------------------------------------------------------------
    */
    
    'title' => 'Clients',
    
    'data' => [
        'type_id' => 'Type',
        'jib' => 'JIB',
        'pib' => 'PIB',
        'code' => 'Client code',
        'name' => 'Client name',
        'is_location' => 'Is location?',
        'location_code' => 'Location code',
        'location_name' => 'Location name',
        'location_type_id' => 'Location type',
        'category_id' => 'Location category',
        'photo' => 'Photo (JIB/PIB)',
        'photo' => 'Photo (Contract)',
        'address' => 'Address',
        'city' => 'City',
        'postal_code' => 'Postal code',
        'country_id' => 'Country',
        'phone' => 'Phone',
        'note' => 'Note',
        'map' => 'Map',
        'payment_period' => 'Payment period',
        'payment_type' => 'Payment type',
        'payment_discount' => 'Payment discount (in %)',
        'discount_value1' => 'Discount 1 (in %)',
        'discount_value2' => 'Discount 2 (in %)',
        'allowed_limit_in' => 'Allowed limit in currency',
        'allowed_limit_outside' => 'Allowed limit outside currency',
        'categories' => 'Categories',
        'products' => 'Products',
        'lang_id' => 'App lang',
        'stock_id' => 'Warehouse',
        'no_other_locations' => 'No other locations',
        'actions' => 'Actions',
        'payment_therms' => 'Payment therms',
    ],
    
    'actions' => [
        'create' => 'Create client',
        'edit' => 'Edit client',
        'create_location' => 'Create location',
        'edit_location' => 'Edit location',
        'view_location' => 'View locations',
        'find_on_map' => 'Find on map',
        'assign' => 'Assign',
        'assign_persons' => 'Assign persons',
        'remove_product' => 'Remove product',
        'remove_action' => 'Remove action',
        'new' => 'New client',
    ],
    
    'notifications' => [
        'created' => 'Novi komitent je uspješno kreiran.',
        'updated' => 'Informacije o komitentu su uspješno ažurirane.',
    ],
    
    'vars' => [
        'subtypes' => [
            'headquarter' => 'Headquarter',
            'location' => 'Location',
        ],
        'location' => [
            'address' => 'Address',
            'radius' => 'Radius',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ],
        'tabs' => [
            'main' => 'Main',
            'address' => 'Address',
            'persons' => 'Ass. persons',
            'payment' => 'Payment',
            'routes' => 'Routes',
            'categories' => 'Categories',
            'products' => 'Products',
            'actions' => 'Actions',
        ],
    ],
    
    'placeholders' => [
        'search' => 'Client name, JIB, PIB or Code',
        'person' => 'Select person',
        'category' => 'Select category',
        'product' => 'Select product',
    ],
];
