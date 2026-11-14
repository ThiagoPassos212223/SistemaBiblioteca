<?php

require_once __DIR__ . '/../service/AutorService.php';
require_once __DIR__ . '/../service/ClassificacaoService.php';
require_once __DIR__ . '/../model/Autor.php';

class AutorController {
    private AutorService $autorService;
    private ?ClassificacaoService $classificacaoService;

    public function __construct(AutorService $autorService, ?ClassificacaoService $classificacaoService = null) {
        $this->autorService = $autorService;
        $this->classificacaoService = $classificacaoService;
    }

    private function querJson(): bool {
        return (isset($_GET['formato']) && $_GET['formato'] === 'json')
            || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
    }

    public function listar(): void {
        try {
            $autores = $this->autorService->listarTodos();
            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($autores);
                return;
            }
            require __DIR__ . '/../viewer/autor/index.php';
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $autores = [];
            require __DIR__ . '/../viewer/autor/index.php';
        }
    }

    public function buscarPorId(int $id): void {
        try {
            $autor = $this->autorService->buscarPorId($id);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($autor);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode(["erro" => $e->getMessage()]);
        }
    }

    public function formulario(?int $id = null): void {
        $autor = null;
        $erro = null;
        $classificacoes = $this->classificacaoService ? $this->classificacaoService->listarTodos() : [];
        if ($id !== null) {
            try {
                $autor = $this->autorService->buscarPorId($id);
            } catch (Exception $e) {
                $erro = $e->getMessage();
            }
        }
        require __DIR__ . '/../viewer/autor/form.php';
    }

    public function cadastrar(): void {
        try {
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $idClassificacao = !empty($_POST['idCLASSIFICACAO']) ? (int)$_POST['idCLASSIFICACAO'] : null;

            $novoAutor = new Autor(null, $nome, $descricao, $idClassificacao);
            $id = $this->autorService->cadastrar($novoAutor);

            if ($this->querJson()) {
                http_response_code(201);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Autor cadastrado com sucesso!", "id" => $id]);
                return;
            }
            header('Location: index.php?entidade=autor&acao=listar&msg=cadastrado');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $classificacoes = $this->classificacaoService ? $this->classificacaoService->listarTodos() : [];
            $autor = [
                'nome' => $_POST['nome'] ?? '',
                'descricao' => $_POST['descricao'] ?? '',
                'idCLASSIFICACAO' => $_POST['idCLASSIFICACAO'] ?? null
            ];
            require __DIR__ . '/../viewer/autor/form.php';
        }
    }

    public function atualizar(int $id): void {
        try {
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $idClassificacao = !empty($_POST['idCLASSIFICACAO']) ? (int)$_POST['idCLASSIFICACAO'] : null;

            $autorObj = new Autor($id, $nome, $descricao, $idClassificacao);
            $this->autorService->atualizar($id, $autorObj);

            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Autor atualizado com sucesso!"]);
                return;
            }
            header('Location: index.php?entidade=autor&acao=listar&msg=atualizado');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            $erro = $e->getMessage();
            $classificacoes = $this->classificacaoService ? $this->classificacaoService->listarTodos() : [];
            $autor = [
                'idAUTOR' => $id,
                'nome' => $_POST['nome'] ?? '',
                'descricao' => $_POST['descricao'] ?? '',
                'idCLASSIFICACAO' => $_POST['idCLASSIFICACAO'] ?? null
            ];
            require __DIR__ . '/../viewer/autor/form.php';
        }
    }

    public function remover(int $id): void {
        try {
            $this->autorService->remover($id);
            if ($this->querJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["sucesso" => "Autor removido com sucesso!"]);
                return;
            }
            header('Location: index.php?entidade=autor&acao=listar&msg=removido');
            exit;
        } catch (Exception $e) {
            if ($this->querJson()) {
                http_response_code(400);
                echo json_encode(["erro" => $e->getMessage()]);
                return;
            }
            header('Location: index.php?entidade=autor&acao=listar&erro=' . urlencode($e->getMessage()));
            exit;
        }
    }
}
