<?php

require_once __DIR__ . '/../service/ConvidadoService.php';
require_once __DIR__ . '/../model/Convidado.php';

class ConvidadoController {
    private ConvidadoService $service;

    public function __construct(ConvidadoService $service) {
        $this->service = $service;
    }

    private function querJson(): bool {
        return (isset($_GET['formato']) && $_GET['formato'] === 'json')
            || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
    }

    public function listar(): void {
        try {
            $convidados = $this->service->listarTodos();
            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($convidados);
                return;
            }
            require __DIR__ . '/../viewer/convidado/index.php';
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $convidados = [];
            require __DIR__ . '/../viewer/convidado/index.php';
        }
    }

    public function buscarPorId(int $id): void {
        try {
            $convidado = $this->service->buscarPorId($id);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($convidado);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode(["erro" => $e->getMessage()]);
        }
    }

    public function formulario(?int $id = null): void {
        $convidado = null;
        $erro = null;
        if ($id !== null) {
            try {
                $convidado = $this->service->buscarPorId($id);
            } catch (Exception $e) {
                $erro = $e->getMessage();
            }
        }
        require __DIR__ . '/../viewer/convidado/form.php';
    }

    public function cadastrar(): void {
        try {
            $nome = $_POST['nome'] ?? '';
            $statusConvite = isset($_POST['status_convite']) && (bool)$_POST['status_convite'];

            $novo = new Convidado(null, $nome, $statusConvite);
            $id = $this->service->cadastrar($novo);

            if ($this->querJson()) {
                http_response_code(201);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Convidado cadastrado com sucesso!", "id" => $id]);
                return;
            }
            header('Location: index.php?entidade=convidado&acao=listar&msg=cadastrado');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $convidado = [
                'nome' => $_POST['nome'] ?? '',
                'status_convite' => isset($_POST['status_convite']) ? 1 : 0
            ];
            require __DIR__ . '/../viewer/convidado/form.php';
        }
    }

    public function atualizar(int $id): void {
        try {
            $nome = $_POST['nome'] ?? '';
            $statusConvite = isset($_POST['status_convite']) && (bool)$_POST['status_convite'];

            $obj = new Convidado($id, $nome, $statusConvite);
            $this->service->atualizar($id, $obj);

            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Convidado atualizado com sucesso!"]);
                return;
            }
            header('Location: index.php?entidade=convidado&acao=listar&msg=atualizado');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $convidado = [
                'idCONVIDADO' => $id,
                'nome' => $_POST['nome'] ?? '',
                'status_convite' => isset($_POST['status_convite']) ? 1 : 0
            ];
            require __DIR__ . '/../viewer/convidado/form.php';
        }
    }

    public function remover(int $id): void {
        try {
            $this->service->remover($id);
            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Convidado removido com sucesso!"]);
                return;
            }
            header('Location: index.php?entidade=convidado&acao=listar&msg=removido');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            header('Location: index.php?entidade=convidado&acao=listar&erro=' . urlencode($e->getMessage()));
            exit;
        }
    }
}
