<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Language Lines
    |--------------------------------------------------------------------------
    */
    
    'title' => 'Users',
    
    'data' => [
        'email' => 'Email',
        'password' => 'Password',
        'password_confirmation' => 'Password Confirmation',
        'photo' => 'Avatar',
        'role' => 'Role',
    ],
    
    'actions' => [
        'create' => 'Create user',
        'edit' => 'Edit user',
        'activity' => 'View activity',
    ],
    
    'notifications' => [
        'created' => 'Novi korisnik je uspješno kreiran.',
        'updated' => 'Informacije o korisniku su uspješno ažurirane.',
    ],
    
    'emails' => [
        'invite' => [
            'title' => 'Welcome to the :site_url',
            'message' => 'Dear <strong >:user_to_name</strong >,<br /><br />you have been invited to join the <strong >:site_url</strong>. <br /><br />Your login name is: <strong>:user_email</strong><br /><br />To get started, visit the <strong>:site_url</strong> and set your password.',
            'button' => 'Setup Your Password',
            'note' => '<strong>Note</strong>: Invitation link is valid until <strong>:date</strong>. <br />If this was a mistake, just ignore this email and nothing will happen.',
        ],
    ],
];
