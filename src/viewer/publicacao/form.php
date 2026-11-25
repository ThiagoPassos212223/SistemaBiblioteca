<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= !empty($publicacao['idPUBLICACAO']) ? 'Editar' : 'Nova' ?> Publicação</title>
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
        <h1><?= !empty($publicacao['idPUBLICACAO']) ? 'Editar Publicação' : 'Cadastrar Publicação' ?></h1>
        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php
            $action = !empty($publicacao['idPUBLICACAO']) ? "index.php?entidade=publicacao&acao=atualizar&id=" . (int)$publicacao['idPUBLICACAO'] : "index.php?entidade=publicacao&acao=cadastrar";
            $dataFormatada = !empty($publicacao['data_publicacao']) ? date('Y-m-d\TH:i', strtotime($publicacao['data_publicacao'])) : '';
        ?>
        <form method="POST" action="<?= $action ?>">
            <div class="form-group">
                <label for="titulo">Título *</label>
                <input type="text" id="titulo" name="titulo" required value="<?= htmlspecialchars($publicacao['titulo'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="data_publicacao">Data da Publicação *</label>
                <input type="datetime-local" id="data_publicacao" name="data_publicacao" required value="<?= htmlspecialchars($dataFormatada) ?>">
            </div>
            <div class="form-group">
                <label for="idAUTOR">Autor</label>
                <select id="idAUTOR" name="idAUTOR">
                    <option value="">-- Selecione --</option>
                    <?php foreach ($autores as $a): ?>
                        <option value="<?= (int)$a['idAUTOR'] ?>" <?= (isset($publicacao['idAUTOR']) && (int)$publicacao['idAUTOR'] === (int)$a['idAUTOR']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="idTIPO_PUBLICACAO">Tipo de Publicação</label>
                <select id="idTIPO_PUBLICACAO" name="idTIPO_PUBLICACAO">
                    <option value="">-- Selecione --</option>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?= (int)$t['idTIPO_PUBLICACAO'] ?>" <?= (isset($publicacao['idTIPO_PUBLICACAO']) && (int)$publicacao['idTIPO_PUBLICACAO'] === (int)$t['idTIPO_PUBLICACAO']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="idDIVULGACAO">Divulgação</label>
                <select id="idDIVULGACAO" name="idDIVULGACAO">
                    <option value="">-- Selecione --</option>
                    <?php foreach ($divulgacoes as $d): ?>
                        <option value="<?= (int)$d['idDIVULGACAO'] ?>" <?= (isset($publicacao['idDIVULGACAO']) && (int)$publicacao['idDIVULGACAO'] === (int)$d['idDIVULGACAO']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d['endereco'] . ' (' . $d['data'] . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição *</label>
                <textarea id="descricao" name="descricao" rows="3" required><?= htmlspecialchars($publicacao['descricao'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="index.php?entidade=publicacao&acao=listar" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
