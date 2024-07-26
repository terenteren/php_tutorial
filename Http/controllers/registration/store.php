<?php

use Core\App;
use Core\Database;
use Core\Validator;
use Http\Forms\LoginForm;

$db = App::resolve(Database::class);

$email = $_POST['email'];
$password = $_POST['password'];

$form = new LoginForm();

if (! $form->validate($email, $password)) {
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

    $db->query('INSERT INTO users (email, name, password) VALUES (:email, :name, :password)', [
        'email' => $email,
        'name' => explode('@', $email)[0],
        'password' => password_hash($password, PASSWORD_BCRYPT)
    ]);

    // 삽입된 사용자 ID 가져오기
    $userId = $db->lastInsertId();

    // 삽입된 사용자 정보 조회
    $user = $db->query('select * from users where id = :id', [
        'id' => $userId
    ])->find();

    login($user);

    header('Location: /');
    exit();
}




