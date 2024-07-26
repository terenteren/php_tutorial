<?php

namespace Core;

class Authenticator
{
    public function attempt($attributes)
    {
        $user = App::resolve(Database::class)
            ->query('select * from users where email = :email', [
            'email' => $attributes['email']
        ])->find();

        if ($user) {
            if (password_verify($attributes['password'], $user['password'])) {
                $this->login([
                    'email' => $attributes['email']
                ]);

                return true;
            }
        }

        return false;
    }

    public function login($user)
    {
        $_SESSION['user'] = [
            'email' => $user['email']
        ];

        session_regenerate_id(true);
    }

    public function logout()
    {
        Session::destroy();
    }

}