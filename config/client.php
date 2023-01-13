<?php

return [
    'global_discount_value' => floatval(env('CLIENT_GLOBAL_DISCOUNT_VALUE', 0)),
    
    'mpc_start_timestamp' => env('MPC_START_TIMESTAMP', '2021-01-23 00:00:00'),
    
    'pantheon_document_id' => env('PANTHEON_DOCUMENT_ID', 24468),
];
