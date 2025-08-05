<?php
namespace App\Config;

use PDO;
use Exception;
use PDOException;

class Database {
    private $host;
    private $dbname;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        // Carrega as configurações do arquivo .ini
        $config = parse_ini_file(__DIR__ . '/../../config/database.ini', true);

        $this->host = $config['database']['host'];
        $this->dbname = $config['database']['dbname'];
        $this->username = $config['database']['user'];
        $this->password = $config['database']['password'];
    }

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};dbname={$this->dbname}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro na conexão: " . $e->getMessage());
        }

        return $this->conn;
    }
}