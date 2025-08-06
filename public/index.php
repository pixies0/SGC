<?php
define('BASE_PATH', dirname(__DIR__));
require __DIR__ . '/autoload.php';

use App\Config\Database;
use App\Repositories\ClienteRepository;
use App\Controllers\ClienteController;

try {
    // Inicializa a aplicação
    $database = new Database();
    $db = $database->getConnection();

    $repository = new ClienteRepository($db);
    $controller = new ClienteController($repository);

    // Roteamento básico
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    switch ($path) {
        case '/clientes':
            $controller->index();
            break;

        case '/clientes/cadastrar':
            $_SERVER['REQUEST_METHOD'] === 'POST'
                ? $controller->armazena($_POST)
                : $controller->cria();
            break;

        case (preg_match('|^/clientes/editar/\d+$|', $path) ? true : false):
            $id = (int) basename($path);
            $_SERVER['REQUEST_METHOD'] === 'POST'
                ? $controller->atualiza($id, $_POST)
                : $controller->edita($id);
            break;

        case (preg_match('|^/clientes/deletar/\d+$|', $path) ? true : false): // Corrigido para 'deletar'
            $id = (int) basename($path);
            $controller->deleta($id);
            break;

        case '/':
            require BASE_PATH . '/index.php';
            exit;
    }
} catch (Exception $e) {
    die("Erro na aplicação: " . $e->getMessage());
}
