<?php

require_once __DIR__ . '/../repository/PublicacaoRepository.php';
require_once __DIR__ . '/../model/Publicacao.php';

class PublicacaoService {
    private PublicacaoRepository $repository;

    public function __construct(PublicacaoRepository $repository) {
        $this->repository = $repository;
    }

    public function criarTabela(): void {
        $this->repository->criarTabela();
    }

    public function cadastrar(Publicacao $publicacao): int {
        if (empty(trim($publicacao->getTitulo()))) {
            throw new InvalidArgumentException("O título da publicação é obrigatório!");
        }
        if (empty(trim($publicacao->getDataPublicacao()))) {
            throw new InvalidArgumentException("A data da publicação é obrigatória!");
        }
        if (empty(trim($publicacao->getDescricao()))) {
            throw new InvalidArgumentException("A descrição da publicação é obrigatória!");
        }
        return $this->repository->inserir($publicacao);
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
            throw new RuntimeException("Publicação não encontrada!");
        }
        return $registro;
    }

    public function atualizar(int $id, Publicacao $publicacao): bool {
        $this->buscarPorId($id);
        if (empty(trim($publicacao->getTitulo()))) {
            throw new InvalidArgumentException("O título da publicação é obrigatório!");
        }
        if (empty(trim($publicacao->getDataPublicacao()))) {
            throw new InvalidArgumentException("A data da publicação é obrigatória!");
        }
        if (empty(trim($publicacao->getDescricao()))) {
            throw new InvalidArgumentException("A descrição da publicação é obrigatória!");
        }
        return $this->repository->atualizar($id, $publicacao);
    }

    public function remover(int $id): bool {
        $this->buscarPorId($id);
        return $this->repository->remover($id);
    }
}
