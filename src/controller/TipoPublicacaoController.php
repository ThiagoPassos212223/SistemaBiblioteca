<?php

require_once __DIR__ . '/../service/TipoPublicacaoService.php';
require_once __DIR__ . '/../model/TipoPublicacao.php';

class TipoPublicacaoController {
    private TipoPublicacaoService $service;

    public function __construct(TipoPublicacaoService $service) {
        $this->service = $service;
    }

    private function querJson(): bool {
        return (isset($_GET['formato']) && $_GET['formato'] === 'json')
            || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
    }

    public function listar(): void {
        try {
            $tipos = $this->service->listarTodos();
            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($tipos);
                return;
            }
            require __DIR__ . '/../viewer/tipo_publicacao/index.php';
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $tipos = [];
            require __DIR__ . '/../viewer/tipo_publicacao/index.php';
        }
    }

    public function buscarPorId(int $id): void {
        try {
            $tipo = $this->service->buscarPorId($id);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($tipo);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode(["erro" => $e->getMessage()]);
        }
    }

    public function formulario(?int $id = null): void {
        $tipo = null;
        $erro = null;
        if ($id !== null) {
            try {
                $tipo = $this->service->buscarPorId($id);
            } catch (Exception $e) {
                $erro = $e->getMessage();
            }
        }
        require __DIR__ . '/../viewer/tipo_publicacao/form.php';
    }

    public function cadastrar(): void {
        try {
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';

            $novo = new TipoPublicacao(null, $nome, $descricao);
            $id = $this->service->cadastrar($novo);

            if ($this->querJson()) {
                http_response_code(201);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Tipo de publicação cadastrado com sucesso!", "id" => $id]);
                return;
            }
            header('Location: index.php?entidade=tipo_publicacao&acao=listar&msg=cadastrado');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $tipo = ['nome' => $_POST['nome'] ?? '', 'descricao' => $_POST['descricao'] ?? ''];
            require __DIR__ . '/../viewer/tipo_publicacao/form.php';
        }
    }

    public function atualizar(int $id): void {
        try {
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';

            $obj = new TipoPublicacao($id, $nome, $descricao);
            $this->service->atualizar($id, $obj);

            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Tipo de publicação atualizado com sucesso!"]);
                return;
            }
            header('Location: index.php?entidade=tipo_publicacao&acao=listar&msg=atualizado');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $tipo = ['idTIPO_PUBLICACAO' => $id, 'nome' => $_POST['nome'] ?? '', 'descricao' => $_POST['descricao'] ?? ''];
            require __DIR__ . '/../viewer/tipo_publicacao/form.php';
        }
    }

    public function remover(int $id): void {
        try {
            $this->service->remover($id);
            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Tipo de publicação removido com sucesso!"]);
                return;
            }
            header('Location: index.php?entidade=tipo_publicacao&acao=listar&msg=removido');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            header('Location: index.php?entidade=tipo_publicacao&acao=listar&erro=' . urlencode($e->getMessage()));
            exit;
        }
    }
}
