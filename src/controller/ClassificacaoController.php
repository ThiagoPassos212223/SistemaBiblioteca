<?php

require_once __DIR__ . '/../service/ClassificacaoService.php';
require_once __DIR__ . '/../model/Classificacao.php';

class ClassificacaoController {
    private ClassificacaoService $service;

    public function __construct(ClassificacaoService $service) {
        $this->service = $service;
    }

    private function querJson(): bool {
        return (isset($_GET['formato']) && $_GET['formato'] === 'json')
            || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
    }

    public function listar(): void {
        try {
            $classificacoes = $this->service->listarTodos();
            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($classificacoes);
                return;
            }
            require __DIR__ . '/../viewer/classificacao/index.php';
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $classificacoes = [];
            require __DIR__ . '/../viewer/classificacao/index.php';
        }
    }

    public function buscarPorId(int $id): void {
        try {
            $classificacao = $this->service->buscarPorId($id);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($classificacao);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode(["erro" => $e->getMessage()]);
        }
    }

    public function formulario(?int $id = null): void {
        $classificacao = null;
        $erro = null;
        if ($id !== null) {
            try {
                $classificacao = $this->service->buscarPorId($id);
            } catch (Exception $e) {
                $erro = $e->getMessage();
            }
        }
        require __DIR__ . '/../viewer/classificacao/form.php';
    }

    public function cadastrar(): void {
        try {
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $nova = new Classificacao(null, $nome, $descricao);
            $id = $this->service->cadastrar($nova);

            if ($this->querJson()) {
                http_response_code(201);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Classificação cadastrada com sucesso!", "id" => $id]);
                return;
            }
            header('Location: index.php?entidade=classificacao&acao=listar&msg=cadastrado');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $classificacao = ['nome' => $_POST['nome'] ?? '', 'descricao' => $_POST['descricao'] ?? ''];
            require __DIR__ . '/../viewer/classificacao/form.php';
        }
    }

    public function atualizar(int $id): void {
        try {
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $obj = new Classificacao($id, $nome, $descricao);
            $this->service->atualizar($id, $obj);

            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Classificação atualizada com sucesso!"]);
                return;
            }
            header('Location: index.php?entidade=classificacao&acao=listar&msg=atualizado');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $classificacao = ['idCLASSIFICACAO' => $id, 'nome' => $_POST['nome'] ?? '', 'descricao' => $_POST['descricao'] ?? ''];
            require __DIR__ . '/../viewer/classificacao/form.php';
        }
    }

    public function remover(int $id): void {
        try {
            $this->service->remover($id);
            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Classificação removida com sucesso!"]);
                return;
            }
            header('Location: index.php?entidade=classificacao&acao=listar&msg=removido');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            header('Location: index.php?entidade=classificacao&acao=listar&erro=' . urlencode($e->getMessage()));
            exit;
        }
    }
}
