<?php

namespace App\Controllers;

use PDO;
use PDOException;
use DateTime;
use Exception;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;


class ClienteController
{
    private ClienteRepository $repository;

    public function __construct(ClienteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        try {
            $clientes = $this->repository->listarTodos();

            $view = BASE_PATH . '/views/clientes/listar.php';
            $layout = BASE_PATH . '/views/layout.php';

            if (file_exists($view)) {
                require $layout;
            } else {
                die("View não encontrada: " . $view);
            }
        } catch (Exception $e) {
            error_log('Erro na listagem de clientes: ' . $e->getMessage());
            header('Location: /clientes?erro=Ocorreu+um+erro+ao+carregar+os+clientes');
            exit;
        }
    }

    public function cria()
    {
        $view = BASE_PATH . '/views/clientes/cadastrar.php';
        $layout = BASE_PATH . '/views/layout.php';

        if (file_exists($view)) {
            require $layout;
        } else {
            die("View não encontrada: " . $view);
        }
    }

    public function armazena(array $dados)
    {
        try {
            $cpf = preg_replace('/[^0-9]/', '', $dados['cpf']);

            if (empty($dados['nome'])) {
                throw new Exception('O nome é obrigatório');
            }

            if (empty($cpf)) {
                throw new Exception('O CPF é obrigatório');
            }

            if (!Cliente::validarCpf($cpf)) {
                throw new Exception('CPF inválido. Digite 11 números válidos');
            }

            // Validação de CPF duplicado
            if ($this->repository->buscarPorCpf($cpf) !== null) {
                throw new Exception('Já existe um cliente cadastrado com este CPF.');
            }

            if (empty($dados['data_nascimento'])) {
                throw new Exception('A data de nascimento é obrigatória');
            }

            // Cria e salva o cliente
            $cliente = new Cliente(
                $dados['nome'],
                $cpf,
                new DateTime($dados['data_nascimento']),
                !empty($dados['renda_familiar']) ? (float)$dados['renda_familiar'] : null
            );

            if ($this->repository->salvar($cliente)) {
                header('Location: /clientes/cadastrar?sucesso=Cliente+cadastrado+com+sucesso');
                exit;
            }

            throw new Exception('Erro ao salvar no banco de dados');
        } catch (Exception $e) {
            // Preserva os dados do formulário em caso de erro
            $_SESSION['form_data'] = $dados;
            header('Location: /clientes/cadastrar?erro=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function edita(int $id)
    {
        $cliente = $this->repository->buscarPorId($id);
        if (!$cliente) {
            header('Location: /clientes?erro=Cliente+não+encontrado');
            exit;
        }

        $view = BASE_PATH . '/views/clientes/editar.php';
        $layout = BASE_PATH . '/views/layout.php';

        if (file_exists($view)) {
            require $layout;
        } else {
            die("View não encontrada: " . $view);
        }
    }

    public function atualiza(int $id, array $dados)
    {
        try {
            $cliente = $this->repository->buscarPorId($id);
            if (!$cliente) {
                throw new Exception('Cliente não encontrado');
            }

            $cliente->setNome($dados['nome']);
            $cliente->setCpf(preg_replace('/[^0-9]/', '', $dados['cpf']));
            $cliente->setDataNascimento(new DateTime($dados['data_nascimento']));
            $cliente->setRendaFamiliar(!empty($dados['renda_familiar']) ? (float)$dados['renda_familiar'] : null);

            if ($this->repository->salvar($cliente)) {
                header('Location: /clientes/editar/' . $id . '?sucesso=Cliente+atualizado+com+sucesso');
            } else {
                throw new Exception('Erro ao atualizar cliente');
            }
        } catch (Exception $e) {
            header('Location: /clientes/editar/' . $id . '?erro=' . urlencode($e->getMessage()));
        }
        exit;
    }

    public function deleta(int $id)
    {
        try {
            if ($this->repository->excluir($id)) {
                header('Location: /clientes?sucesso=Cliente+excluído+com+sucesso');
            } else {
                header('Location: /clientes?erro=Não+foi+possível+excluir+o+cliente');
            }
        } catch (PDOException $e) {
            error_log('Erro ao excluir cliente: ' . $e->getMessage());
            header('Location: /clientes?erro=Erro+ao+excluir+cliente');
        }
        exit;
    }
}
