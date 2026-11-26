<?php

// Esta classe abstrata define o contrato das enttidades no repsitorio principal.
// Ela guarda a conexão PDO utilizada pelas entidades.
// Cada repositório implementa os métodos definidos nesta base.
abstract class Repository {
    protected PDO $conexao;

    public function __construct(PDO $conexao) {
        $this->conexao = $conexao;
    }

    abstract public function criarTabela(): void;
    abstract public function inserir(object $objeto): int;
    abstract public function buscarPorId(int $id): ?array;
    abstract public function atualizar(int $id, object $objeto): bool;
    abstract public function remover(int $id): bool;
    abstract public function listarTodos(): array;
}
