<?php namespace models;

use core\Database;
use core\Auth;

class TodoItemModel
{
    const ERROR_ALL_OK = 0;
    const ERROR_START_DATE_EMPTY = 1;
    const ERROR_END_DATE_EMPTY = 2;
    const ERROR_TEXT_EMPTY = 3;

    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function countAllItems(): int
    {
        try {
            return $this->db
                ->querySql("SELECT COUNT(id) FROM todos")
                ->fetch_array(MYSQLI_NUM)[0];
        } catch (\Exception $e) {
        }
    }

    public function getById(int $itemId): array
    {
        try {
            return $this->db
                ->querySql("SELECT id, work_name, start_date, end_date, status FROM todos WHERE id=$itemId")
                ->fetch_all(MYSQLI_ASSOC);
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
            "SELECT id, work_name, start_date, end_date, status FROM todos
            ORDER BY $sortField $sortOrder
            LIMIT $offset, $onPageLimit"
        )->fetch_all(MYSQLI_ASSOC);
    }

    public function addItemFromPost(array $post): array
    {
        $errors = $this->validatePost($post);
        if (!empty($errors)) return $errors;

        $workName = $post['work_name'];
        $startDate = $post['start_date'];
        $endDate = $post['end_date'];
        $status = $post['status'];
        $db = Database::getConnection();
        $db->querySql(
            "INSERT INTO todos (work_name, start_date, end_date, status) 
            VALUES ('{$workName}', '{$startDate}', '{$endDate}', '$status')"
        );
        return [];
    }

    public function updateItemFromPost(int $itemId, array $post): array
    {
        $errors = $this->validatePost($post);
        if (!empty($errors)) return $errors;
        $workName = $post['work_name'];
        $startDate = $post['start_date'];
        $endDate = $post['end_date'];
        $status = $post['status'];
        $db = Database::getConnection();
        $db->querySql(
            "UPDATE todos SET 
                work_name='{$workName}',
                start_date= '{$startDate}', 
                end_date = '{$endDate}', 
                status =  '$status' 
                WHERE id = {$itemId}"
        );
        return [];
    }

    public function updateStatus(int $itemId, string $status)
    {
        Database::getConnection()->querySql(
            "UPDATE todos SET status = {$status} WHERE id = {$itemId}"
        );
    }

    public function deleteItem(int $itemId)
    {
        Database::getConnection()->querySql(
            "DELETE FROM todos WHERE id = {$itemId}"
        );
    }


    public function updateWorkName(int $itemId, string $newText)
    {
        $encodedText = $this->encodeText($newText);
        Database::getConnection()->querySql(
            "UPDATE todos SET work_name = '$encodedText'WHERE id = $itemId"
        );
    }

    /**
     * @param array $post
     * @return array of error key codes that are ERROR_ constants of this class.
     */
    private function validatePost(array $post): array
    {
        $errors = [];

        $username = $post['start_date'] ?? '';
        if ($username === '') $errors[] = self::ERROR_START_DATE_EMPTY;

        $email = $post['end_date'] ?? '';
        if ($email === '') $errors[] = self::ERROR_END_DATE_EMPTY;

        $text = $post['work_name'] ?? '';
        if (!$text) $errors[] = self::ERROR_TEXT_EMPTY;
        return $errors;
    }

    private function encodeText(string $text): string
    {
        return htmlspecialchars($text, ENT_COMPAT | ENT_HTML401 | ENT_QUOTES);
    }
}