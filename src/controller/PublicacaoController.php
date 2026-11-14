<?php

require_once __DIR__ . '/../service/PublicacaoService.php';
require_once __DIR__ . '/../service/TipoPublicacaoService.php';
require_once __DIR__ . '/../service/DivulgacaoService.php';
require_once __DIR__ . '/../service/AutorService.php';
require_once __DIR__ . '/../model/Publicacao.php';

class PublicacaoController {
    private PublicacaoService $service;
    private ?TipoPublicacaoService $tipoService;
    private ?DivulgacaoService $divulgacaoService;
    private ?AutorService $autorService;

    public function __construct(
        PublicacaoService $service,
        ?TipoPublicacaoService $tipoService = null,
        ?DivulgacaoService $divulgacaoService = null,
        ?AutorService $autorService = null
    ) {
        $this->service = $service;
        $this->tipoService = $tipoService;
        $this->divulgacaoService = $divulgacaoService;
        $this->autorService = $autorService;
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
            $publicacoes = $this->service->listarTodos();
            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($publicacoes);
                return;
            }
            require __DIR__ . '/../viewer/publicacao/index.php';
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $publicacoes = [];
            require __DIR__ . '/../viewer/publicacao/index.php';
        }
    }

    public function buscarPorId(int $id): void {
        try {
            $publicacao = $this->service->buscarPorId($id);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($publicacao);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode(["erro" => $e->getMessage()]);
        }
    }

    public function formulario(?int $id = null): void {
        $publicacao = null;
        $erro = null;
        $tipos = $this->tipoService ? $this->tipoService->listarTodos() : [];
        $divulgacoes = $this->divulgacaoService ? $this->divulgacaoService->listarTodos() : [];
        $autores = $this->autorService ? $this->autorService->listarTodos() : [];

        if ($id !== null) {
            try {
                $publicacao = $this->service->buscarPorId($id);
            } catch (Exception $e) {
                $erro = $e->getMessage();
            }
        }
        require __DIR__ . '/../viewer/publicacao/form.php';
    }

    public function cadastrar(): void {
        try {
            $titulo = $_POST['titulo'] ?? '';
            $dataPublicacao = $this->normalizarDataHora($_POST['data_publicacao'] ?? '');
            $descricao = $_POST['descricao'] ?? '';
            $idTipo = !empty($_POST['idTIPO_PUBLICACAO']) ? (int)$_POST['idTIPO_PUBLICACAO'] : null;
            $idDivulgacao = !empty($_POST['idDIVULGACAO']) ? (int)$_POST['idDIVULGACAO'] : null;
            $idAutor = !empty($_POST['idAUTOR']) ? (int)$_POST['idAUTOR'] : null;

            $nova = new Publicacao(null, $titulo, $dataPublicacao, $descricao, $idTipo, $idDivulgacao, $idAutor);
            $id = $this->service->cadastrar($nova);

            if ($this->querJson()) {
                http_response_code(201);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Publicação cadastrada com sucesso!", "id" => $id]);
                return;
            }
            header('Location: index.php?entidade=publicacao&acao=listar&msg=cadastrado');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $tipos = $this->tipoService ? $this->tipoService->listarTodos() : [];
            $divulgacoes = $this->divulgacaoService ? $this->divulgacaoService->listarTodos() : [];
            $autores = $this->autorService ? $this->autorService->listarTodos() : [];
            $publicacao = [
                'titulo' => $_POST['titulo'] ?? '',
                'data_publicacao' => $_POST['data_publicacao'] ?? '',
                'descricao' => $_POST['descricao'] ?? '',
                'idTIPO_PUBLICACAO' => $_POST['idTIPO_PUBLICACAO'] ?? null,
                'idDIVULGACAO' => $_POST['idDIVULGACAO'] ?? null,
                'idAUTOR' => $_POST['idAUTOR'] ?? null
            ];
            require __DIR__ . '/../viewer/publicacao/form.php';
        }
    }

    public function atualizar(int $id): void {
        try {
            $titulo = $_POST['titulo'] ?? '';
            $dataPublicacao = $this->normalizarDataHora($_POST['data_publicacao'] ?? '');
            $descricao = $_POST['descricao'] ?? '';
            $idTipo = !empty($_POST['idTIPO_PUBLICACAO']) ? (int)$_POST['idTIPO_PUBLICACAO'] : null;
            $idDivulgacao = !empty($_POST['idDIVULGACAO']) ? (int)$_POST['idDIVULGACAO'] : null;
            $idAutor = !empty($_POST['idAUTOR']) ? (int)$_POST['idAUTOR'] : null;

            $obj = new Publicacao($id, $titulo, $dataPublicacao, $descricao, $idTipo, $idDivulgacao, $idAutor);
            $this->service->atualizar($id, $obj);

            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Publicação atualizada com sucesso!"]);
                return;
            }
            header('Location: index.php?entidade=publicacao&acao=listar&msg=atualizado');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $tipos = $this->tipoService ? $this->tipoService->listarTodos() : [];
            $divulgacoes = $this->divulgacaoService ? $this->divulgacaoService->listarTodos() : [];
            $autores = $this->autorService ? $this->autorService->listarTodos() : [];
            $publicacao = [
                'idPUBLICACAO' => $id,
                'titulo' => $_POST['titulo'] ?? '',
                'data_publicacao' => $_POST['data_publicacao'] ?? '',
                'descricao' => $_POST['descricao'] ?? '',
                'idTIPO_PUBLICACAO' => $_POST['idTIPO_PUBLICACAO'] ?? null,
                'idDIVULGACAO' => $_POST['idDIVULGACAO'] ?? null,
                'idAUTOR' => $_POST['idAUTOR'] ?? null
            ];
            require __DIR__ . '/../viewer/publicacao/form.php';
        }
    }

    public function remover(int $id): void {
        try {
            $this->service->remover($id);
            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Publicação removida com sucesso!"]);
                return;
            }
            header('Location: index.php?entidade=publicacao&acao=listar&msg=removido');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            header('Location: index.php?entidade=publicacao&acao=listar&erro=' . urlencode($e->getMessage()));
            exit;
        }
    }
}
