<?php
define('BASE_PATH', dirname(__DIR__));
require __DIR__ . '/autoload.php';

use App\Config\Database;
use App\Repositories\ClienteRepository;
use App\Controllers\ClienteController;
use App\Controllers\DashboardController;
use App\Controllers\LandingController;

try {
    // Inicializa a aplicação
    $database = new Database();
    $db = $database->getConnection();

    // Cria os repositórios e controllers
    $repository = new ClienteRepository($db);
    $clienteController = new ClienteController($repository);
    $dashboardController = new DashboardController($repository);
    $landingController = new LandingController();

    // Roteamento básico
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    switch ($path) {
        case '/':
            $landingController->index();
            break;
        case '/dashboard':
            $dashboardController->index();
            break;

        case '/clientes':
            $clienteController->index();
            break;

        case '/clientes/cadastrar':
            $_SERVER['REQUEST_METHOD'] === 'POST'
                ? $clienteController->armazena($_POST)
                : $clienteController->cria();
            break;

        case (preg_match('|^/clientes/editar/\d+$|', $path) ? true : false):
            $id = (int) basename($path);
            $_SERVER['REQUEST_METHOD'] === 'POST'
                ? $clienteController->atualiza($id, $_POST)
                : $clienteController->edita($id);
            break;

        case (preg_match('|^/clientes/deletar/\d+$|', $path) ? true : false):
            $id = (int) basename($path);
            $clienteController->deleta($id);
            break;

        default:
            // Rota não encontrada
            http_response_code(404);
            if (file_exists(BASE_PATH . '/views/errors/404.php')) {
                require BASE_PATH . '/views/errors/404.php';
            } else {
                die('Página não encontrada');
            }
            break;
    }
} catch (Exception $e) {
    // Log do erro
    error_log('ERRO: ' . $e->getMessage());

    // Página de erro
    http_response_code(500);
    if (file_exists(BASE_PATH . '/views/errors/500.php')) {
        require BASE_PATH . '/views/errors/500.php';
    } else {
        die('Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.');
    }
}
