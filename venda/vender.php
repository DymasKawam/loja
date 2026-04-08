<?php
require_once '../auth.php';
exigir_login();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Realizar Venda</title>
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
    <a href="../fornecedor/cadastrar.php">Fornecedores</a>
    <a href="../estoque/entrada.php">Estoque</a>
    <a href="../venda/vender.php" class="ativo">Vendas</a>
  </div>
</nav>

<?php
include("../conexao.php"); // Conecta ao banco para carregar produtos, clientes e vendedores

// Busca todos os produtos que têm estoque, junto com a quantidade disponível
// Só aparecem no select os produtos que ainda têm unidades para vender
$sqlProdutos = "SELECT produto.id, produto.nome, produto.preco, estoque.quantidade
                FROM produto
                JOIN estoque ON estoque.id_produto = produto.id
                ORDER BY produto.nome";
$produtos = $pdo->query($sqlProdutos)->fetchAll();

// Busca todos os clientes cadastrados para o select
$clientes  = $pdo->query("SELECT id, nome FROM cliente ORDER BY nome")->fetchAll();

// Busca todos os vendedores cadastrados para o select
$vendedores = $pdo->query("SELECT id, nome FROM vendedor ORDER BY nome")->fetchAll();
?>

<div class="container">

  <a href="historico.php" class="link-voltar">📜 Ver histórico de vendas</a>

  <div class="card">
    <div class="card-topo">
      <h2>🛍️ Realizar Venda</h2>
      <p>Preencha os dados da venda abaixo</p>
    </div>

    <!-- Formulário de venda — envia para processar.php via POST -->
    <form action="processar.php" method="POST">

      <!-- Select de produto — mostra nome, preço e quantidade disponível -->
      <div class="form-grupo">
        <label class="form-label" for="produto">Produto</label>
        <select class="form-control" id="produto" name="produto" required
                onchange="atualizarEstoque(this)">
          <option value="">— Selecione o produto —</option>
          <?php foreach ($produtos as $p): ?>
            <?php if ($p['quantidade'] > 0): ?>
              <!-- Produto com estoque: mostra o preço e a quantidade disponível -->
              <option value="<?= $p['id'] ?>"
                      data-estoque="<?= $p['quantidade'] ?>"
                      data-preco="<?= $p['preco'] ?>">
                <?= htmlspecialchars($p['nome']) ?> —
                R$ <?= number_format($p['preco'], 2, ',', '.') ?>
                (<?= $p['quantidade'] ?> disponíveis)
              </option>
            <?php else: ?>
              <!-- Produto esgotado: aparece desabilitado no select -->
              <option value="" disabled>
                <?= htmlspecialchars($p['nome']) ?> (Esgotado)
              </option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select>
        <!-- Área que mostra o estoque disponível ao selecionar um produto -->
        <p id="info-estoque" style="font-size:0.825rem; color:var(--text-muted); margin-top:0.375rem"></p>
      </div>

      <!-- Campo de quantidade: min e max são ajustados pelo JavaScript -->
      <div class="form-grupo">
        <label class="form-label" for="quantidade">Quantidade</label>
        <input class="form-control" type="number" id="quantidade" name="quantidade"
          min="1" placeholder="0" required>
      </div>

      <!-- Campos de cliente e vendedor lado a lado -->
      <div class="form-grade-2">

        <div class="form-grupo">
          <label class="form-label" for="cliente">Cliente</label>
          <select class="form-control" id="cliente" name="cliente" required>
            <option value="">— Selecione o cliente —</option>
            <?php foreach ($clientes as $c): ?>
              <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-grupo">
          <label class="form-label" for="vendedor">Vendedor responsável</label>
          <select class="form-control" id="vendedor" name="vendedor" required>
            <option value="">— Selecione o vendedor —</option>
            <?php foreach ($vendedores as $v): ?>
              <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

      </div>

      <div class="form-acoes">
        <button type="submit" class="btn btn-primario">🛍️ Confirmar Venda</button>
        <a href="../index.php" class="btn btn-contorno">Cancelar</a>
      </div>

    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
// Atualiza o texto de info de estoque e o limite máximo do campo de quantidade
// quando o usuário troca o produto selecionado
function atualizarEstoque(select) {
    const opcao    = select.options[select.selectedIndex]; // opção selecionada atualmente
    const estoque  = opcao.dataset.estoque;                // pega o estoque do atributo data-estoque
    const preco    = opcao.dataset.preco;                  // pega o preço do atributo data-preco
    const info     = document.getElementById('info-estoque');
    const campo    = document.getElementById('quantidade');

    if (estoque) {
        // Mostra a info de estoque disponível abaixo do select
        info.textContent = `✅ ${estoque} unidade(s) disponível(is) em estoque`;
        campo.max = estoque; // Impede que o usuário peça mais do que tem em estoque
    } else {
        info.textContent = '';
        campo.removeAttribute('max'); // Remove o limite se nenhum produto foi selecionado
    }
}

const tsOpts = { placeholder: '— Digite para buscar —', allowEmptyOption: true };

// Produto — dispara atualizarEstoque ao mudar
new TomSelect('#produto', {
  ...tsOpts,
  onChange: function() {
    atualizarEstoque(document.getElementById('produto'));
  }
});

new TomSelect('#cliente',  tsOpts);
new TomSelect('#vendedor', tsOpts);
</script>

</body>
</html>
