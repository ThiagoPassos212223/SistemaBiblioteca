<?php

require_once __DIR__ . '/Repository.php';

class PublicacaoRepository extends Repository {

    public function __construct(PDO $conexao) {
        parent::__construct($conexao);
    }

    #[Override]
    public function criarTabela(): void {
        $sql = "CREATE TABLE IF NOT EXISTS PUBLICACAO (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $this->conexao->exec($sql);
    }

    #[Override]
    public function inserir(object $publicacao): int {
        $sql = "INSERT INTO PUBLICACAO (idTIPO_PUBLICACAO, idDIVULGACAO, idAUTOR, titulo, data_publicacao, descricao)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            $publicacao->getIdTipoPublicacao(),
            $publicacao->getIdDivulgacao(),
            $publicacao->getIdAutor(),
            $publicacao->getTitulo(),
            $publicacao->getDataPublicacao(),
            $publicacao->getDescricao()
        ]);
        return (int)$this->conexao->lastInsertId();
    }

    #[Override]
    public function buscarPorId(int $id): ?array {
        $sql = "SELECT p.*,
                       tp.nome AS tipo_nome,
                       d.endereco AS divulgacao_endereco,
                       d.data AS divulgacao_data,
                       a.nome AS autor_nome
                FROM PUBLICACAO p
                LEFT JOIN TIPO_PUBLICACAO tp ON p.idTIPO_PUBLICACAO = tp.idTIPO_PUBLICACAO
                LEFT JOIN DIVULGACAO d ON p.idDIVULGACAO = d.idDIVULGACAO
                LEFT JOIN AUTOR a ON p.idAUTOR = a.idAUTOR
                WHERE p.idPUBLICACAO = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    #[Override]
    public function atualizar(int $id, object $publicacao): bool {
        $sql = "UPDATE PUBLICACAO
                SET idTIPO_PUBLICACAO = ?, idDIVULGACAO = ?, idAUTOR = ?, titulo = ?, data_publicacao = ?, descricao = ?
                WHERE idPUBLICACAO = ?";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([
            $publicacao->getIdTipoPublicacao(),
            $publicacao->getIdDivulgacao(),
            $publicacao->getIdAutor(),
            $publicacao->getTitulo(),
            $publicacao->getDataPublicacao(),
            $publicacao->getDescricao(),
            $id
        ]);
    }

    #[Override]
    public function remover(int $id): bool {
        $sql = "DELETE FROM PUBLICACAO WHERE idPUBLICACAO = ?";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([$id]);
    }

    #[Override]
    public function listarTodos(): array {
        $sql = "SELECT p.*,
                       tp.nome AS tipo_nome,
                       d.endereco AS divulgacao_endereco,
                       d.data AS divulgacao_data,
                       a.nome AS autor_nome
                FROM PUBLICACAO p
                LEFT JOIN TIPO_PUBLICACAO tp ON p.idTIPO_PUBLICACAO = tp.idTIPO_PUBLICACAO
                LEFT JOIN DIVULGACAO d ON p.idDIVULGACAO = d.idDIVULGACAO
                LEFT JOIN AUTOR a ON p.idAUTOR = a.idAUTOR
                ORDER BY p.idPUBLICACAO DESC";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
