<?php

// Este repositório gerencia a classificacão no banco de dados.
// Ele cria tabelas, consulta registros e executa alterações.
// Os métodos utilizam prepared statements para segurança.
require_once __DIR__ . '/Repository.php';

class ClassificacaoRepository extends Repository {

    public function __construct(PDO $conexao) {
        parent::__construct($conexao);
    }

    #[Override]
    public function criarTabela(): void {
        $sql = "CREATE TABLE IF NOT EXISTS CLASSIFICACAO (
            idCLASSIFICACAO INT PRIMARY KEY AUTO_INCREMENT,
            nome VARCHAR(255) NOT NULL,
            descricao VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $this->conexao->exec($sql);
    }

    #[Override]
    public function inserir(object $classificacao): int {
        $sql = "INSERT INTO CLASSIFICACAO (nome, descricao) VALUES (?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            $classificacao->getNome(),
            $classificacao->getDescricao()
        ]);
        return (int)$this->conexao->lastInsertId();
    }

    #[Override]
    public function buscarPorId(int $id): ?array {
        $sql = "SELECT * FROM CLASSIFICACAO WHERE idCLASSIFICACAO = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    #[Override]
    public function atualizar(int $id, object $classificacao): bool {
        $sql = "UPDATE CLASSIFICACAO SET nome = ?, descricao = ? WHERE idCLASSIFICACAO = ?";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([
            $classificacao->getNome(),
            $classificacao->getDescricao(),
            $id
        ]);
    }

    #[Override]
    public function remover(int $id): bool {
        $sql = "DELETE FROM CLASSIFICACAO WHERE idCLASSIFICACAO = ?";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([$id]);
    }

    #[Override]
    public function listarTodos(): array {
        $sql = "SELECT * FROM CLASSIFICACAO ORDER BY idCLASSIFICACAO DESC";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
