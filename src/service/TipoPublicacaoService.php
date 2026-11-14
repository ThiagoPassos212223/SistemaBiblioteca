<?php

require_once __DIR__ . '/../repository/TipoPublicacaoRepository.php';
require_once __DIR__ . '/../model/TipoPublicacao.php';

class TipoPublicacaoService {
    private TipoPublicacaoRepository $repository;

    public function __construct(TipoPublicacaoRepository $repository) {
        $this->repository = $repository;
    }

    public function criarTabela(): void {
        $this->repository->criarTabela();
    }

    public function cadastrar(TipoPublicacao $tipoPublicacao): int {
        if (empty(trim($tipoPublicacao->getNome()))) {
            throw new InvalidArgumentException("O nome do tipo de publicação é obrigatório!");
        }
        if (empty(trim($tipoPublicacao->getDescricao()))) {
            throw new InvalidArgumentException("A descrição do tipo de publicação é obrigatória!");
        }
        return $this->repository->inserir($tipoPublicacao);
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
            throw new RuntimeException("Tipo de publicação não encontrado!");
        }
        return $registro;
    }

    public function atualizar(int $id, TipoPublicacao $tipoPublicacao): bool {
        $this->buscarPorId($id);
        if (empty(trim($tipoPublicacao->getNome()))) {
            throw new InvalidArgumentException("O nome do tipo de publicação é obrigatório!");
        }
        if (empty(trim($tipoPublicacao->getDescricao()))) {
            throw new InvalidArgumentException("A descrição do tipo de publicação é obrigatória!");
        }
        return $this->repository->atualizar($id, $tipoPublicacao);
    }

    public function remover(int $id): bool {
        $this->buscarPorId($id);
        return $this->repository->remover($id);
    }
}
