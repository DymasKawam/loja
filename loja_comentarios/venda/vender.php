<?php
// require_once inclui auth.php uma única vez para disponibilizar exigir_login()
require_once '../auth.php';
// exigir_login() verifica se há sessão ativa — vendedores e admins podem realizar vendas
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
// include carrega conexao.php e cria $pdo para buscar produtos, clientes e vendedores
include("../conexao.php");

// JOIN (não LEFT JOIN) garante que só produtos com estoque registrado apareçam no select
// ORDER BY produto.nome ordena em ordem alfabética
$sqlProdutos = "SELECT produto.id, produto.nome, produto.preco, estoque.quantidade
                FROM produto
                JOIN estoque ON estoque.id_produto = produto.id
                ORDER BY produto.nome";
$produtos = $pdo->query($sqlProdutos)->fetchAll(); // fetchAll() retorna todos como array associativo

// Busca todos os clientes para o select de cliente
$clientes  = $pdo->query("SELECT id, nome FROM cliente ORDER BY nome")->fetchAll();

// Busca todos os vendedores para o select de vendedor responsável
$vendedores = $pdo->query("SELECT id, nome FROM vendedor ORDER BY nome")->fetchAll();
?>

<div class="container">

  <a href="historico.php" class="link-voltar">📜 Ver histórico de vendas</a>

  <div class="card">
    <div class="card-topo">
      <h2>🛍️ Realizar Venda</h2>
      <p>Preencha os dados da venda abaixo</p>
    </div>

    <!-- action="processar.php" processa a venda; method="POST" envia os dados de forma segura -->
    <form action="processar.php" method="POST">

      <div class="form-grupo">
        <label class="form-label" for="produto">Produto</label>
        <!-- onchange="atualizarEstoque(this)" dispara a função JS ao trocar o produto -->
        <select class="form-control" id="produto" name="produto" required
                onchange="atualizarEstoque(this)">
          <option value="">— Selecione o produto —</option>
          <?php foreach ($produtos as $p): ?>
            <?php if ($p['quantidade'] > 0): ?> <!-- Só exibe produtos com estoque disponível -->
              <!-- data-estoque e data-preco são atributos personalizados lidos pelo JavaScript -->
              <option value="<?= $p['id'] ?>"
                      data-estoque="<?= $p['quantidade'] ?>"
                      data-preco="<?= $p['preco'] ?>">
                <?= htmlspecialchars($p['nome']) ?> —
                R$ <?= number_format($p['preco'], 2, ',', '.') ?>
                (<?= $p['quantidade'] ?> disponíveis)
              </option>
            <?php else: ?> <!-- Produto esgotado: aparece desabilitado, não pode ser selecionado -->
              <option value="" disabled>
                <?= htmlspecialchars($p['nome']) ?> (Esgotado)
              </option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select>
        <!-- Parágrafo preenchido pelo JavaScript com o estoque disponível do produto selecionado -->
        <p id="info-estoque" style="font-size:0.825rem; color:var(--text-muted); margin-top:0.375rem"></p>
      </div>

      <div class="form-grupo">
        <label class="form-label" for="quantidade">Quantidade</label>
        <!-- O atributo max é definido dinamicamente pelo JavaScript para não ultrapassar o estoque -->
        <input class="form-control" type="number" id="quantidade" name="quantidade"
          min="1" placeholder="0" required>
      </div>

      <!-- Cliente e Vendedor lado a lado na grade de 2 colunas -->
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
// atualizarEstoque() é chamada sempre que o usuário troca o produto selecionado
function atualizarEstoque(select) {
    // options[selectedIndex] retorna o elemento <option> atualmente selecionado
    const opcao   = select.options[select.selectedIndex];
    // dataset acessa os atributos data-* definidos no HTML da <option>
    const estoque = opcao.dataset.estoque;
    const preco   = opcao.dataset.preco;
    const info    = document.getElementById('info-estoque'); // parágrafo de informação
    const campo   = document.getElementById('quantidade');   // campo numérico de quantidade

    if (estoque) {
        info.textContent = `✅ ${estoque} unidade(s) disponível(is) em estoque`;
        campo.max = estoque; // max limita o valor máximo que o usuário pode digitar
    } else {
        info.textContent = '';
        campo.removeAttribute('max'); // removeAttribute() apaga o atributo max quando não há produto selecionado
    }
}

// tsOpts são as configurações padrão reutilizadas pelos três selects
const tsOpts = { placeholder: '— Digite para buscar —', allowEmptyOption: true };

// TomSelect transforma o <select> de produto e dispara atualizarEstoque() ao mudar
new TomSelect('#produto', {
  ...tsOpts, // spread operator copia as propriedades de tsOpts para este objeto
  onChange: function() {
    // O Tom Select mantém o <select> original oculto no DOM — getElementById ainda o encontra
    atualizarEstoque(document.getElementById('produto'));
  }
});

// Selects simples sem callback adicional
new TomSelect('#cliente',  tsOpts);
new TomSelect('#vendedor', tsOpts);
</script>

</body>
</html>
