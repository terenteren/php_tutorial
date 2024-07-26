<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);

$currentUserId = 2;

// find the corresponding note
$note = $db->query('select * from notes where id = :id', [
    'id' => $_POST['id']
])->findOrFail();


// authorize that the current user can edit the note
authorize($note['user_id'] == $currentUserId);


// validate the form
$errors = [];

if (! Validator::string($_POST['body'], 1, 1000)) {
    $errors['body'] = 'A body of no more than 1,000 characters is required';
}


// if no validation errors, update the record in the notes database table.
if (count($errors)) {
    return view("notes/edit", [
        'heading' => "Edit Note",
        'errors' => $errors,
        'note' => $note
    ]);
}

$db->query('UPDATE notes SET body = :body WHERE id = :id', [
    'body' => $_POST['body'],
    'id' => $_POST['id']
]);


// redirect the user
header('Location: /notes');
die();