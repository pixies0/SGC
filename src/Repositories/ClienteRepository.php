<?php

namespace App\Repositories;

use PDO;
use DateTime;
use Exception;
use PDOException;

use App\Models\Cliente;

class ClienteRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getConnection(): PDO
    {
        return $this->db;
    }

    public function salvar(Cliente $cliente): bool
    {
        try {
            if ($cliente->getId() === null) {
                return $this->inserir($cliente);
            } else {
                return $this->atualizar($cliente);
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    private function inserir(Cliente $cliente): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO public.clientes
                (nome, cpf, data_nascimento, data_cadastro, renda_familiar)
            VALUES
                (:nome, :cpf, :data_nascimento, :data_cadastro, :renda_familiar)
            RETURNING id
        ");

        $sucesso = $stmt->execute([
            ':nome' => $cliente->getNome(),
            ':cpf' => $cliente->getCpf(),
            ':data_nascimento' => $cliente->getDataNascimento()->format('Y-m-d'),
            ':data_cadastro' => $cliente->getDataCadastro()->format('Y-m-d'),
            ':renda_familiar' => $cliente->getRendaFamiliar()
        ]);

        if ($sucesso) {
            $cliente->setId($this->db->lastInsertId());
            return true;
        }

        return false;
    }

    private function atualizar(Cliente $cliente): bool
    {
        $stmt = $this->db->prepare("
            UPDATE public.clientes SET
                nome = :nome,
                cpf = :cpf,
                data_nascimento = :data_nascimento,
                renda_familiar = :renda_familiar
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $cliente->getId(),
            ':nome' => $cliente->getNome(),
            ':cpf' => $cliente->getCpf(),
            ':data_nascimento' => $cliente->getDataNascimento()->format('Y-m-d'),
            ':renda_familiar' => $cliente->getRendaFamiliar()
        ]);
    }

    public function buscarPorId(int $id): ?Cliente
    {
        $stmt = $this->db->prepare("
            SELECT * FROM public.clientes WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            return null;
        }

        return $this->mapearParaObjeto($dados);
    }

    public function listarTodos(?string $termoBusca = null): array
    {
        try {
            $sql = "SELECT id, nome, cpf, data_nascimento, data_cadastro, renda_familiar
                FROM clientes";

            $params = [];

            if ($termoBusca) {
                $sql .= " WHERE nome ILIKE :termo";
                $params[':termo'] = '%' . $termoBusca . '%';
            }

            $sql .= " ORDER BY nome";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            $clientes = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                try {
                    $clientes[] = new Cliente(
                        $row['nome'],
                        $row['cpf'],
                        new DateTime($row['data_nascimento']),
                        $row['renda_familiar'],
                        $row['id'],
                        new DateTime($row['data_cadastro'])
                    );
                } catch (Exception $e) {
                    error_log("Erro ao criar cliente ID {$row['id']}: " . $e->getMessage());
                    continue;
                }
            }

            return $clientes;
        } catch (Exception $e) {
            error_log('Erro no repositório: ' . $e->getMessage());
            throw new Exception('Erro ao carregar lista de clientes');
        }
    }

    public function excluir(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM clientes WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();

            if ($stmt->rowCount() === 0) {
                error_log("Nenhum registro encontrado para excluir (ID: $id)");
                return false;
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Erro no SQL: " . $e->getMessage());
            error_log("Query: DELETE FROM clientes WHERE id = $id");
            return false;
        }
    }

    public function buscarPorCpf(string $cpf): ?Cliente
    {
        $stmt = $this->db->prepare("
            SELECT * FROM public.clientes WHERE cpf = :cpf
        ");
        $stmt->execute([':cpf' => $cpf]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            return null;
        }

        return $this->mapearParaObjeto($dados);
    }

    private function mapearParaObjeto(array $dados): Cliente
    {
        return new Cliente(
            $dados['nome'],
            $dados['cpf'],
            new DateTime($dados['data_nascimento']),
            $dados['renda_familiar'],
            $dados['id'],
            new DateTime($dados['data_cadastro'])
        );
    }
}
