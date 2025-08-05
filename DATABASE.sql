CREATE TABLE clientes (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    data_nascimento DATE NOT NULL,
    data_cadastro DATE NOT NULL DEFAULT CURRENT_DATE,
    renda_familiar DECIMAL(10,2)
);

-- Inserção de dados de teste
INSERT INTO clientes (nome, cpf, data_nascimento, renda_familiar) VALUES
('João Silva', '12345678901', '1990-05-15', 850.00),
('Maria Oliveira', '98765432109', '1985-08-22', 1200.50);