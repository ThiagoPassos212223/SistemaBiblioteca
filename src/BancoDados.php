<?php

class BancoDados {
    public string $host = "localhost";
    public string $username = "root";
    public string $password = "";
    public string $dbname = "biblioteca";
    public PDO $pdo;

    public function __construct() {
        // executa a função que garante que a DATABASE exista de forma automática, sem depender de configuração manual.
        $this->criarBanco();

        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            $this->criarTabelas();
        } catch (PDOException $e) {
            throw new PDOException("Erro na conexão com o banco de dados: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    /* nessa parte, ela cria uma conexão temporária com o banco de dados.
    durante a primeira execução do script php, a DATABASE necessária pode não existir. Para evitar a necessidade de criação manual, a função criarBanco cria essa conexão e cria a DATABASE se ela não existir.
    */
    private function criarBanco(): void {
        try {
            $pdoTemp = new PDO(
                "mysql:host={$this->host};charset=utf8mb4",
                $this->username,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $pdoTemp->exec("CREATE DATABASE IF NOT EXISTS `{$this->dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        } catch (PDOException $e) {
            throw new PDOException("Erro ao criar o banco de dados: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    // cria todas as tabelas necessárias no banco de dados, caso elas não existam. Dispensando configuração manual
    public function criarTabelas(): void {
        // temos um array que armazena os comandos que serão fornecidos ao banco. Como os comandos são string, esse é um array que armazena apenas strings.
        $queries = [
            "CREATE TABLE IF NOT EXISTS TIPO_PUBLICACAO (
                idTIPO_PUBLICACAO INT PRIMARY KEY AUTO_INCREMENT,
                nome VARCHAR(255) NOT NULL,
                descricao VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            "CREATE TABLE IF NOT EXISTS CLASSIFICACAO (
                idCLASSIFICACAO INT PRIMARY KEY AUTO_INCREMENT,
                nome VARCHAR(255) NOT NULL,
                descricao VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            "CREATE TABLE IF NOT EXISTS CONVIDADO (
                idCONVIDADO INT PRIMARY KEY AUTO_INCREMENT,
                nome VARCHAR(255) NOT NULL,
                status_convite TINYINT(1) NOT NULL DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            "CREATE TABLE IF NOT EXISTS DIVULGACAO (
                idDIVULGACAO INT PRIMARY KEY AUTO_INCREMENT,
                idCONVIDADO INT NULL,
                data DATETIME NOT NULL,
                endereco VARCHAR(255) NOT NULL,
                CONSTRAINT fk_divulgacao_convidado FOREIGN KEY (idCONVIDADO) REFERENCES CONVIDADO (idCONVIDADO) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            "CREATE TABLE IF NOT EXISTS AUTOR (
                idAUTOR INT PRIMARY KEY AUTO_INCREMENT,
                idCLASSIFICACAO INT NULL,
                nome VARCHAR(255) NOT NULL,
                descricao VARCHAR(255) NOT NULL,
                CONSTRAINT fk_autor_classificacao FOREIGN KEY (idCLASSIFICACAO) REFERENCES CLASSIFICACAO (idCLASSIFICACAO) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            "CREATE TABLE IF NOT EXISTS PUBLICACAO (
                idPUBLICACAO INT PRIMARY KEY AUTO_INCREMENT,
                idTIPO_PUBLICACAO INT NULL,
                idDIVULGACAO INT NULL,
                idAUTOR INT NULL,
                titulo VARCHAR(255) NOT NULL,
                data_publicacao DATETIME NOT NULL,
                descricao VARCHAR(255) NOT NULL,
                CONSTRAINT fk_publicacao_tipo FOREIGN KEY (idTIPO_PUBLICACAO) REFERENCES TIPO_PUBLICACAO (idTIPO_PUBLICACAO) ON DELETE SET NULL,
                CONSTRAINT fk_publicacao_divulgacao FOREIGN KEY (idDIVULGACAO) REFERENCES DIVULGACAO (idDIVULGACAO) ON DELETE SET NULL,
                CONSTRAINT fk_publicacao_autor FOREIGN KEY (idAUTOR) REFERENCES AUTOR (idAUTOR) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        ];

        // usamos o foreach para percorrer o array de strings, pegando cada string e a executando
        foreach ($queries as $sql) {
            $this->pdo->exec($sql);
        }
    }
}
