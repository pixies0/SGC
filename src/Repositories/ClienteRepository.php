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

    public function contarTotalClientes(string $periodo): int
    {
        [$inicio, $fim] = $this->gerarFiltroPeriodo($periodo);

        $stmt = $this->db->prepare("
        SELECT COUNT(*) FROM clientes
        WHERE data_cadastro BETWEEN :inicio AND :fim
        ");

        $stmt->execute([':inicio' => $inicio, ':fim' => $fim]);
        return (int) $stmt->fetchColumn();
    }


    public function contarClientesPorClasse(int $classe, string $periodo): int
    {
        [$inicio, $fim] = $this->gerarFiltroPeriodo($periodo);

        $sql = "SELECT COUNT(*) FROM clientes WHERE data_cadastro BETWEEN :inicio AND :fim AND ";
        switch ($classe) {
            case Cliente::CLASSE_A:
                $sql .= "renda_familiar <= " . Cliente::LIMITE_CLASSE_A;
                break;
            case Cliente::CLASSE_B:
                $sql .= "renda_familiar > " . Cliente::LIMITE_CLASSE_A .
                    " AND renda_familiar <= " . Cliente::LIMITE_CLASSE_B;
                break;
            case Cliente::CLASSE_C:
                $sql .= "renda_familiar > " . Cliente::LIMITE_CLASSE_B;
                break;
            default:
                $sql .= "renda_familiar IS NULL";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':inicio' => $inicio, ':fim' => $fim]);
        return (int) $stmt->fetchColumn();
    }


    public function calcularMediaRenda(string $periodo): float
    {
        [$inicio, $fim] = $this->gerarFiltroPeriodo($periodo);

        $stmt = $this->db->prepare("
        SELECT AVG(renda_familiar)
        FROM clientes
        WHERE renda_familiar IS NOT NULL AND data_cadastro BETWEEN :inicio AND :fim
    ");
        $stmt->execute([':inicio' => $inicio, ':fim' => $fim]);

        return round((float) $stmt->fetchColumn(), 2);
    }


    public function calcularMediaIdade(string $periodo): float
    {
        [$inicio, $fim] = $this->gerarFiltroPeriodo($periodo);

        $stmt = $this->db->prepare("
        SELECT AVG(EXTRACT(YEAR FROM AGE(CURRENT_DATE, data_nascimento)))
        FROM clientes
        WHERE data_cadastro BETWEEN :inicio AND :fim
        ");

        $stmt->execute([':inicio' => $inicio, ':fim' => $fim]);

        return round((float) $stmt->fetchColumn(), 1);
    }


    public function contarClientesMaior18ComRendaAcimaDaMedia(string $periodo): int
    {
        [$inicio, $fim] = $this->gerarFiltroPeriodo($periodo);
        $mediaRenda = $this->calcularMediaRenda($periodo);

        $stmt = $this->db->prepare("
        SELECT COUNT(*)
        FROM clientes
        WHERE EXTRACT(YEAR FROM AGE(CURRENT_DATE, data_nascimento)) >= 18
          AND renda_familiar > :media_renda
          AND data_cadastro BETWEEN :inicio AND :fim
        ");

        $stmt->execute([
            ':media_renda' => $mediaRenda,
            ':inicio' => $inicio,
            ':fim' => $fim
        ]);

        return (int) $stmt->fetchColumn();
    }


    private function gerarFiltroPeriodo(string $periodo): array
    {
        $hoje = new DateTime();

        switch ($periodo) {
            case 'hoje':
                $inicio = (clone $hoje)->setTime(0, 0, 0);
                $fim = (clone $hoje)->setTime(23, 59, 59);
                break;

            case 'semana':
                // Pega segunda-feira da semana atual
                $inicio = (clone $hoje)->modify('monday this week')->setTime(0, 0, 0);
                $fim = (clone $hoje)->modify('sunday this week')->setTime(23, 59, 59);
                break;

            case 'mes':
            default:
                $inicio = (new DateTime('first day of this month'))->setTime(0, 0, 0);
                $fim = (clone $hoje)->setTime(23, 59, 59);
        }

        return [$inicio->format('Y-m-d'), $fim->format('Y-m-d')];
    }

    public function listarTodosComFiltroPeriodo(string $periodo): array
    {
        [$inicio, $fim] = $this->gerarFiltroPeriodo($periodo);

        $sql = "SELECT * FROM clientes
            WHERE data_cadastro BETWEEN :inicio AND :fim
            ORDER BY nome";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':inicio' => $inicio, ':fim' => $fim]);

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
    }


    public function calcularMediaRendaPorFaixaEtaria(string $periodo): array
    {
        $clientes = $this->listarTodosComFiltroPeriodo($periodo);

        $faixas = [
            '18-30' => [],
            '31-45' => [],
            '46+'   => []
        ];

        foreach ($clientes as $cliente) {
            $idade = $cliente->getIdade();
            $renda = $cliente->getRendaFamiliar();

            if ($idade === null || $renda === null) {
                continue;
            }

            if ($idade >= 18 && $idade <= 30) {
                $faixas['18-30'][] = $renda;
            } elseif ($idade >= 31 && $idade <= 45) {
                $faixas['31-45'][] = $renda;
            } elseif ($idade > 45) {
                $faixas['46+'][] = $renda;
            }
        }

        $medias = [];

        foreach ($faixas as $faixa => $rendas) {
            $medias[$faixa] = count($rendas) > 0
                ? round(array_sum($rendas) / count($rendas), 2)
                : 0;
        }

        return $medias;
    }

    public function contarClientesAcimaAbaixoMedia(string $periodo): array
    {
        $clientes = $this->listarTodosComFiltroPeriodo($periodo);

        $rendas = array_filter(array_map(function ($c) {
            return $c->getRendaFamiliar();
        }, $clientes));

        if (count($rendas) === 0) {
            return ['acima' => 0, 'abaixo' => 0];
        }

        $media = array_sum($rendas) / count($rendas);

        $acima = 0;
        $abaixo = 0;

        foreach ($clientes as $cliente) {
            $renda = $cliente->getRendaFamiliar();

            if ($renda === null) {
                continue;
            }

            if ($renda > $media) {
                $acima++;
            } else {
                $abaixo++;
            }
        }

        return [
            'acima' => $acima,
            'abaixo' => $abaixo
        ];
    }
}
