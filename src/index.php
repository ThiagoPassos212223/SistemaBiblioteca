<?php
// configurando a exibição de erros
ini_set('display_errors', '1');
error_reporting(E_ALL);

// importando a interface com o banco de dados
require_once __DIR__ . '/BancoDados.php';

// importando os models (camada responsável por representar os dados)
require_once __DIR__ . '/model/Classificacao.php';
require_once __DIR__ . '/model/Autor.php';
require_once __DIR__ . '/model/Convidado.php';
require_once __DIR__ . '/model/Divulgacao.php';
require_once __DIR__ . '/model/TipoPublicacao.php';
require_once __DIR__ . '/model/Publicacao.php';

// importando os repositorios (camada responsável por interagir com banco de dados)
require_once __DIR__ . '/repository/Repository.php';
require_once __DIR__ . '/repository/ClassificacaoRepository.php';
require_once __DIR__ . '/repository/AutorRepository.php';
require_once __DIR__ . '/repository/ConvidadoRepository.php';
require_once __DIR__ . '/repository/DivulgacaoRepository.php';
require_once __DIR__ . '/repository/TipoPublicacaoRepository.php';
require_once __DIR__ . '/repository/PublicacaoRepository.php';

// importando os services (camada responsável por aplicar as regras de negócio e utilizar os comandos dos repositórios)
require_once __DIR__ . '/service/ClassificacaoService.php';
require_once __DIR__ . '/service/AutorService.php';
require_once __DIR__ . '/service/ConvidadoService.php';
require_once __DIR__ . '/service/DivulgacaoService.php';
require_once __DIR__ . '/service/TipoPublicacaoService.php';
require_once __DIR__ . '/service/PublicacaoService.php';

// importando os controllers (camada responsável por realizar a relação model e viewer)
require_once __DIR__ . '/controller/HomeController.php';
require_once __DIR__ . '/controller/ClassificacaoController.php';
require_once __DIR__ . '/controller/AutorController.php';
require_once __DIR__ . '/controller/ConvidadoController.php';
require_once __DIR__ . '/controller/DivulgacaoController.php';
require_once __DIR__ . '/controller/TipoPublicacaoController.php';
require_once __DIR__ . '/controller/PublicacaoController.php';

// tratamento de exceções
try {
    // criação da conexão de um banco de dados utilizando PDO
    $banco = new BancoDados();
    $pdo = $banco->pdo;

    // Instanciando Repositories
    $classificacaoRepo = new ClassificacaoRepository($pdo);
    $autorRepo = new AutorRepository($pdo);
    $convidadoRepo = new ConvidadoRepository($pdo);
    $divulgacaoRepo = new DivulgacaoRepository($pdo);
    $tipoPublicacaoRepo = new TipoPublicacaoRepository($pdo);
    $publicacaoRepo = new PublicacaoRepository($pdo);

    // Instanciando Services
    $classificacaoService = new ClassificacaoService($classificacaoRepo);
    $autorService = new AutorService($autorRepo);
    $convidadoService = new ConvidadoService($convidadoRepo);
    $divulgacaoService = new DivulgacaoService($divulgacaoRepo);
    $tipoPublicacaoService = new TipoPublicacaoService($tipoPublicacaoRepo);
    $publicacaoService = new PublicacaoService($publicacaoRepo);

    // ?? = indica para o PHP realizar a checagem se o valor é null, caso seja, define que o valor é igual a uma string vazia
    // pegando as informações enviadas de um formulário que postou as informações por meio do método GET

    $entidade = $_GET['entidade'] ?? 'home';
    
    $acao = $_GET['acao'] ?? ($entidade === 'home' ? 'index' : 'listar');
    
    // usando isset para verificar se o valor é null
    // usando um operador ternário para definir o valor do $id
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

    // uso de match para definir qual é o valor que será recebido pelo controller e assim, o controller correto será selecionado.
    // isso ocorre com base na entidade que foi escolhida no index.html na viewer
    $controller = match ($entidade) {
        'home' => new HomeController(
            $publicacaoService,
            $autorService,
            $classificacaoService,
            $tipoPublicacaoService,
            $divulgacaoService,
            $convidadoService
        ),
        'autor' => new AutorController($autorService, $classificacaoService),
        'classificacao' => new ClassificacaoController($classificacaoService),
        'convidado' => new ConvidadoController($convidadoService),
        'divulgacao' => new DivulgacaoController($divulgacaoService, $convidadoService),
        'tipo_publicacao' => new TipoPublicacaoController($tipoPublicacaoService),
        'publicacao' => new PublicacaoController(
            $publicacaoService,
            $tipoPublicacaoService,
            $divulgacaoService,
            $autorService
        ),
        default => null
    };

    // utilização do if para uma comparação
    if ($controller === null) {
        http_response_code(404);
        echo "Entidade não encontrada.";
        exit;
    }


    // uso de switch para determinar qual ação será  
    switch ($acao) {
        case 'index':
            if (method_exists($controller, 'index')) {
                $controller->index();
            } else {
                $controller->listar();
            }
            break;
        case 'listar':
            $controller->listar();
            break;
        case 'buscar':
            if ($id === null) {
                http_response_code(400);
                echo json_encode(["erro" => "Parâmetro 'id' é obrigatório."]);
                exit;
            }
            $controller->buscarPorId($id);
            break;
        case 'formulario':
            $controller->formulario($id);
            break;
        case 'cadastrar':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->cadastrar();
            } else {
                $controller->formulario(null);
            }
            break;
        case 'atualizar':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null) {
                $controller->atualizar($id);
            } elseif ($id !== null) {
                $controller->formulario($id);
            }
            break;
        case 'remover':
            if ($id === null) {
                http_response_code(400);
                echo "Parâmetro 'id' é obrigatório para remoção.";
                exit;
            }
            $controller->remover($id);
            break;
        default:
            http_response_code(404);
            echo "Ação não encontrada.";
            break;
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo "Erro interno: " . htmlspecialchars($e->getMessage());
}
