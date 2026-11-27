<?php

require_once __DIR__ . '/../repository/ClassificacaoRepository.php';
require_once __DIR__ . '/../model/Classificacao.php';

class ClassificacaoService {
    private ClassificacaoRepository $repository;

    public function __construct(ClassificacaoRepository $repository) {
        // atribuição repository
        $this->repository = $repository;
    }

    public function criarTabela(): void {
        $this->repository->criarTabela();
    }

    public function cadastrar(Classificacao $classificacao): int {
        if (empty(trim($classificacao->getNome()))) {
            throw new InvalidArgumentException("O nome da classificação é obrigatório!");
        }
        if (empty(trim($classificacao->getDescricao()))) {
            throw new InvalidArgumentException("A descrição da classificação é obrigatória!");
        }
        return $this->repository->inserir($classificacao);
    }

    public function listarTodos(): array {
        return $this->repository->listarTodos();
    }

    public function buscarPorId(int $id): array {
        // comparação usando o operador de comparação
        if ($id <= 0) {
            throw new InvalidArgumentException("O ID fornecido é inválido!");
        }
        $registro = $this->repository->buscarPorId($id);

        // uso do operador lógico not(!)
        if (!$registro) {
            throw new RuntimeException("Classificação não encontrada!");
        }
        return $registro;
    }

    public function atualizar(int $id, Classificacao $classificacao): bool {
        $this->buscarPorId($id);
        if (empty(trim($classificacao->getNome()))) {
            throw new InvalidArgumentException("O nome da classificação é obrigatório!");
        }
        if (empty(trim($classificacao->getDescricao()))) {
            throw new InvalidArgumentException("A descrição da classificação é obrigatória!");
        }
        return $this->repository->atualizar($id, $classificacao);
    }

    public function remover(int $id): bool {
        $this->buscarPorId($id);
        return $this->repository->remover($id);
    }
}
