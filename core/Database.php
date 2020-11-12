<?php namespace core;

class Database
{
    private static $instance = null;
    private $mysqli;

    public static function getConnection(): Database
    {
        if (!self::$instance) self::$instance = new Database;
        return self::$instance;
    }

    private function __construct() {
        $this->mysqli = new \mysqli('localhost:3306', 'root', 'password', 'est-test');
        $this->mysqli->query('SET NAMES UTF8'); // Encoding fix
        if ($this->mysqli->connect_errno)
            throw new \Exception($this->mysqli->connect_error);

        // For convenience return number values in native PHP types
        $this->mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
    }

    /**
     * @param string $sql
     * @return bool|\mysqli_result
     * @throws \Exception
     */
    public function querySql(string $sql)
    {
        $result = $this->mysqli->query($sql);
        if ($this->mysqli->errno)
            throw new \Exception($this->mysqli->error, $this->mysqli->errno);
        return $result;
    }
}