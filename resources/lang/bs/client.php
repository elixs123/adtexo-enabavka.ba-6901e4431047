<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Client Language Lines
    |--------------------------------------------------------------------------
    */
    
    'title' => 'Klijenti',
    
    'data' => [
        'type_id' => 'Tip',
        'jib' => 'JIB',
        'pib' => 'PIB',
        'code' => 'Šifra',
        'name' => 'Naziv',
        'is_location' => 'Da li je lokacija?',
        'location_code' => 'Šifra lokacije',
        'location_name' => 'Naziv lokacije',
        'location_type_id' => 'Format objekta',
        'category_id' => 'Kategorija',
        'photo' => 'Slika (JIB/PIB)',
        'photo_contract' => 'Slika (Ugovor)',
        'address' => 'Adresa',
        'city' => 'Grad',
        'postal_code' => 'Poštanski broj',
        'country_id' => 'Država',
        'phone' => 'Telefon',
        'note' => 'Napomena',
        'map' => 'Mapa',
        'payment_period' => 'Period plaćanja',
        'payment_type' => 'Tip plaćanja',
        'payment_discount' => 'Popust (in %)',
        'discount_value1' => 'Popust 1 (in %)',
        'discount_value2' => 'Popust 2 (in %)',
        'allowed_limit_in' => 'Dozvoljeni limit u valuti',
        'allowed_limit_outside' => 'Dozvoljeni limit van valute',
        'categories' => 'Kategorije',
        'products' => 'Proizvodi',
        'lang_id' => 'Jezik aplikacije',
        'stock_id' => 'Skladište',
        'salesman_person_id' => 'Komercijalista',
        'supervisor_person_id' => 'Supervizor',
        'responsible_person_id' => 'Odgovorna osoba za narudžbe',
        'payment_person_id' => 'Kontakt za naplatu',
        'no_other_locations' => 'Nema drugih lokacija',
        'actions' => 'Akcije',
        'payment_therms' => 'Uslovi plaćanja',
    ],
    
    'actions' => [
        'create' => 'Kreiraj klijenta',
        'edit' => 'Izmjeni klijenta',
        'create_location' => 'Kreiraj lokaciju',
        'edit_location' => 'Izmjeni lokaciju',
        'view_location' => 'Vidi lokacije',
        'find_on_map' => 'Prona]i na mapi',
        'assign' => 'Dodijeli',
        'assign_persons' => 'Dodijeli osobe',
        'remove_product' => 'Ukloni proizvod',
        'remove_action' => 'Ukloni akciju',
        'new' => 'Novi klijent',
        'show' => 'Pregledaj klijenta',
        'options' => 'Opcije',
        'status_to' => [
            'active' => 'Odobri klijente',
            'inactive' => 'Odbij klijente',
        ],
    ],
    
    'notifications' => [
        'created' => 'Novi klijent je uspješno kreiran.',
        'updated' => 'Informacije o klijentu su uspješno ažurirane.',
        'status' => 'Klijenti su uspješno prebačeni - :status.',
    ],
    
    'vars' => [
        'subtypes' => [
            'headquarter' => 'Sjedište',
            'location' => 'Lokacija',
        ],
        'location' => [
            'address' => 'Adresa',
            'radius' => 'Radius',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ],
        'tabs' => [
            'main' => 'Osnovno',
            'address' => 'Adresa',
            'persons' => 'Dodjelj. osobe',
            'payment' => 'Plaćanje',
            'routes' => 'Komerc. i ruta',
            'categories' => 'Kategorije',
            'products' => 'Proizvodi',
            'actions' => 'Akcije',
        ],
    ],
    
    'placeholders' => [
        'search' => 'Naziv klijenta, JIB, PIB ili šifra',
        'person' => 'Odaberi osobu',
        'category' => 'Odaberi kategoriju',
        'product' => 'Odaberi proizvod',
    ],
];
