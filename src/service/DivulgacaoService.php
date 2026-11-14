<?php

require_once __DIR__ . '/../repository/DivulgacaoRepository.php';
require_once __DIR__ . '/../model/Divulgacao.php';

class DivulgacaoService {
    private DivulgacaoRepository $repository;

    public function __construct(DivulgacaoRepository $repository) {
        $this->repository = $repository;
    }

    public function criarTabela(): void {
        $this->repository->criarTabela();
    }

    public function cadastrar(Divulgacao $divulgacao): int {
        if (empty(trim($divulgacao->getData()))) {
            throw new InvalidArgumentException("A data da divulgação é obrigatória!");
        }
        if (empty(trim($divulgacao->getEndereco()))) {
            throw new InvalidArgumentException("O endereço da divulgação é obrigatório!");
        }
        return $this->repository->inserir($divulgacao);
    }

    public function listarTodos(): array {
        return $this->repository->listarTodos();
    }

    public function buscarPorId(int $id): array {
        if ($id <= 0) {
            throw new InvalidArgumentException("O ID fornecido é inválido!");
        }
        $registro = $this->repository->buscarPorId($id);
        if (!$registro) {
            throw new RuntimeException("Divulgação não encontrada!");
        }
        return $registro;
    }

    public function atualizar(int $id, Divulgacao $divulgacao): bool {
        $this->buscarPorId($id);
        if (empty(trim($divulgacao->getData()))) {
            throw new InvalidArgumentException("A data da divulgação é obrigatória!");
        }
        if (empty(trim($divulgacao->getEndereco()))) {
            throw new InvalidArgumentException("O endereço da divulgação é obrigatório!");
        }
        return $this->repository->atualizar($id, $divulgacao);
    }

    public function remover(int $id): bool {
        $this->buscarPorId($id);
        return $this->repository->remover($id);
    }
}
