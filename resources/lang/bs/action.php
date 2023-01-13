<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Action Language Lines
    |--------------------------------------------------------------------------
    */
    
    'title' => 'Akcije',
    
    'data' => [
        'name' => 'Naziv',
        'type_id' => 'Tip',
        'started_at' => 'Počinje',
        'finished_at' => 'Završava',
        'roles' => 'Namjenjeno',
        'products' => 'Proizvod',
        'qty' => 'Količina',
        'stock' => 'Zaliha',
        'stock_type' => 'Tip zalihe',
        'discount' => 'Popust',
        'mpc_discount' => 'MPC popust',
        'vpc_discount' => 'VPC popust',
        'stock_id' => 'Lager',
        'product_id' => 'Gratis',
        'vpc_price' => 'VPC cijena',
        'mpc_price' => 'MPC cijena',
        'photo' => 'Slika',
        'presentation' => 'Prezentacija',
        'technical_sheet' => 'Tehnički list',
        'available' => 'Dostupno',
        'free_delivery' => 'Besplatna dostava',
    ],
    
    'actions' => [
        'create' => 'Kreiraj akciju',
        'edit' => 'Izmjeni akciju',
        'products' => 'Proizvodi',
        'quantity' => 'Količina',
        'stats' => 'Pregled prodaje',
    ],
    
    'notifications' => [
        'created' => 'Nova akcija je uspješno kreirana.',
        'updated' => 'Informacije o akciji su uspješno ažurirane.',
        'products' => 'Informacije o akcijskim proizvodima su uspješno ažurirane.',
        'quantity' => 'Uspješno ste dodali akciju.',
    ],
    
    'vars' => [
        'stock_types' => [
            'limited' => 'Ograničena zaliha',
            'unlimited' => 'Do isteka zaliha',
        ],
        'tabs' => [
            'action' => 'Akcijski proizvodi',
            'gratis' => 'Gratis proizvodi',
        ],
    ],
    
    'errors' => [
        'not_found' => 'Akcija #:id nije pronađen!',
        'gratis_not_found' => 'Gratis proizvod #:id nije pronađen!',
    ],
    
    'stats' => [
        'labels' => [
            'salesmen' => 'Svi komercijalisti',
            'action' => 'Sve akcije',
        ],
        'total' => [
            'actions' => 'Ukupno akcija',
            'stock' => 'Lager',
            'sales' => 'Prodaja',
        ],
        'documents' => [
            'document' => 'Narudžba',
            'date' => 'Datum',
            'action' => 'Akcija',
            'qty' => 'Količina',
            'salesman_id' => 'Komercijalista',
        ],
    ],
    
    'placeholders' => [
        'search' => 'Upišite naziv akcije',
    ],
];
