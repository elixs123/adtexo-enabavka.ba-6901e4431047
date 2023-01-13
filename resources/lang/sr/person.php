<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Person Language Lines
    |--------------------------------------------------------------------------
    */
    
    'title' => 'Osobe',
    
    'data' => [
        'user_id' => 'Korisnik',
        'name' => 'Ime i prezime',
        'type_id' => 'Tip',
        'email' => 'Email',
        'phone' => 'Telefon',
        'code' => 'Šifra (Eurobit)',
        'note' => 'Napomena',
        'access' => 'Ima pristup sistemu',
        'printer_type' => 'Vsta štampača',
        'printer_receipt_url' => 'API Url štampača',
        'printer_access_token' => 'API ključ štampača',
    ],
    
    'actions' => [
        'create' => 'Kreiraj osobu',
        'edit' => 'Izmjeni osobu',
        'assign' => 'Kreiraj korisnika',
        'invite' => 'Pošalji email pozivnicu korisniku',
        'route' => 'Pogledaj rute',
        'new' => 'Nova osoba',
    ],
    
    'notifications' => [
        'created' => 'Nova osoba je uspješno kreirana.',
        'updated' => 'Informacije o osobi su uspješno ažurirane.',
    ],
    
    'vars' => [
        'tabs' => [
            'categories' => 'Kategorije',
            'products' => 'Proizvodi',
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
