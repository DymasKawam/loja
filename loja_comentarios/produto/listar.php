<?php
// require_once inclui auth.php para disponibilizar exigir_papel()
require_once '../auth.php';
// exigir_papel('admin') garante acesso restrito — vendedores não podem ver a lista de produtos
exigir_papel('admin');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produtos</title>
  <link rel="stylesheet" href="../estilo.css">
</head>
<body>

<nav class="navbar">
  <a href="../index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>
  <div class="nav-links">
    <a href="../cliente/cadastrar.php">Clientes</a>
    <a href="../produto/cadastrar.php" class="ativo">Produtos</a>
    <a href="../vendedor/cadastrar.php">Vendedores</a>
    <a href="../fornecedor/cadastrar.php">Fornecedores</a>
    <a href="../estoque/entrada.php">Estoque</a>
    <a href="../venda/vender.php">Vendas</a>
  </div>
</nav>

<?php
// include carrega conexao.php e cria $pdo para executar a query abaixo
include("../conexao.php");

// LEFT JOIN inclui todos os produtos mesmo que não tenham registro na tabela "estoque"
// COALESCE retorna o primeiro valor não-nulo: se quantidade for NULL, retorna 0
// ORDER BY produto.nome ordena em ordem alfabética
$sql = "SELECT produto.*, COALESCE(estoque.quantidade, 0) AS quantidade
        FROM produto
        LEFT JOIN estoque ON estoque.id_produto = produto.id
        ORDER BY produto.nome";

// query() executa a query sem parâmetros (nenhum valor do usuário está na query)
// fetchAll() retorna todos os produtos de uma vez como array associativo
$produtos = $pdo->query($sql)->fetchAll();
?>

<!-- <div class="container-largo"> tem largura maior que o container padrão (melhor para tabelas) -->
<div class="container-largo">

  <!-- Linha com link "Início" à esquerda e botão "Novo Produto" à direita -->
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem">
    <a href="../index.php" class="link-voltar">← Início</a>
    <a href="cadastrar.php" class="btn btn-primario">➕ Novo Produto</a>
  </div>

  <div class="card">
    <div class="card-topo">
      <h2>Produtos Cadastrados</h2>
      <!-- count() conta o total de produtos no array para exibir no subtítulo -->
      <p><?= count($produtos) ?> produto(s) no sistema</p>
    </div>

    <?php if (empty($produtos)): ?> <!-- empty() retorna true quando o array não tem elementos -->
      <!-- Estado vazio: exibido quando não há produtos cadastrados -->
      <div class="estado-vazio">
        <div class="icone">📦</div>
        <p>Nenhum produto cadastrado ainda.</p>
        <a href="cadastrar.php" class="btn btn-primario" style="margin-top:1rem">Cadastrar o primeiro</a>
      </div>

    <?php else: ?> <!-- else: exibe a tabela quando há pelo menos um produto -->
      <!-- <div class="tabela-wrapper"> adiciona scroll horizontal em telas pequenas -->
      <div class="tabela-wrapper">
        <table>
          <thead>
            <tr>
              <th>Nome</th>
              <th>Descrição</th>
              <th>Preço de venda</th>
              <th>Estoque</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($produtos as $p): ?> <!-- foreach itera cada produto do array -->
              <tr>
                <td><strong><?= htmlspecialchars($p['nome']) ?></strong></td>
                <!-- ?: (operador ternário) exibe '—' se a descrição estiver vazia -->
                <td><?= htmlspecialchars($p['descricao']) ?: '<span style="color:var(--text-muted)">—</span>' ?></td>
                <!-- number_format() formata o preço: 2 casas decimais, vírgula decimal, ponto como milhar -->
                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                <td>
                  <?php if ($p['quantidade'] > 0): ?> <!-- Produto com estoque → badge verde -->
                    <span class="badge badge-verde"><?= $p['quantidade'] ?> un.</span>
                  <?php else: ?> <!-- Produto sem estoque → badge vermelho "Esgotado" -->
                    <span class="badge badge-vermelho">Esgotado</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

  </div>
</div>
</body>
</html>
