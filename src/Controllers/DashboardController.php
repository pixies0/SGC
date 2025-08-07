<?php

namespace App\Controllers;

use Exception;
use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use App\Helper\View;

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
            $periodo = $_GET['periodo'] ?? 'mes';

            $dashboardData = [
                'total' => $this->repository->contarTotalClientes($periodo),
                'classe_a' => $this->repository->contarClientesPorClasse(Cliente::CLASSE_A, $periodo),
                'classe_b' => $this->repository->contarClientesPorClasse(Cliente::CLASSE_B, $periodo),
                'classe_c' => $this->repository->contarClientesPorClasse(Cliente::CLASSE_C, $periodo),
                'media_renda' => $this->repository->calcularMediaRenda($periodo),
                'media_idade' => $this->repository->calcularMediaIdade($periodo),
                'maior18_acima_media' => $this->repository->contarClientesMaior18ComRendaAcimaDaMedia($periodo),

                // Dados para os gráficos
                'media_renda_faixa_etaria' => $this->repository->calcularMediaRendaPorFaixaEtaria($periodo),
                'clientes_renda_acima_abaixo' => $this->repository->contarClientesAcimaAbaixoMedia($periodo)
            ];

            View::render('dashboard/index.php', [
                'dashboardData' => $dashboardData,
            ]);
        } catch (Exception $e) {
            error_log('Erro no dashboard: ' . $e->getMessage());
            header('Location: /?erro=Erro+ao+carregar+dashboard');
            exit;
        }
    }
}
