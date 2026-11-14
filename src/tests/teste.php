<?php

require_once __DIR__ . '/../BancoDados.php';
require_once __DIR__ . '/../model/Classificacao.php';
require_once __DIR__ . '/../model/Autor.php';
require_once __DIR__ . '/../model/Convidado.php';
require_once __DIR__ . '/../model/Divulgacao.php';
require_once __DIR__ . '/../model/TipoPublicacao.php';
require_once __DIR__ . '/../model/Publicacao.php';

require_once __DIR__ . '/../repository/ClassificacaoRepository.php';
require_once __DIR__ . '/../repository/AutorRepository.php';
require_once __DIR__ . '/../repository/ConvidadoRepository.php';
require_once __DIR__ . '/../repository/DivulgacaoRepository.php';
require_once __DIR__ . '/../repository/TipoPublicacaoRepository.php';
require_once __DIR__ . '/../repository/PublicacaoRepository.php';

try {
    $banco = new BancoDados();
    $pdo = $banco->pdo;
    echo "Conexão e tabelas verificadas com sucesso!<br>";

    // 1. Classificação
    $classRepo = new ClassificacaoRepository($pdo);
    $idClass = $classRepo->inserir(new Classificacao(null, "Ficção Científica", "Obras especulativas e futuristas"));

    // 2. Autor
    $autorRepo = new AutorRepository($pdo);
    $idAutor = $autorRepo->inserir(new Autor(null, "Isaac Asimov", "Autor clássico de ficção científica", $idClass));

    // 3. Tipo de Publicação
    $tipoRepo = new TipoPublicacaoRepository($pdo);
    $idTipo = $tipoRepo->inserir(new TipoPublicacao(null, "Livro", "Publicação literária impressa ou digital"));

    // 4. Convidado
    $convRepo = new ConvidadoRepository($pdo);
    $idConv = $convRepo->inserir(new Convidado(null, "Thiago dos Passos", true));

    // 5. Divulgação
    $divRepo = new DivulgacaoRepository($pdo);
    $idDiv = $divRepo->inserir(new Divulgacao(null, "2026-11-20 19:00:00", "Auditório da Biblioteca Central", $idConv));

    // 6. Publicação
    $pubRepo = new PublicacaoRepository($pdo);
    $idPub = $pubRepo->inserir(new Publicacao(
        null,
        "Fundação",
        "2026-11-14 10:00:00",
        "Primeiro volume da trilogia da Fundação",
        $idTipo,
        $idDiv,
        $idAutor
    ));

    echo "Registros inseridos com sucesso! ID Publicação: {$idPub}<br>";
    echo "<h3>Publicações Cadastradas:</h3><pre>";
    print_r($pubRepo->listarTodos());
    echo "</pre>";
} catch (Exception $e) {
    echo "Erro durante o teste: " . $e->getMessage();
}
