<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= !empty($tipo['idTIPO_PUBLICACAO']) ? 'Editar' : 'Novo' ?> Tipo de Publicação</title>
    <link rel="stylesheet" href="viewer/form.css">
</head>
<body>
    <nav style="background:#1e293b;padding:12px 20px;display:flex;gap:16px;flex-wrap:wrap;align-items:center;">
    <a href="index.php?entidade=home" style="color:#fff;font-weight:bold;text-decoration:none;">Biblioteca</a>
    <a href="index.php?entidade=publicacao&acao=listar" style="color:#cbd5e1;text-decoration:none;">Publicações</a>
    <a href="index.php?entidade=autor&acao=listar" style="color:#cbd5e1;text-decoration:none;">Autores</a>
    <a href="index.php?entidade=classificacao&acao=listar" style="color:#cbd5e1;text-decoration:none;">Classificações</a>
    <a href="index.php?entidade=tipo_publicacao&acao=listar" style="color:#cbd5e1;text-decoration:none;">Tipos de Publicação</a>
    <a href="index.php?entidade=divulgacao&acao=listar" style="color:#cbd5e1;text-decoration:none;">Divulgações</a>
    <a href="index.php?entidade=convidado&acao=listar" style="color:#cbd5e1;text-decoration:none;">Convidados</a>
</nav>
    <div class="container">
        <h1><?= !empty($tipo['idTIPO_PUBLICACAO']) ? 'Editar Tipo de Publicação' : 'Cadastrar Tipo de Publicação' ?></h1>
        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php $action = !empty($tipo['idTIPO_PUBLICACAO']) ? "index.php?entidade=tipo_publicacao&acao=atualizar&id=" . (int)$tipo['idTIPO_PUBLICACAO'] : "index.php?entidade=tipo_publicacao&acao=cadastrar"; ?>
        <form method="POST" action="<?= $action ?>">
            <div class="form-group">
                <label for="nome">Nome *</label>
                <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($tipo['nome'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="descricao">Descrição *</label>
                <textarea id="descricao" name="descricao" rows="3" required><?= htmlspecialchars($tipo['descricao'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="index.php?entidade=tipo_publicacao&acao=listar" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
