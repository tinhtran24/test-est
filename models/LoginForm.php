<?php namespace models;

use core\Auth;

class LoginForm
{
    const ERROR_NO_LOGIN = 1;
    const ERROR_NO_PASSWORD = 2;
    const ERROR_FAIL_ACCESS = 3;

    private $login;
    private $password;

    /**
     * @param array $post
     * @return array of error codes
     */
    public function run(array $post): array
    {
        $this->login = $post['login'] ?? '';
        $this->password = $post['password'] ?? '';

        $errors = $this->validate();
        if (!empty($errors)) return $errors;

        (new Auth)->login($this->login, $this->password);

        return [];
    }

    /**
     * @return array of error key codes
     */
    private function validate(): array
    {
        $errors = [];
        if ($this->login === '') $errors[] = self::ERROR_NO_LOGIN;
        if ($this->password === '') $errors[] = self::ERROR_NO_PASSWORD;

        if (empty($errors) && ($this->login !== 'admin' || $this->password !== '123'))
            $errors[] = self::ERROR_FAIL_ACCESS;

        return $errors;
    }
}