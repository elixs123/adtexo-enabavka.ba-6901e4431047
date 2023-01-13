<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reminder Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    'password' => 'Lozinke moraju da budu šest karaktera i da se slaže sa potvrdnom lozinkom.',
    'reset'    => 'Lozinka je resetovana!',
    'sent'     => 'Poslan vam je e-mail za povrat lozinke!',
    'token'    => 'Ovaj token za resetovanje lozinke nije ispravan.',
    'user'     => 'Ne može se pronaći korisnik sa tom e-mail adresom.',

    'pages' => [
        'recover' => [
            'title' => 'Izmjena lozinke',
            'message' => 'Unesite svoju adresu e-mail adresu i poslat ćemo vam upute kako izmjeniti lozinku.',
            'form' => [
                'email' => 'E-mail',
                'back' => 'Nazad na prijavu',
                'recover' => 'Izmjena lozinke',
            ],
            'email' => [
                'title' => 'Izmjena lozinke',
                'message' => 'Dear <strong >:user_to_name</strong >,<br /><br />you requested that the password be reset for your <strong >:site_url</strong> account. <br /><br />To reset your password, click on the following link:',
                'button' => 'Izmjenite svoju lozinku',
                'note' => '<strong>Note</strong>: Password reset link is valid until <strong>:date</strong>. <br />If this was a mistake, just ignore this email and nothing will happen.',
            ],
        ],
        'reset' => [
            'title' => 'Reset Password',
            'message' => 'Please enter your new password.',
            'form' => [
                'email' => 'E-mail',
                'password' => 'Lozinka',
                'confirm_password' => 'Lozinka [Potvrda]',
                'back' => 'Nazad na prijavu',
                'reset' => 'Izmjeni',
            ],
        ],
    ],    
    
];
