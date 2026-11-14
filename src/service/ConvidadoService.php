<?php

require_once __DIR__ . '/../repository/ConvidadoRepository.php';
require_once __DIR__ . '/../model/Convidado.php';

class ConvidadoService {
    private ConvidadoRepository $repository;

    public function __construct(ConvidadoRepository $repository) {
        $this->repository = $repository;
    }

    public function criarTabela(): void {
        $this->repository->criarTabela();
    }

    public function cadastrar(Convidado $convidado): int {
        if (empty(trim($convidado->getNome()))) {
            throw new InvalidArgumentException("O nome do convidado é obrigatório!");
        }
        return $this->repository->inserir($convidado);
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
            throw new RuntimeException("Convidado não encontrado!");
        }
        return $registro;
    }

    public function atualizar(int $id, Convidado $convidado): bool {
        $this->buscarPorId($id);
        if (empty(trim($convidado->getNome()))) {
            throw new InvalidArgumentException("O nome do convidado é obrigatório!");
        }
        return $this->repository->atualizar($id, $convidado);
    }

    public function remover(int $id): bool {
        $this->buscarPorId($id);
        return $this->repository->remover($id);
    }
}
