<?php
// require_once inclui auth.php uma única vez para disponibilizar exigir_papel()
require_once '../auth.php';
// exigir_papel('admin') bloqueia vendedores e não-logados de registrar entradas de estoque
exigir_papel('admin');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entrada de Estoque</title>
  <link rel="stylesheet" href="../estilo.css">
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
  <style>
    /* Estilos do Tom Select adaptados ao visual do projeto */
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
    <a href="../estoque/entrada.php" class="ativo">Estoque</a>
    <a href="../venda/vender.php">Vendas</a>
  </div>
</nav>

<?php
// include carrega conexao.php para buscar fornecedores e produtos nos selects abaixo
include("../conexao.php");
?>

<div class="container">

  <a href="../index.php" class="link-voltar">← Início</a>

  <div class="card">
    <div class="card-topo">
      <h2>📥 Entrada de Estoque</h2>
      <p>Escolha como deseja registrar a entrada</p>
    </div>

    <?php
    // isset() verifica se o parâmetro "msg" chegou na URL (vindo de salvar_entrada.php ou ajustar_estoque.php)
    if (isset($_GET['msg'])) {
        if ($_GET['msg'] === 'entrada_ok')
            echo '<div class="alerta alerta-sucesso">✅ Entrada com fornecedor registrada!</div>';
        if ($_GET['msg'] === 'ajuste_ok')
            echo '<div class="alerta alerta-sucesso">✅ Estoque ajustado com sucesso!</div>';
        if ($_GET['msg'] === 'erro')
            // htmlspecialchars() protege contra XSS ao exibir a mensagem de erro vinda da URL
            echo '<div class="alerta alerta-erro">❌ Erro ao registrar: ' . htmlspecialchars($_GET['detalhe'] ?? '') . '</div>';
    }
    ?>

    <!-- Sistema de abas para escolher entre os dois modos de entrada -->
    <div class="abas">
      <!-- class="ativa" marca a primeira aba como selecionada ao carregar a página -->
      <button class="aba-btn ativa" onclick="trocarAba('comFornecedor', this)">
        🏭 Com Fornecedor
      </button>
      <button class="aba-btn" onclick="trocarAba('semFornecedor', this)">
        ⚙️ Ajuste Direto
      </button>
    </div>

    <!-- ── ABA 1: entrada vinculada a um fornecedor ── -->
    <div id="comFornecedor"> <!-- id usado pelo JavaScript para mostrar/esconder -->
      <p style="color:var(--text-muted); font-size:0.875rem; margin-bottom:1.25rem">
        Use quando comprou produtos de um fornecedor e quer registrar a entrada no estoque.
      </p>

      <!-- action="salvar_entrada.php" processa a entrada com fornecedor -->
      <form action="salvar_entrada.php" method="POST">

        <div class="form-grade-2">

          <div class="form-grupo">
            <label class="form-label" for="id_fornecedor">Fornecedor</label>
            <select class="form-control" id="id_fornecedor" name="id_fornecedor" required>
              <option value="">— Selecione —</option>
              <?php
              // foreach percorre os fornecedores e gera uma <option> para cada um
              foreach ($pdo->query("SELECT id, nome FROM fornecedor ORDER BY nome") as $f):
              ?>
                <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-grupo">
            <label class="form-label" for="id_produto">Produto</label>
            <select class="form-control" id="id_produto" name="id_produto" required>
              <option value="">— Selecione —</option>
              <?php
              foreach ($pdo->query("SELECT id, nome FROM produto ORDER BY nome") as $p):
              ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-grupo">
            <label class="form-label" for="preco_compra">Preço de compra (R$)</label>
            <!-- step="0.01" permite centavos; min="0" impede preços negativos -->
            <input class="form-control" type="number" id="preco_compra" name="preco_compra"
              step="0.01" min="0" placeholder="0.00" required>
          </div>

          <div class="form-grupo">
            <label class="form-label" for="quantidade">Quantidade recebida</label>
            <!-- min="1" garante que sempre seja registrado pelo menos 1 item -->
            <input class="form-control" type="number" id="quantidade" name="quantidade"
              min="1" placeholder="0" required>
          </div>

        </div>

        <div class="form-acoes">
          <button type="submit" class="btn btn-primario">📥 Registrar Entrada</button>
        </div>

      </form>
    </div>

    <!-- ── ABA 2: ajuste direto sem fornecedor (começa oculta) ── -->
    <div id="semFornecedor" style="display:none"> <!-- display:none esconde este painel inicialmente -->
      <p style="color:var(--text-muted); font-size:0.875rem; margin-bottom:1.25rem">
        Use quando precisa adicionar unidades a um produto sem vincular a um fornecedor.
      </p>

      <!-- action="ajustar_estoque.php" processa o ajuste direto de estoque -->
      <form action="ajustar_estoque.php" method="POST">

        <div class="form-grupo">
          <label class="form-label" for="id_produto_ajuste">Produto</label>
          <select class="form-control" id="id_produto_ajuste" name="id_produto" required>
            <option value="">— Selecione —</option>
            <?php
            // LEFT JOIN inclui produtos mesmo sem estoque; COALESCE retorna 0 quando quantidade é NULL
            $sql = "SELECT produto.id, produto.nome, COALESCE(estoque.quantidade, 0) AS quantidade
                    FROM produto
                    LEFT JOIN estoque ON estoque.id_produto = produto.id
                    ORDER BY produto.nome";
            foreach ($pdo->query($sql) as $p):
            ?>
              <!-- Mostra o estoque atual entre parênteses para o admin saber de onde está partindo -->
              <option value="<?= $p['id'] ?>">
                <?= htmlspecialchars($p['nome']) ?> (Estoque atual: <?= $p['quantidade'] ?> un.)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-grupo">
          <label class="form-label" for="qtd_ajuste">Quantidade a adicionar</label>
          <input class="form-control" type="number" id="qtd_ajuste" name="quantidade"
            min="1" placeholder="0" required>
        </div>

        <div class="form-acoes">
          <button type="submit" class="btn btn-sucesso">✅ Adicionar ao Estoque</button>
        </div>

      </form>
    </div>

  </div><!-- fim do card -->

  <!-- Link de atalho para a consulta de estoque -->
  <div style="text-align:center; margin-top:0.5rem">
    <a href="consulta.php" class="link-voltar">🔍 Ir para Consulta de Estoque</a>
  </div>

</div><!-- fim do container -->

<script>
// trocarAba() alterna qual painel de entrada está visível na tela
function trocarAba(idAba, botao) {
    // Esconde os dois painéis antes de exibir o selecionado
    document.getElementById('comFornecedor').style.display = 'none';
    document.getElementById('semFornecedor').style.display  = 'none';
    // querySelectorAll() seleciona todos os botões de aba; forEach remove a classe "ativa" de cada um
    document.querySelectorAll('.aba-btn').forEach(b => b.classList.remove('ativa'));
    // Exibe o painel escolhido e marca o botão como ativo
    document.getElementById(idAba).style.display = 'block';
    botao.classList.add('ativa');
}
</script>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
  // tsOpts são as configurações padrão compartilhadas pelos três selects desta página
  const tsOpts = { placeholder: '— Digite para buscar —', allowEmptyOption: true };
  // new TomSelect() transforma cada <select> em um campo pesquisável com autocomplete
  new TomSelect('#id_fornecedor',     tsOpts);
  new TomSelect('#id_produto',        tsOpts);
  new TomSelect('#id_produto_ajuste', tsOpts);
</script>

</body>
</html>
