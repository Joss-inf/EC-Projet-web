<?php
class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $this->loadEnv(path: '../../../../doc/.env'); // ajuste le chemin selon l’endroit

        $host = getenv('DB_HOST');
        $db_name = getenv('DB_NAME');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');

        try {
            $this->conn = new PDO(
                dsn: "mysql:host=$host;dbname=$db_name;charset=utf8mb4",
                username: $username,
                password: $password
            );
            $this->conn->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }
    }

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }

    public static function closeConnection(): void
    {
        if (self::$instance !== null) {
            self::$instance->conn = null;
            self::$instance = null;
        }
    }
    private function loadEnv($path): void
    {
        if (!file_exists(filename: $path)) return;
        $lines = file(filename: $path, flags: FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(haystack: trim(string: $line), needle: '#')) continue;
            list($key, $value) = explode(separator: '=', string: $line, limit: 2);
            putenv(assignment: trim(string: $key) . '=' . trim(string: $value));
        }
    }
}
