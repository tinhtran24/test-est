<?php namespace models;

use core\Database;
use core\Auth;

class TodoItemModel
{
    const ERROR_ALL_OK = 0;
    const ERROR_USERNAME_EMPTY = 1;
    const ERROR_EMAIL_EMPTY = 2;
    const ERROR_TEXT_EMPTY = 3;
    const ERROR_EMAIL_INVALID = 4;

    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function countAllItems(): int
    {
        try {
            return $this->db
                ->querySql("SELECT COUNT(todo_id) FROM todos")
                ->fetch_array(MYSQLI_NUM)[0];
        } catch (\Exception $e) {
        }
    }

    public function loadPaginatedItems(
        int $offset,
        int $onPageLimit,
        string $sortField,
        string $sortOrder
    ): array
    {
        return $this->db->querySql(
            "SELECT * FROM todos
            ORDER BY $sortField $sortOrder
            LIMIT $offset, $onPageLimit"
        )->fetch_all(MYSQLI_ASSOC);
    }

    public function addItemFromPost(array $post): array
    {
        $errors = $this->validatePost($post);
        if (!empty($errors)) return $errors;

        $username = $post['username'];
        $email = $post['email'];
        $encodedText = $this->encodeText($post['text']);

        $db = Database::getConnection();
        $db->querySql(
            "INSERT INTO todos (username, email, text) 
            VALUES ('{$username}', '{$email}', '$encodedText')"
        );

        return [];
    }

    public function updateStatus(int $itemId, int $status)
    {
        Database::getConnection()->querySql(
            "UPDATE todos SET status = {$status} WHERE todo_id = {$itemId}"
        );
    }

    public function updateText(int $itemId, string $newText)
    {
        $encodedText = $this->encodeText($newText);
        $adminEdit = (new Auth)->isLogged() ? '1' : '0';
        Database::getConnection()->querySql(
            "UPDATE todos SET text = '$encodedText', admin_edit = $adminEdit
            WHERE todo_id = $itemId"
        );
    }

    /**
     * @param array $post
     * @return array of error key codes that are ERROR_ constants of this class.
     */
    private function validatePost(array $post): array
    {
        $errors = [];

        $username = $post['username'] ?? '';
        if ($username === '') $errors[] = self::ERROR_USERNAME_EMPTY;

        $email = $post['email'] ?? '';
        if ($email === '') $errors[] = self::ERROR_EMAIL_EMPTY;
        else {
            $emailPattern = '/^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/';
            if (!preg_match($emailPattern, $email)) $errors[] = self::ERROR_EMAIL_INVALID;
        }

        $text = $post['text'] ?? '';
        if (!$text) $errors[] = self::ERROR_TEXT_EMPTY;
        return $errors;
    }

    private function encodeText(string $text): string
    {
        return htmlspecialchars($text, ENT_COMPAT | ENT_HTML401 | ENT_QUOTES);
    }
}