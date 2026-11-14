<?php

require_once __DIR__ . '/../repository/AutorRepository.php';
require_once __DIR__ . '/../model/Autor.php';

class AutorService {
    private AutorRepository $autorRepository;

    public function __construct(AutorRepository $autorRepository) {
        $this->autorRepository = $autorRepository;
    }

    public function criarTabela(): void {
        $this->autorRepository->criarTabela();
    }

    public function cadastrar(Autor $autor): int {
        if (empty(trim($autor->getNome()))) {
            throw new InvalidArgumentException("O nome do autor é obrigatório!");
        }
        if (empty(trim($autor->getDescricao()))) {
            throw new InvalidArgumentException("A descrição do autor é obrigatória!");
        }
        return $this->autorRepository->inserir($autor);
    }

    public function listarTodos(): array {
        return $this->autorRepository->listarTodos();
    }

    public function buscarPorId(int $id): array {
        if ($id <= 0) {
            throw new InvalidArgumentException("O ID fornecido é inválido!");
        }
        $autor = $this->autorRepository->buscarPorId($id);
        if (!$autor) {
            throw new RuntimeException("Autor não encontrado!");
        }
        return $autor;
    }

    public function atualizar(int $id, Autor $autor): bool {
        $this->buscarPorId($id);
        if (empty(trim($autor->getNome()))) {
            throw new InvalidArgumentException("O nome do autor é obrigatório!");
        }
        if (empty(trim($autor->getDescricao()))) {
            throw new InvalidArgumentException("A descrição do autor é obrigatória!");
        }
        return $this->autorRepository->atualizar($id, $autor);
    }

    public function remover(int $id): bool {
        $this->buscarPorId($id);
        return $this->autorRepository->remover($id);
    }
}
