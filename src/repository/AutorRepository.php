<?php

// Este repositório gerencia o autor no banco de dados.
// Ele cria a tabela, busca registros e executa CRUD.
// Os métodos usam PDO e tratam identficadores corretamente.
require_once __DIR__ . '/Repository.php';

class AutorRepository extends Repository {

    public function __construct(PDO $conexao) {
        parent::__construct($conexao);
    }

    #[Override]
    public function criarTabela(): void {
        $sql = "CREATE TABLE IF NOT EXISTS AUTOR (
            idAUTOR INT PRIMARY KEY AUTO_INCREMENT,
            idCLASSIFICACAO INT NULL,
            nome VARCHAR(255) NOT NULL,
            descricao VARCHAR(255) NOT NULL,
            CONSTRAINT fk_autor_classificacao FOREIGN KEY (idCLASSIFICACAO) REFERENCES CLASSIFICACAO (idCLASSIFICACAO) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $this->conexao->exec($sql);
    }

    #[Override]
    public function inserir(object $autor): int {
        $sql = "INSERT INTO AUTOR (idCLASSIFICACAO, nome, descricao) VALUES (?, ?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            $autor->getIdClassificacao(),
            $autor->getNome(),
            $autor->getDescricao()
        ]);
        return (int)$this->conexao->lastInsertId();
    }

    #[Override]
    public function buscarPorId(int $id): ?array {
        $sql = "SELECT a.*, c.nome AS classificacao_nome
                FROM AUTOR a
                LEFT JOIN CLASSIFICACAO c ON a.idCLASSIFICACAO = c.idCLASSIFICACAO
                WHERE a.idAUTOR = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    #[Override]
    public function atualizar(int $id, object $autor): bool {
        $sql = "UPDATE AUTOR SET idCLASSIFICACAO = ?, nome = ?, descricao = ? WHERE idAUTOR = ?";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([
            $autor->getIdClassificacao(),
            $autor->getNome(),
            $autor->getDescricao(),
            $id
        ]);
    }

    #[Override]
    public function remover(int $id): bool {
        $sql = "DELETE FROM AUTOR WHERE idAUTOR = ?";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([$id]);
    }

    #[Override]
    public function listarTodos(): array {
        $sql = "SELECT a.*, c.nome AS classificacao_nome
                FROM AUTOR a
                LEFT JOIN CLASSIFICACAO c ON a.idCLASSIFICACAO = c.idCLASSIFICACAO
                ORDER BY a.idAUTOR DESC";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
