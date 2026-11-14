<?php

require_once __DIR__ . '/../service/DivulgacaoService.php';
require_once __DIR__ . '/../service/ConvidadoService.php';
require_once __DIR__ . '/../model/Divulgacao.php';

class DivulgacaoController {
    private DivulgacaoService $service;
    private ?ConvidadoService $convidadoService;

    public function __construct(DivulgacaoService $service, ?ConvidadoService $convidadoService = null) {
        $this->service = $service;
        $this->convidadoService = $convidadoService;
    }

    private function querJson(): bool {
        return (isset($_GET['formato']) && $_GET['formato'] === 'json')
            || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
    }

    private function normalizarDataHora(string $valor): string {
        $valor = trim($valor);
        if ($valor === '') return '';
        return str_replace('T', ' ', $valor) . (strlen($valor) === 16 ? ':00' : '');
    }

    public function listar(): void {
        try {
            $divulgacoes = $this->service->listarTodos();
            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($divulgacoes);
                return;
            }
            require __DIR__ . '/../viewer/divulgacao/index.php';
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $divulgacoes = [];
            require __DIR__ . '/../viewer/divulgacao/index.php';
        }
    }

    public function buscarPorId(int $id): void {
        try {
            $divulgacao = $this->service->buscarPorId($id);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($divulgacao);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode(["erro" => $e->getMessage()]);
        }
    }

    public function formulario(?int $id = null): void {
        $divulgacao = null;
        $erro = null;
        $convidados = $this->convidadoService ? $this->convidadoService->listarTodos() : [];
        if ($id !== null) {
            try {
                $divulgacao = $this->service->buscarPorId($id);
            } catch (Exception $e) {
                $erro = $e->getMessage();
            }
        }
        require __DIR__ . '/../viewer/divulgacao/form.php';
    }

    public function cadastrar(): void {
        try {
            $data = $this->normalizarDataHora($_POST['data'] ?? '');
            $endereco = $_POST['endereco'] ?? '';
            $idConvidado = !empty($_POST['idCONVIDADO']) ? (int)$_POST['idCONVIDADO'] : null;

            $nova = new Divulgacao(null, $data, $endereco, $idConvidado);
            $id = $this->service->cadastrar($nova);

            if ($this->querJson()) {
                http_response_code(201);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Divulgação cadastrada com sucesso!", "id" => $id]);
                return;
            }
            header('Location: index.php?entidade=divulgacao&acao=listar&msg=cadastrado');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $convidados = $this->convidadoService ? $this->convidadoService->listarTodos() : [];
            $divulgacao = [
                'data' => $_POST['data'] ?? '',
                'endereco' => $_POST['endereco'] ?? '',
                'idCONVIDADO' => $_POST['idCONVIDADO'] ?? null
            ];
            require __DIR__ . '/../viewer/divulgacao/form.php';
        }
    }

    public function atualizar(int $id): void {
        try {
            $data = $this->normalizarDataHora($_POST['data'] ?? '');
            $endereco = $_POST['endereco'] ?? '';
            $idConvidado = !empty($_POST['idCONVIDADO']) ? (int)$_POST['idCONVIDADO'] : null;

            $obj = new Divulgacao($id, $data, $endereco, $idConvidado);
            $this->service->atualizar($id, $obj);

            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Divulgação atualizada com sucesso!"]);
                return;
            }
            header('Location: index.php?entidade=divulgacao&acao=listar&msg=atualizado');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $convidados = $this->convidadoService ? $this->convidadoService->listarTodos() : [];
            $divulgacao = [
                'idDIVULGACAO' => $id,
                'data' => $_POST['data'] ?? '',
                'endereco' => $_POST['endereco'] ?? '',
                'idCONVIDADO' => $_POST['idCONVIDADO'] ?? null
            ];
            require __DIR__ . '/../viewer/divulgacao/form.php';
        }
    }

    public function remover(int $id): void {
        try {
            $this->service->remover($id);
            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Divulgação removida com sucesso!"]);
                return;
            }
            header('Location: index.php?entidade=divulgacao&acao=listar&msg=removido');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            header('Location: index.php?entidade=divulgacao&acao=listar&erro=' . urlencode($e->getMessage()));
            exit;
        }
    }
}
