<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Person Language Lines
    |--------------------------------------------------------------------------
    */
    
    'title' => 'Persons',
    
    'data' => [
        'user_id' => 'User',
        'name' => 'Name',
        'type_id' => 'Type',
        'email' => 'Email',
        'phone' => 'Phone',
        'note' => 'Note',
        'code' => 'Code (Eurobit)',
        'stock_id' => 'Warehouse',
        'access' => 'Has access to app',
        'printer_type' => 'Printer type',
        'printer_receipt_url' => 'Printer API Url',
        'printer_access_token' => 'Printer API key',
    ],
    
    'actions' => [
        'create' => 'Create person',
        'edit' => 'Edit person',
        'assign' => 'Assign to user',
        'invite' => 'Send invite email to user',
        'route' => 'View routes',
        'new' => 'New person',
        'clients' => 'Clients',
    ],
    
    'notifications' => [
        'created' => 'Nova osoba je uspješno kreirana.',
        'updated' => 'Informacije o osobi su uspješno ažurirane.',
    ],
    
    'vars' => [
        'tabs' => [
            'categories' => 'Categories',
            'products' => 'Products',
        ],
        'kpi' => [
            'cash' => 'Gotovoina',
            'invoice' => 'Predračun',
            'in_currency' => 'U valuti',
            'in_15_days' => 'Do 15 dana',
            'over_15_days' => 'Preko 15 dana',
            'over_30_days' => 'Preko 30 dana',
            'over_45_days' => 'Preko 45 dana',
            'over_60_days' => 'Preko 60 dana',
        ],
        'kpi_values' => [
            'cash' => 5,
            'invoice' => 5,
            'in_currency' => 3,
            'in_15_days' => 2,
            'over_15_days' => 0,
            'over_30_days' => 0,
            'over_45_days' => 0,
            'over_60_days' => 0,
        ],
    ],
];
