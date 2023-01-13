<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Routes Language Lines
    |--------------------------------------------------------------------------
    */
    
    'title' => 'Rute',
    
    'data' => [
        'person_id' => 'Osoba',
        'client_id' => 'Klijent',
        'week' => 'Sedmica',
        'day' => 'Dan',
        'rank' => 'Rang',
        'clients_num' => ':num klijent|:num klijenta',
        'today_routes' => 'Današnje rute',
        'tomorrow_routes' => 'Sutrašnje rute',
    ],
    
    'actions' => [
        'delete' => 'Obriši rutu',
        'assign' => 'Dodaj klijenta za rutu',
    ],
    
    'notifications' => [
        'updated' => 'Informacije o routama su uspješno ažurirane.',
        'deleted' => 'Ruta je uspješno obrisana.',
        'assigned' => 'Ruta je uspješno dodjeljena.',
    ],
    
    'vars' => [
        'weeks' => [
            1 => 'Sed. 1',
            2 => 'Sed. 2',
            3 => 'Sed. 3',
            4 => 'Sed. 4',
        ],
        'days' => [
            'mon' => 'Ponedjeljak',
            'tue' => 'Utorak',
            'wed' => 'Srijeda',
            'thu' => 'Četvrtak',
            'fri' => 'Petak',
            'sat' => 'Subota',
            'sun' => 'Nedjelja',
        ],
    ],
    
    'placeholders' => [
        'client' => 'Odaberi klijenta',
    ],
];
