<?php
$db_config = [
    'host' => 'localhost',
    'dbname' => 'postgres',
    'user' => 'postgres',
    'password' => 'postgres'
];

try {
    $conn = new PDO(
        "pgsql:host={$db_config['host']};dbname={$db_config['dbname']}",
        $db_config['user'],
        $db_config['password']
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Teste de conexão básica
    $conn->query("SELECT 1")->fetch();
    echo "✅ Conexão com o PostgreSQL funcionando!\n";

} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage();
}