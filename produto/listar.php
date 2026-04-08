<?php
require_once '../auth.php';
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
include("../conexao.php"); // Conecta ao banco

// Busca todos os produtos junto com a quantidade atual do estoque
// LEFT JOIN garante que produtos sem registro de estoque também apareçam (mostra 0)
// COALESCE retorna 0 quando a quantidade está nula (produto sem estoque registrado)
$sql = "SELECT produto.*, COALESCE(estoque.quantidade, 0) AS quantidade
        FROM produto
        LEFT JOIN estoque ON estoque.id_produto = produto.id
        ORDER BY produto.nome"; // Ordem alfabética

$produtos = $pdo->query($sql)->fetchAll(); // Executa e guarda todos os resultados
?>

<div class="container-largo">

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem">
    <a href="../index.php" class="link-voltar">← Início</a>
    <a href="cadastrar.php" class="btn btn-primario">➕ Novo Produto</a>
  </div>

  <div class="card">
    <div class="card-topo">
      <h2>Produtos Cadastrados</h2>
      <p><?= count($produtos) ?> produto(s) no sistema</p>
    </div>

    <?php if (empty($produtos)): ?>
      <div class="estado-vazio">
        <div class="icone">📦</div>
        <p>Nenhum produto cadastrado ainda.</p>
        <a href="cadastrar.php" class="btn btn-primario" style="margin-top:1rem">Cadastrar o primeiro</a>
      </div>

    <?php else: ?>
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
            <?php foreach ($produtos as $p): ?>
              <tr>
                <td><strong><?= htmlspecialchars($p['nome']) ?></strong></td>
                <td><?= htmlspecialchars($p['descricao']) ?: '<span style="color:var(--text-muted)">—</span>' ?></td>
                <!-- number_format formata o preço com 2 casas decimais, vírgula e ponto no padrão BR -->
                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                <td>
                  <?php if ($p['quantidade'] > 0): ?>
                    <!-- Verde quando tem estoque disponível -->
                    <span class="badge badge-verde"><?= $p['quantidade'] ?> un.</span>
                  <?php else: ?>
                    <!-- Vermelho quando o produto está esgotado -->
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
