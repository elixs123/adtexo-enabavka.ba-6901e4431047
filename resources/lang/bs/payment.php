<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Payment Language Lines
    |--------------------------------------------------------------------------
    */
    
    'title' => 'Plaćanje',
    
    'data' => [
        'type' => 'Tip',
        'service' => 'Servis',
        'file' => 'Fajl',
        'uploaded_at' => 'Uploadovano',
        'uploaded_by' => 'Uploadovano',
        'confirmed_at' => 'Potvrđeno',
        'confirmed_by' => 'Potvrđeno',
        'config' => [
            'shipment_number' => 'Br. Pos.',
            'document_id' => 'Ref. Br.',
            'amount' => 'Naplata',
            'currency' => 'Valuta',
        ],
        'total_payments' => 'Ukupno',
        'total_documents' => 'Ukupno (dokumenti)',
        'status' => 'Status',
        'date' => 'Datum',
    ],
    
    'actions' => [
        'create' => 'Kreiraj plaćanje',
        'edit' => 'Izmjeni plaćanje',
        'show' => 'Pogledaj detalje',
        'confirm' => 'Potvrdi plaćanje',
    ],
    
    'notifications' => [
        'created' => 'Nova plaćanje je uspješno kreirano.',
        'updated' => 'Informacije o plaćanju su uspješno ažurirane.',
        'confirmed' => 'Plaćanje je uspješno potvrđeno.',
    ],
    
    'vars' => [
        'type' => [
            'personal_takeover' => 'Lično preuzimanje',
            'express_post' => 'Brza pošta',
        ],
        'services' => [
            'personal_takeover_bih' => 'Lično preuzimanje (BiH)',
            'personal_takeover_srb' => 'Lično preuzimanje (SRB)',
            'express_one' => 'Express One (BiH)',
            'city_express' => 'City Express (SRB)',
        ],
    ],
];
