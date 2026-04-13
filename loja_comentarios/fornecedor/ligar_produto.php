<?php
// require_once inclui auth.php apenas uma vez para disponibilizar as funções de autenticação
require_once '../auth.php';
// exigir_papel('admin') garante que apenas administradores acessem esta página
exigir_papel('admin');
?>
<!DOCTYPE html> <!-- Define o tipo de documento como HTML5 -->
<html lang="pt-BR"> <!-- Idioma da página -->
<head>
  <meta charset="UTF-8"> <!-- Codificação para suportar acentos e caracteres especiais -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Layout responsivo -->
  <title>Vincular Produto a Fornecedor</title> <!-- Título na aba do navegador -->
  <link rel="stylesheet" href="../estilo.css"> <!-- Importa os estilos do projeto -->
  <!-- Importa o CSS do Tom Select, um componente de select com busca e autocomplete -->
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
  <style>
    /* Estilos que adaptam o Tom Select ao visual do projeto */
    .ts-wrapper .ts-control { font-size: 0.925rem; border: 1px solid var(--borda, #d1d5db); border-radius: 6px; padding: 0.45rem 0.65rem; background: var(--fundo-input, #fff); }
    .ts-wrapper.focus .ts-control { border-color: var(--primario, #6366f1); box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
    .ts-dropdown { border: 1px solid var(--borda, #d1d5db); border-radius: 6px; font-size: 0.925rem; }
    .ts-dropdown .option.selected, .ts-dropdown .option:hover { background: var(--primario, #6366f1); color: #fff; }
  </style>
</head>
<body>

<!-- <nav> com class="navbar" define a barra de navegação superior -->
<nav class="navbar">
  <a href="../index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>
  <div class="nav-links">
    <a href="../cliente/cadastrar.php">Clientes</a>
    <a href="../produto/cadastrar.php">Produtos</a>
    <a href="../vendedor/cadastrar.php">Vendedores</a>
    <a href="../fornecedor/cadastrar.php" class="ativo">Fornecedores</a> <!-- class="ativo" destaca o item atual -->
    <a href="../estoque/entrada.php">Estoque</a>
    <a href="../venda/vender.php">Vendas</a>
  </div>
</nav>

<?php
// include carrega conexao.php, criando $pdo para buscar fornecedores e produtos
include("../conexao.php");
?>

<div class="container"> <!-- Limita a largura e centraliza o conteúdo -->

  <a href="../index.php" class="link-voltar">← Início</a> <!-- Link de retorno ao painel -->

  <div class="card"> <!-- Painel branco com sombra -->
    <div class="card-topo"> <!-- Cabeçalho do card -->
      <h2>🔗 Vincular Produto a Fornecedor</h2>
      <p>Use quando um fornecedor já cadastrado passou a fornecer um produto novo</p>
    </div>

    <?php
    // isset() verifica se o parâmetro "msg" existe na URL (vindo de salvar_ligacao.php)
    if (isset($_GET['msg'])) {
        if ($_GET['msg'] === 'sucesso') {
            // Exibe alerta verde quando o vínculo foi criado com sucesso
            echo '<div class="alerta alerta-sucesso">✅ Produto vinculado ao fornecedor com sucesso!</div>';
        }
        if ($_GET['msg'] === 'erro') {
            // Exibe alerta vermelho quando houve falha — htmlspecialchars protege contra XSS
            echo '<div class="alerta alerta-erro">❌ Erro: ' . htmlspecialchars($_GET['detalhe'] ?? '') . '</div>';
        }
    }
    ?>

    <!-- Formulário que envia os dados para salvar_ligacao.php via POST -->
    <form action="salvar_ligacao.php" method="POST">

      <!-- Select de fornecedor — será aprimorado pelo Tom Select no JavaScript abaixo -->
      <div class="form-grupo">
        <label class="form-label" for="id_fornecedor">Fornecedor</label>
        <select class="form-control" id="id_fornecedor" name="id_fornecedor" required>
          <option value="">— Selecione o fornecedor —</option>
          <?php
          // query() executa a consulta e foreach percorre cada fornecedor do resultado
          foreach ($pdo->query("SELECT id, nome FROM fornecedor ORDER BY nome") as $f):
          ?>
            <!-- <?= $f['id'] ?> é o valor enviado no POST; htmlspecialchars() protege o texto exibido -->
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
          foreach ($pdo->query("SELECT id, nome FROM produto ORDER BY nome") as $p):
          ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Campo de preço de compra -->
      <div class="form-grupo">
        <label class="form-label" for="preco_compra">Preço de compra (R$)</label>
        <!-- type="number" e step="0.01" permitem valores decimais como 12.99 -->
        <input class="form-control" type="number" id="preco_compra" name="preco_compra"
          step="0.01" min="0" placeholder="0.00" required>
      </div>

      <div class="form-acoes">
        <button type="submit" class="btn btn-primario">🔗 Vincular</button>
        <!-- CORREÇÃO: link apontava para ../index.html; corrigido para ../index.php -->
        <a href="../index.php" class="btn btn-contorno">Cancelar</a>
      </div>

    </form>
  </div>
</div>

<!-- Carrega o JavaScript do Tom Select (transforma os <select> em campos com busca) -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
  // tsOpts são as opções padrão para todos os selects da página
  const tsOpts = { placeholder: '— Digite para buscar —', allowEmptyOption: true };
  // new TomSelect() transforma o <select> com o id informado em um campo pesquisável
  new TomSelect('#id_fornecedor', tsOpts);
  new TomSelect('#id_produto',    tsOpts);
</script>

</body>
</html>
