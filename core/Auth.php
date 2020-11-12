<?php namespace core;
class Auth
{
    //Login
    public function login(string $login, string $password)
    {
        setcookie('sid', $login . $password, 0, '/');
    }

    //Logout
    public function logout()
    {
        setcookie('sid', '', time() - 3600, '/');
    }
    public function isLogged(): bool
    {
        return isset($_COOKIE['sid']);
    }
}