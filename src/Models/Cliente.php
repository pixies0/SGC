<?php

namespace App\Models;

use DateTime;
use Exception;

class Cliente
{
    private ?int $id;
    private string $nome;
    private string $cpf;
    private DateTime $dataNascimento;
    private DateTime $dataCadastro;
    private ?float $rendaFamiliar;

    // Constantes para classes de renda
    public const CLASSE_A = 1;
    public const CLASSE_B = 2;
    public const CLASSE_C = 3;
    public const LIMITE_CLASSE_A = 980.00;
    public const LIMITE_CLASSE_B = 2500.00;

    public function __construct(
        string $nome,
        string $cpf,
        DateTime $dataNascimento,
        ?float $rendaFamiliar = null,
        ?int $id = null,
        ?DateTime $dataCadastro = null
    ) {
        $this->setId($id);
        $this->setNome($nome);
        $this->setCpf($cpf);
        $this->setDataNascimento($dataNascimento);
        $this->setRendaFamiliar($rendaFamiliar);
        $this->dataCadastro = $dataCadastro ?? new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function getDataNascimento(): DateTime
    {
        return $this->dataNascimento;
    }

    public function getIdade(): ?int
    {
        if (!$this->dataNascimento) {
            return null;
        }

        $hoje = new DateTime();
        return $this->dataNascimento->diff($hoje)->y;
    }


    public function getDataCadastro(): DateTime
    {
        return $this->dataCadastro;
    }

    public function getRendaFamiliar(): ?float
    {
        return $this->rendaFamiliar;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setNome(string $nome): void
    {
        if (strlen($nome) > 150) {
            throw new Exception("Nome deve ter no máximo 150 caracteres");
        }
        $this->nome = $nome;
    }

    public function setCpf(string $cpf): void
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) !== 11) {
            throw new Exception('CPF deve conter exatamente 11 dígitos');
        }

        if (!ctype_digit($cpf)) {
            throw new Exception('CPF deve conter apenas dígitos');
        }

        if (!self::validarCPF($cpf)) {
            throw new Exception('CPF inválido');
        }

        $this->cpf = $cpf;
    }


    public function setDataNascimento(DateTime $dataNascimento): void
    {
        if ($dataNascimento > new DateTime()) {
            throw new Exception("Data de nascimento não pode ser no futuro");
        }
        $this->dataNascimento = $dataNascimento;
    }

    public function setRendaFamiliar(?float $rendaFamiliar): void
    {
        if ($rendaFamiliar !== null && $rendaFamiliar < 0) {
            throw new Exception("Renda familiar não pode ser negativa");
        }
        $this->rendaFamiliar = $rendaFamiliar;
    }

    public function getClasseRenda(): string
    {
        if ($this->rendaFamiliar === null) {
            return '';
        }

        if ($this->rendaFamiliar <= self::LIMITE_CLASSE_A) {
            return 'badge-classe-a';
        } elseif ($this->rendaFamiliar <= self::LIMITE_CLASSE_B) {
            return 'badge-classe-b';
        } else {
            return 'badge-classe-c';
        }
    }

    public function getRendaFormatada(): string
    {
        if ($this->rendaFamiliar === null) {
            return '-';
        }

        return number_format($this->rendaFamiliar, 2, ',', '.');
    }

    public function isMaiorDeIdade(): bool
    {
        $hoje = new DateTime();
        $diferenca = $this->dataNascimento->diff($hoje);
        return $diferenca->y >= 18;
    }

    public static function validarCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Verifica se tem 11 dígitos ou se é uma sequência repetida
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}
