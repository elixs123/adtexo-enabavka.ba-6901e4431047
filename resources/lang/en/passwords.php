<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reset Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    'password' => 'Passwords must be at least six characters and match the confirmation.',
    'reset' => 'Your password has been reset!',
    'sent' => 'We have e-mailed your password reset link!',
    'token' => 'This password reset token is invalid.',
    'user' => "We can't find a user with that e-mail address.",
    
    'pages' => [
        'recover' => [
            'title' => 'Recover your password',
            'message' => 'Please enter your email address and we\'ll send you instructions on how to reset your password.',
            'form' => [
                'email' => 'Email',
                'back' => 'Back to Login',
                'recover' => 'Recover Password',
            ],
            'email' => [
                'title' => 'Password reset',
                'message' => 'Dear <strong >:user_to_name</strong >,<br /><br />you requested that the password be reset for your <strong >:site_url</strong> account. <br /><br />To reset your password, click on the following link:',
                'button' => 'Reset Your Password',
                'note' => '<strong>Note</strong>: Password reset link is valid until <strong>:date</strong>. <br />If this was a mistake, just ignore this email and nothing will happen.',
            ],
        ],
        'reset' => [
            'title' => 'Reset Password',
            'message' => 'Please enter your new password.',
            'form' => [
                'email' => 'Email',
                'password' => 'Password',
                'confirm_password' => 'Confirm Password',
                'back' => 'Back to Login',
                'reset' => 'Reset',
            ],
        ],
    ],
    
];
