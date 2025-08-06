<?php

namespace App\Controllers;

use Exception;
use App\Models\Cliente;  // Adicione esta linha para importar a classe Cliente
use App\Repositories\ClienteRepository;

class DashboardController
{
    private ClienteRepository $repository;

    public function __construct(ClienteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        try {
            $dashboardData = [
                'total' => $this->repository->contarTotalClientes(),
                'classe_a' => $this->repository->contarClientesPorClasse(Cliente::CLASSE_A),
                'classe_b' => $this->repository->contarClientesPorClasse(Cliente::CLASSE_B),
                'classe_c' => $this->repository->contarClientesPorClasse(Cliente::CLASSE_C),
                'media_renda' => $this->repository->calcularMediaRenda(),
                'media_idade' => $this->repository->calcularMediaIdade(),
                'maior18_acima_media' => $this->repository->contarClientesMaior18ComRendaAcimaDaMedia()
            ];


            // Restante do código permanece igual
            $view = BASE_PATH . '/views/dashboard/index.php';
            $layout = BASE_PATH . '/views/layout.php';

            if (file_exists($view)) {
                require $layout;
            } else {
                die("View não encontrada no caminho: " . $view);
            }
        } catch (Exception $e) {
            error_log('Erro no dashboard: ' . $e->getMessage());
            header('Location: /?erro=Erro+ao+carregar+dashboard');
            exit;
        }
    }
}
