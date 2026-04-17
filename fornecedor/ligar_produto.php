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
    // Verifica se a URL tem ?msg=sucesso para mostrar mensagem de confirmação. Isso acontece depois de um cadastro bem-sucedido, quando salvar_ligacao.php redireciona de volta aqui.
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
          <!-- Cada opção do select é preenchida com o nome do fornecedor e o valor é o ID do fornecedor. htmlspecialchars é usado para evitar problemas de segurança ao exibir o nome do fornecedor, garantindo que caracteres especiais sejam tratados corretamente. -->
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

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script> <!-- Importa a biblioteca Tom Select para transformar os selects de fornecedor e produto em campos de busca mais amigáveis, permitindo que o usuário digite para filtrar as opções disponíveis, melhorando a usabilidade especialmente quando há muitos fornecedores ou produtos cadastrados. -->
<script>
  // Define as opções para o Tom Select, incluindo um placeholder que orienta o usuário a digitar para buscar e a opção allowEmptyOption que permite limpar a seleção se necessário. Essas opções configuram o comportamento dos campos de busca, tornando-os mais intuitivos e fáceis de usar.
  const tsOpts = { placeholder: '— Digite para buscar —', allowEmptyOption: true };
  // Inicializa o Tom Select nos selects de fornecedor e produto, aplicando as opções definidas em tsOpts para configurar o comportamento dos campos de busca. Isso transforma os selects tradicionais em componentes interativos que facilitam a seleção, especialmente em casos onde há uma grande quantidade de opções. O placeholder orienta o usuário a digitar para buscar, e allowEmptyOption permite que o campo seja limpo se necessário.
  new TomSelect('#id_fornecedor', tsOpts);
  new TomSelect('#id_produto',    tsOpts);
</script>

</body>
</html>
