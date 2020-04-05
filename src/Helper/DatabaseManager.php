<?php
namespace App\Helper;

class DatabaseManager
{
    private static $instance;

    private $connection;

    private function __construct()
    {
        $loader = new ConfigLoader();
        $config = $loader->load();
        $this->connection = new \PDO(...$config);
    }

    public static function getManager(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function query(string $statement, array $params = null)
    {
        if ($params) {
            $statement = $this->connection->prepare($statement, $params);

            return $statement->execute(array_values($params));
        } else {

            return $this->connection->query($statement);
        }
    }
}