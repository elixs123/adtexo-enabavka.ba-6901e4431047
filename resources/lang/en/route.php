<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Routes Language Lines
    |--------------------------------------------------------------------------
    */
    
    'title' => 'Routes',
    
    'data' => [
        'person_id' => 'Person',
        'client_id' => 'Client',
        'week' => 'Week',
        'day' => 'Day',
        'rank' => 'Rank',
        'clients_num' => ':num client|:num clients',
        'today_routes' => 'Today routes',
        'tomorrow_routes' => 'Tomorrow routes',
    ],
    
    'actions' => [
        'delete' => 'Delete route',
        'assign' => 'Assign client to route',
    ],
    
    'notifications' => [
        'updated' => 'Informacije o routama su uspješno ažurirane.',
        'deleted' => 'Ruta je uspješno obrisana.',
        'assigned' => 'Ruta je uspješno dodjeljena.',
    ],
    
    'vars' => [
        'weeks' => [
            1 => 'Week 1',
            2 => 'Week 2',
            3 => 'Week 3',
            4 => 'Week 4',
        ],
        'days' => [
            'mon' => 'Monday',
            'tue' => 'Tuesday',
            'wed' => 'Wednesday',
            'thu' => 'Thursday',
            'fri' => 'Friday',
            'sat' => 'Saturday',
            'sun' => 'Sunday',
        ],
    ],
    
    'placeholders' => [
        'client' => 'Choose client',
    ],
];
