<?php

use Core\Authenticator;
use Http\Forms\LoginForm;

$attributes = [
    'email' => $_POST['email'],
    'password' => $_POST['password']
];

$form = LoginForm::validate($attributes);

$signedIn = (new authenticator)->attempt($attributes);

if (!$signedIn) {
    $form->error(
        'email',  'No matching account found for that email address and password'
    )->throw();
}


redirect('/');

