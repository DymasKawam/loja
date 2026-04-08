<?php
require_once '../auth.php';
exigir_login();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes</title>
  <link rel="stylesheet" href="../estilo.css">
</head>
<body>

<nav class="navbar">
  <a href="../index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>
  <div class="nav-links">
    <a href="../cliente/cadastrar.php" class="ativo">Clientes</a>
    <a href="../produto/cadastrar.php">Produtos</a>
    <a href="../vendedor/cadastrar.php">Vendedores</a>
    <a href="../fornecedor/cadastrar.php">Fornecedores</a>
    <a href="../estoque/entrada.php">Estoque</a>
    <a href="../venda/vender.php">Vendas</a>
  </div>
</nav>

<?php
include("../conexao.php"); // Conecta ao banco de dados

// Busca todos os clientes junto com os dados do endereço usando JOIN
// O JOIN conecta as duas tabelas pelo campo "endereco_cliente" do cliente com "id" do endereço
$sql = "SELECT cliente.*, endereco.rua, endereco.numero, endereco.cidade, endereco.estado
        FROM cliente
        JOIN endereco ON cliente.endereco_cliente = endereco.id
        ORDER BY cliente.nome"; // Ordena em ordem alfabética pelo nome

$clientes = $pdo->query($sql)->fetchAll(); // Executa a busca e guarda todos os resultados
?>

<div class="container-largo">

  <!-- Link de voltar e botão de novo cadastro lado a lado -->
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem">
    <a href="../index.html" class="link-voltar">← Início</a>
    <!-- Botão que leva para a página de cadastro de novo cliente -->
    <a href="cadastrar.php" class="btn btn-primario">➕ Novo Cliente</a>
  </div>

  <div class="card">
    <div class="card-topo">
      <h2>Clientes Cadastrados</h2>
      <!-- Mostra o total de clientes encontrados -->
      <p><?= count($clientes) ?> cliente(s) no sistema</p>
    </div>

    <?php if (empty($clientes)): ?>
      <!-- Mensagem exibida quando não há clientes cadastrados ainda -->
      <div class="estado-vazio">
        <div class="icone">👤</div>
        <p>Nenhum cliente cadastrado ainda.</p>
        <a href="cadastrar.php" class="btn btn-primario" style="margin-top:1rem">Cadastrar o primeiro</a>
      </div>

    <?php else: ?>
      <!-- Tabela de clientes só aparece quando há pelo menos um cadastrado -->
      <div class="tabela-wrapper">
        <table>
          <thead>
            <tr>
              <th>Nome</th>
              <th>CPF</th>
              <th>Cidade / Estado</th>
              <th>Endereço</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($clientes as $c): ?>
              <!-- Cada linha da tabela representa um cliente -->
              <tr>
                <td><strong><?= htmlspecialchars($c['nome']) ?></strong></td>
                <!-- htmlspecialchars evita que caracteres especiais quebrem o HTML -->
                <td><?= htmlspecialchars($c['cpf']) ?></td>
                <td>
                  <!-- Mostra a cidade e o estado como uma etiqueta azul -->
                  <span class="badge badge-azul">
                    <?= htmlspecialchars($c['cidade']) ?> / <?= htmlspecialchars($c['estado']) ?>
                  </span>
                </td>
                <td><?= htmlspecialchars($c['rua']) ?>, <?= htmlspecialchars($c['numero']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

  </div><!-- fim do card -->
</div><!-- fim do container -->

</body>
</html>
