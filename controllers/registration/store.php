<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);

$email = $_POST['email'];
$password = $_POST['password'];

$errors = [];
if (! Validator::email($email)) {
    $errors['email'] = 'Please provide a valid email address.';
}

if (! Validator::string($password, 7, 255)) {
    $errors['password'] = 'Please provide a valid password.';
}

if (! empty($errors)) {
    return view('registration/create', [
        'errors' => $errors
    ]);
}


$user = $db->query('select * from users where email = :email', [
    'email' => $email
])->find();

if ($user) {
    header('Location: /login');
    exit();
} else {
    $name = explode('@', $email)[0];
    $db->query('INSERT INTO users (email, name, password) VALUES (:email, :name, :password)', [
        'email' => $email,
        'name' => $name,
        'password' => password_hash($password, PASSWORD_BCRYPT)
    ]);

    login($user);

    header('Location: /');
    exit();
}




