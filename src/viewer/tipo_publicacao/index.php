<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Tipos de Publicação - Biblioteca</title>
    <style>
    body { font-family: Arial, sans-serif; margin: 0; background: #f8fafc; color: #0f172a; }
    .container { max-width: 960px; margin: 24px auto; padding: 20px; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; }
    h1 { margin-top: 0; font-size: 1.5rem; }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    th, td { border: 1px solid #cbd5e1; padding: 8px 10px; text-align: left; }
    th { background: #f1f5f9; }
    .btn { display: inline-block; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.9rem; border: none; cursor: pointer; }
    .btn-primary { background: #2563eb; color: #fff; }
    .btn-warning { background: #d97706; color: #fff; }
    .btn-danger { background: #dc2626; color: #fff; }
    .btn-secondary { background: #64748b; color: #fff; }
    .alert { padding: 10px 14px; border-radius: 4px; margin-bottom: 16px; }
    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
    .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .form-group { margin-bottom: 14px; }
    label { display: block; font-weight: bold; margin-bottom: 4px; }
    input[type="text"], input[type="datetime-local"], select, textarea { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; }
    .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-top: 16px; }
    .card { border: 1px solid #cbd5e1; border-radius: 6px; padding: 16px; background: #f8fafc; }
    .card h3 { margin: 0 0 8px 0; font-size: 1.1rem; }
    .card .num { font-size: 1.8rem; font-weight: bold; color: #2563eb; margin-bottom: 10px; }
</style>
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
        <h1>Tipos de Publicação</h1>
        <?php if (!empty($_GET['msg'])): ?>
            <div class="alert alert-success">Operação realizada com sucesso (<?= htmlspecialchars($_GET['msg']) ?>).</div>
        <?php endif; ?>
        <?php if (!empty($_GET['erro']) || !empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro'] ?? $erro) ?></div>
        <?php endif; ?>

        <a href="index.php?entidade=tipo_publicacao&acao=formulario" class="btn btn-primary">Novo Tipo de Publicação</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tipos)): ?>
                    <tr><td colspan="4">Nenhum tipo de publicação cadastrado.</td></tr>
                <?php else: ?>
                    <?php foreach ($tipos as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)$item['idTIPO_PUBLICACAO']) ?></td>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td><?= htmlspecialchars($item['descricao']) ?></td>
                            <td>
                                <a class="btn btn-warning" href="index.php?entidade=tipo_publicacao&acao=formulario&id=<?= (int)$item['idTIPO_PUBLICACAO'] ?>">Editar</a>
                                <a class="btn btn-danger" href="index.php?entidade=tipo_publicacao&acao=remover&id=<?= (int)$item['idTIPO_PUBLICACAO'] ?>" onclick="return confirm('Deseja remover este tipo?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
