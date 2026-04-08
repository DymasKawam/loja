<?php
require_once '../auth.php';
exigir_papel('admin');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vincular Produto a Fornecedor</title>
  <link rel="stylesheet" href="../estilo.css">
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
  <style>
    .ts-wrapper .ts-control { font-size: 0.925rem; border: 1px solid var(--borda, #d1d5db); border-radius: 6px; padding: 0.45rem 0.65rem; background: var(--fundo-input, #fff); }
    .ts-wrapper.focus .ts-control { border-color: var(--primario, #6366f1); box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
    .ts-dropdown { border: 1px solid var(--borda, #d1d5db); border-radius: 6px; font-size: 0.925rem; }
    .ts-dropdown .option.selected, .ts-dropdown .option:hover { background: var(--primario, #6366f1); color: #fff; }
  </style>
</head>
<body>

<nav class="navbar">
  <a href="../index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>
  <div class="nav-links">
    <a href="../cliente/cadastrar.php">Clientes</a>
    <a href="../produto/cadastrar.php">Produtos</a>
    <a href="../vendedor/cadastrar.php">Vendedores</a>
    <a href="../fornecedor/cadastrar.php" class="ativo">Fornecedores</a>
    <a href="../estoque/entrada.php">Estoque</a>
    <a href="../venda/vender.php">Vendas</a>
  </div>
</nav>

<?php
include("../conexao.php"); // Conecta ao banco para buscar fornecedores e produtos
?>

<div class="container">

  <a href="../index.php" class="link-voltar">← Início</a>

  <div class="card">
    <div class="card-topo">
      <h2>🔗 Vincular Produto a Fornecedor</h2>
      <p>Use quando um fornecedor já cadastrado passou a fornecer um produto novo</p>
    </div>

    <?php
    // Mostra mensagem de sucesso se veio redirecionado do salvar_ligacao.php
    if (isset($_GET['msg']) && $_GET['msg'] === 'sucesso') {
        echo '<div class="alerta alerta-sucesso">✅ Produto vinculado ao fornecedor com sucesso!</div>';
    }
    ?>

    <!-- Formulário de vínculo — envia para salvar_ligacao.php -->
    <form action="salvar_ligacao.php" method="POST">

      <!-- Select de fornecedor -->
      <div class="form-grupo">
        <label class="form-label" for="id_fornecedor">Fornecedor</label>
        <select class="form-control" id="id_fornecedor" name="id_fornecedor" required>
          <option value="">— Selecione o fornecedor —</option>
          <?php
          // Busca todos os fornecedores para popular o select
          foreach ($pdo->query("SELECT id, nome FROM fornecedor ORDER BY nome") as $f):
          ?>
            <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Select de produto -->
      <div class="form-grupo">
        <label class="form-label" for="id_produto">Produto</label>
        <select class="form-control" id="id_produto" name="id_produto" required>
          <option value="">— Selecione o produto —</option>
          <?php
          // Busca todos os produtos para popular o select
          foreach ($pdo->query("SELECT id, nome FROM produto ORDER BY nome") as $p):
          ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Campo de preço de compra -->
      <div class="form-grupo">
        <label class="form-label" for="preco_compra">Preço de compra (R$)</label>
        <input class="form-control" type="number" id="preco_compra" name="preco_compra"
          step="0.01" min="0" placeholder="0.00" required>
      </div>

      <div class="form-acoes">
        <button type="submit" class="btn btn-primario">🔗 Vincular</button>
        <a href="../index.html" class="btn btn-contorno">Cancelar</a>
      </div>

    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
  const tsOpts = { placeholder: '— Digite para buscar —', allowEmptyOption: true };
  new TomSelect('#id_fornecedor', tsOpts);
  new TomSelect('#id_produto',    tsOpts);
</script>

</body>
</html>
