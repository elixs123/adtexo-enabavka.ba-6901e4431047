<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Language Lines
    |--------------------------------------------------------------------------
    */
    
    'title' => 'Korisnici',
    
    'data' => [
        'email' => 'E-mail',
        'password' => 'Lozinka',
        'password_confirmation' => 'Lozinka [potvrda]',
        'photo' => 'Avatar',
        'role' => 'Uloga',
    ],
    
    'actions' => [
        'create' => 'Kreiraj korisnika',
        'edit' => 'Izmjeni korisnika',
        'login_as' => 'Prijavi se kao ovaj korisnik',
    ],
    
    'notifications' => [
        'created' => 'Novi korisnik je uspješno kreiran.',
        'updated' => 'Informacije o korisniku su uspješno ažurirane.',
    ],
    
    'emails' => [
        'invite' => [
            'title' => 'Dobrodošli na :site_url',
            'message' => 'Dragi <strong >:user_to_name</strong >,<br /><br />pozvani ste da se pridružite <strong >:site_url</strong>. <br /><br />Pristupni email je: <strong>:user_email</strong><br /><br />Za početak, posjetite <strong>:site_url</strong> i postavite svoju lozinku.',
            'button' => 'Postavi lozinku',
            'note' => '<strong>Napomena</strong>: Ovaj link je validan do <strong>:date</strong>. <br />Ako je ovo greška, ignorišite ovaj email i ništa se neće desiti.',
        ],
    ],
];
