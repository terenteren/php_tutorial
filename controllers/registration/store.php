<?php


use Core\App;
use Core\Database;
use Core\Validator;

$email = $_POST['email'];
$password = $_POST['password'];


// validate the form inputs.
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

$db = App::resolve(Database::class);
// check if the account already exists
$user = $db->query('select * from users where email = :email', [
    'email' => $email
])->find();

if ($user) {
    // then someone with that email already exists and has on account.
    // If yes, redirect to a login page.
    header('Location: /login');
    exit();
} else {
    // If not, save one to the database, and then log the user in, and redirect.
    $name = explode('@', $email)[0];
    $db->query('INSERT INTO users (email, name, password) VALUES (:email, :name, :password)', [
        'email' => $email,
        'name' => $name,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);

    // mark that the user has logged in.
    $_SESSION['user'] = [
        'email' => $email
    ];

    header('Location: /');
    exit();
}




