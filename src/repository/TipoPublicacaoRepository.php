<?php

// Este repositório gerencia tiposs de publicação no banco.
// Ele cria tabelas e executa operações completas de CRUD.
// Os métodos usam PDO e identificadores inteiros nos parâmetros.
require_once __DIR__ . '/Repository.php';

class TipoPublicacaoRepository extends Repository {

    public function __construct(PDO $conexao) {
        parent::__construct($conexao);
    }

    #[Override]
    public function criarTabela(): void {
        $sql = "CREATE TABLE IF NOT EXISTS TIPO_PUBLICACAO (
            idTIPO_PUBLICACAO INT PRIMARY KEY AUTO_INCREMENT,
            nome VARCHAR(255) NOT NULL,
            descricao VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $this->conexao->exec($sql);
    }

    #[Override]
    public function inserir(object $tipoPublicacao): int {
        $sql = "INSERT INTO TIPO_PUBLICACAO (nome, descricao) VALUES (?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            $tipoPublicacao->getNome(),
            $tipoPublicacao->getDescricao()
        ]);
        return (int)$this->conexao->lastInsertId();
    }

    #[Override]
    public function buscarPorId(int $id): ?array {
        $sql = "SELECT * FROM TIPO_PUBLICACAO WHERE idTIPO_PUBLICACAO = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    #[Override]
    public function atualizar(int $id, object $tipoPublicacao): bool {
        $sql = "UPDATE TIPO_PUBLICACAO SET nome = ?, descricao = ? WHERE idTIPO_PUBLICACAO = ?";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([
            $tipoPublicacao->getNome(),
            $tipoPublicacao->getDescricao(),
            $id
        ]);
    }

    #[Override]
    public function remover(int $id): bool {
        $sql = "DELETE FROM TIPO_PUBLICACAO WHERE idTIPO_PUBLICACAO = ?";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([$id]);
    }

    #[Override]
    public function listarTodos(): array {
        $sql = "SELECT * FROM TIPO_PUBLICACAO ORDER BY idTIPO_PUBLICACAO DESC";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
