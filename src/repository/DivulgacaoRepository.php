<?php

require_once __DIR__ . '/Repository.php';

class DivulgacaoRepository extends Repository {

    public function __construct(PDO $conexao) {
        parent::__construct($conexao);
    }

    #[Override]
    public function criarTabela(): void {
        $sql = "CREATE TABLE IF NOT EXISTS DIVULGACAO (
            idDIVULGACAO INT PRIMARY KEY AUTO_INCREMENT,
            idCONVIDADO INT NULL,
            data DATETIME NOT NULL,
            endereco VARCHAR(255) NOT NULL,
            CONSTRAINT fk_divulgacao_convidado FOREIGN KEY (idCONVIDADO) REFERENCES CONVIDADO (idCONVIDADO) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $this->conexao->exec($sql);
    }

    #[Override]
    public function inserir(object $divulgacao): int {
        $sql = "INSERT INTO DIVULGACAO (idCONVIDADO, data, endereco) VALUES (?, ?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            $divulgacao->getIdConvidado(),
            $divulgacao->getData(),
            $divulgacao->getEndereco()
        ]);
        return (int)$this->conexao->lastInsertId();
    }

    #[Override]
    public function buscarPorId(int $id): ?array {
        $sql = "SELECT d.*, c.nome AS convidado_nome
                FROM DIVULGACAO d
                LEFT JOIN CONVIDADO c ON d.idCONVIDADO = c.idCONVIDADO
                WHERE d.idDIVULGACAO = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    #[Override]
    public function atualizar(int $id, object $divulgacao): bool {
        $sql = "UPDATE DIVULGACAO SET idCONVIDADO = ?, data = ?, endereco = ? WHERE idDIVULGACAO = ?";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([
            $divulgacao->getIdConvidado(),
            $divulgacao->getData(),
            $divulgacao->getEndereco(),
            $id
        ]);
    }

    #[Override]
    public function remover(int $id): bool {
        $sql = "DELETE FROM DIVULGACAO WHERE idDIVULGACAO = ?";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([$id]);
    }

    #[Override]
    public function listarTodos(): array {
        $sql = "SELECT d.*, c.nome AS convidado_nome
                FROM DIVULGACAO d
                LEFT JOIN CONVIDADO c ON d.idCONVIDADO = c.idCONVIDADO
                ORDER BY d.idDIVULGACAO DESC";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
