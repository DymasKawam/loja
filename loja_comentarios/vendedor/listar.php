<?php
// require_once inclui auth.php para disponibilizar exigir_papel()
require_once '../auth.php';
// exigir_papel('admin') restringe o acesso: só admin pode ver a lista de vendedores
exigir_papel('admin');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendedores</title>
  <link rel="stylesheet" href="../estilo.css">
</head>
<body>

<nav class="navbar">
  <a href="../index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>
  <div class="nav-links">
    <a href="../cliente/cadastrar.php">Clientes</a>
    <a href="../produto/cadastrar.php">Produtos</a>
    <a href="../vendedor/cadastrar.php" class="ativo">Vendedores</a>
    <a href="../fornecedor/cadastrar.php">Fornecedores</a>
    <a href="../estoque/entrada.php">Estoque</a>
    <a href="../venda/vender.php">Vendas</a>
  </div>
</nav>

<?php
// include carrega conexao.php, criando $pdo para a query abaixo
include("../conexao.php");

// SELECT * busca todas as colunas da tabela vendedor
// ORDER BY nome ordena em ordem alfabética
// fetchAll() retorna todos os vendedores como array associativo
$vendedores = $pdo->query("SELECT * FROM vendedor ORDER BY nome")->fetchAll();
?>

<div class="container-largo">

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem">
    <a href="../index.php" class="link-voltar">← Início</a>
    <a href="cadastrar.php" class="btn btn-primario">➕ Novo Vendedor</a>
  </div>

  <div class="card">
    <div class="card-topo">
      <h2>Vendedores Cadastrados</h2>
      <!-- count() retorna o número de vendedores no array -->
      <p><?= count($vendedores) ?> vendedor(es) no sistema</p>
    </div>

    <?php if (empty($vendedores)): ?> <!-- empty() retorna true quando não há vendedores -->
      <!-- Estado vazio: exibido quando o banco não tem vendedores -->
      <div class="estado-vazio">
        <div class="icone">🧑‍💼</div>
        <p>Nenhum vendedor cadastrado ainda.</p>
        <a href="cadastrar.php" class="btn btn-primario" style="margin-top:1rem">Cadastrar o primeiro</a>
      </div>

    <?php else: ?> <!-- else: exibe a tabela quando há pelo menos um vendedor -->
      <div class="tabela-wrapper"> <!-- Wrapper com scroll horizontal para telas pequenas -->
        <table>
          <thead>
            <tr>
              <th>#</th>   <!-- Coluna do ID do vendedor -->
              <th>Nome</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($vendedores as $v): ?> <!-- foreach percorre cada vendedor do array -->
              <tr>
                <!-- Exibe o ID precedido de "#" — estilo visual comum para identificadores -->
                <!-- style="width:60px" limita a largura da coluna do ID -->
                <td style="color:var(--text-muted); width:60px">#<?= $v['id'] ?></td>
                <!-- htmlspecialchars() converte caracteres especiais para evitar XSS -->
                <td><strong><?= htmlspecialchars($v['nome']) ?></strong></td>
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
