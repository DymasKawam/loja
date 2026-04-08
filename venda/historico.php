<?php
require_once '../auth.php';
exigir_login();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Histórico de Vendas</title>
  <link rel="stylesheet" href="../estilo.css">
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
include("../conexao.php"); // Conecta ao banco de dados

// Busca todas as vendas com os dados de cliente, vendedor, produto e data
// Vários JOINs conectam as tabelas para montar o histórico completo em uma só query
$sql = "
    SELECT
        venda.id,
        venda.data,
        cliente.nome    AS cliente,
        vendedor.nome   AS vendedor,
        produto.nome    AS produto,
        produto.preco,
        item_venda.quantidade
    FROM venda
    JOIN cliente    ON cliente.id   = venda.id_cliente    -- conecta com a tabela de clientes
    JOIN vendedor   ON vendedor.id  = venda.id_vendedor   -- conecta com a tabela de vendedores
    JOIN item_venda ON item_venda.id_venda  = venda.id    -- conecta com os itens de cada venda
    JOIN produto    ON produto.id   = item_venda.id_produto -- conecta com a tabela de produtos
    ORDER BY venda.id DESC                                -- da venda mais recente para a mais antiga
";

$vendas = $pdo->query($sql)->fetchAll(); // Executa e guarda todas as vendas em um array
?>

<div class="container-largo">

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem">
    <a href="../index.html" class="link-voltar">← Início</a>
    <a href="vender.php" class="btn btn-primario">🛍️ Nova Venda</a>
  </div>

  <div class="card">
    <div class="card-topo">
      <h2>📜 Histórico de Vendas</h2>
      <p><?= count($vendas) ?> venda(s) registrada(s)</p>
    </div>

    <?php if (empty($vendas)): ?>
      <!-- Estado vazio — nenhuma venda registrada ainda -->
      <div class="estado-vazio">
        <div class="icone">🛍️</div>
        <p>Nenhuma venda registrada ainda.</p>
        <a href="vender.php" class="btn btn-primario" style="margin-top:1rem">Realizar a primeira venda</a>
      </div>

    <?php else: ?>
      <div class="tabela-wrapper">
        <table>
          <thead>
            <tr>
              <th>Venda #</th>
              <th>Data</th>
              <th>Produto</th>
              <th>Qtd.</th>
              <th>Cliente</th>
              <th>Vendedor</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($vendas as $v): ?>
              <?php
              // Calcula o total multiplicando o preço do produto pela quantidade vendida
              $total = $v['preco'] * $v['quantidade'];

              // Formata a data do banco (formato Y-m-d H:i:s) para o padrão brasileiro (dd/mm/aaaa hh:mm)
              $data = date('d/m/Y H:i', strtotime($v['data']));
              ?>
              <tr>
                <!-- ID da venda em formato de badge azul -->
                <td><span class="badge badge-azul">#<?= $v['id'] ?></span></td>
                <td style="color:var(--text-muted); font-size:0.875rem"><?= $data ?></td>
                <td><strong><?= htmlspecialchars($v['produto']) ?></strong></td>
                <td><?= $v['quantidade'] ?> un.</td>
                <td><?= htmlspecialchars($v['cliente']) ?></td>
                <td><?= htmlspecialchars($v['vendedor']) ?></td>
                <!-- Total formatado em reais com duas casas decimais -->
                <td style="font-weight:600; color:var(--primary)">
                  R$ <?= number_format($total, 2, ',', '.') ?>
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
