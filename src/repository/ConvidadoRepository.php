<?php

require_once __DIR__ . '/Repository.php';

class ConvidadoRepository extends Repository {

    public function __construct(PDO $conexao) {
        parent::__construct($conexao);
    }

    #[Override]
    public function criarTabela(): void {
        $sql = "CREATE TABLE IF NOT EXISTS CONVIDADO (
            idCONVIDADO INT PRIMARY KEY AUTO_INCREMENT,
            nome VARCHAR(255) NOT NULL,
            status_convite TINYINT(1) NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $this->conexao->exec($sql);
    }

    #[Override]
    public function inserir(object $convidado): int {
        $sql = "INSERT INTO CONVIDADO (nome, status_convite) VALUES (?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            $convidado->getNome(),
            $convidado->getStatusConvite() ? 1 : 0
        ]);
        return (int)$this->conexao->lastInsertId();
    }

    #[Override]
    public function buscarPorId(int $id): ?array {
        $sql = "SELECT * FROM CONVIDADO WHERE idCONVIDADO = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    #[Override]
    public function atualizar(int $id, object $convidado): bool {
        $sql = "UPDATE CONVIDADO SET nome = ?, status_convite = ? WHERE idCONVIDADO = ?";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([
            $convidado->getNome(),
            $convidado->getStatusConvite() ? 1 : 0,
            $id
        ]);
    }

    #[Override]
    public function remover(int $id): bool {
        $sql = "DELETE FROM CONVIDADO WHERE idCONVIDADO = ?";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([$id]);
    }

    #[Override]
    public function listarTodos(): array {
        $sql = "SELECT * FROM CONVIDADO ORDER BY idCONVIDADO DESC";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
