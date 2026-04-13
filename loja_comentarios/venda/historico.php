<?php
// require_once inclui auth.php uma única vez para disponibilizar exigir_login()
require_once '../auth.php';
// exigir_login() garante que o usuário esteja logado para ver o histórico de vendas
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
    <a href="../venda/vender.php" class="ativo">Vendas</a> <!-- class="ativo" destaca o link atual -->
  </div>
</nav>

<?php
// include carrega conexao.php e cria $pdo para a consulta de histórico
include("../conexao.php");

// Query com múltiplos JOINs para montar o histórico completo em uma só consulta
// Cada JOIN conecta a tabela "venda" com as tabelas relacionadas pelo campo de chave estrangeira
$sql = "
    SELECT
        venda.id,
        venda.data,
        cliente.nome    AS cliente,   -- AS renomeia a coluna para evitar conflito de nomes
        vendedor.nome   AS vendedor,
        produto.nome    AS produto,
        produto.preco,
        item_venda.quantidade
    FROM venda
    JOIN cliente    ON cliente.id   = venda.id_cliente    -- conecta com a tabela de clientes pelo id_cliente
    JOIN vendedor   ON vendedor.id  = venda.id_vendedor   -- conecta com a tabela de vendedores
    JOIN item_venda ON item_venda.id_venda  = venda.id    -- conecta com os itens que pertencem a cada venda
    JOIN produto    ON produto.id   = item_venda.id_produto -- conecta com os dados do produto vendido
    ORDER BY venda.id DESC                                -- DESC mostra da venda mais recente para a mais antiga
";

// fetchAll() retorna todas as vendas de uma vez como array associativo
$vendas = $pdo->query($sql)->fetchAll();
?>

<div class="container-largo">

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem">
    <!-- CORREÇÃO: link apontava para ../index.html; corrigido para ../index.php -->
    <a href="../index.php" class="link-voltar">← Início</a>
    <a href="vender.php" class="btn btn-primario">🛍️ Nova Venda</a>
  </div>

  <div class="card">
    <div class="card-topo">
      <h2>📜 Histórico de Vendas</h2>
      <!-- count() conta o total de registros no array de vendas -->
      <p><?= count($vendas) ?> venda(s) registrada(s)</p>
    </div>

    <?php if (empty($vendas)): ?> <!-- empty() retorna true quando não há vendas ainda -->
      <!-- Estado vazio: exibido quando ainda não há nenhuma venda registrada -->
      <div class="estado-vazio">
        <div class="icone">🛍️</div>
        <p>Nenhuma venda registrada ainda.</p>
        <a href="vender.php" class="btn btn-primario" style="margin-top:1rem">Realizar a primeira venda</a>
      </div>

    <?php else: ?> <!-- else: exibe a tabela quando há pelo menos uma venda -->
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
            <?php foreach ($vendas as $v): ?> <!-- foreach percorre cada venda do array -->
              <?php
              // Calcula o valor total desta linha: preço unitário × quantidade
              $total = $v['preco'] * $v['quantidade'];

              // date() formata a data; 'd/m/Y H:i' é o padrão brasileiro (dia/mês/ano hora:minuto)
              // strtotime() converte a string de data do banco (Y-m-d H:i:s) para timestamp Unix
              $data = date('d/m/Y H:i', strtotime($v['data']));
              ?>
              <tr>
                <!-- Badge azul com o número da venda precedido de "#" -->
                <td><span class="badge badge-azul">#<?= $v['id'] ?></span></td>
                <!-- font-size:0.875rem deixa a data um pouco menor para não competir com o conteúdo principal -->
                <td style="color:var(--text-muted); font-size:0.875rem"><?= $data ?></td>
                <td><strong><?= htmlspecialchars($v['produto']) ?></strong></td>
                <td><?= $v['quantidade'] ?> un.</td>
                <!-- htmlspecialchars() protege contra XSS nos nomes de cliente e vendedor -->
                <td><?= htmlspecialchars($v['cliente']) ?></td>
                <td><?= htmlspecialchars($v['vendedor']) ?></td>
                <!-- number_format() formata o total com 2 casas, vírgula decimal e ponto de milhar -->
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
