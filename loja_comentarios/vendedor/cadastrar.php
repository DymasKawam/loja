<?php
// require_once inclui auth.php uma vez — disponibiliza exigir_papel()
require_once '../auth.php';
// exigir_papel('admin') impede que vendedores cadastrem outros vendedores
exigir_papel('admin');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar Vendedor</title>
  <link rel="stylesheet" href="../estilo.css">
</head>
<body>

<nav class="navbar">
  <a href="../index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>
  <div class="nav-links">
    <a href="../cliente/cadastrar.php">Clientes</a>
    <a href="../produto/cadastrar.php">Produtos</a>
    <a href="../vendedor/cadastrar.php" class="ativo">Vendedores</a> <!-- class="ativo" destaca o link atual -->
    <a href="../fornecedor/cadastrar.php">Fornecedores</a>
    <a href="../estoque/entrada.php">Estoque</a>
    <a href="../venda/vender.php">Vendas</a>
  </div>
</nav>

<div class="container">

  <a href="listar.php" class="link-voltar">← Voltar para a lista</a>

  <div class="card">
    <div class="card-topo">
      <h2>Cadastrar Vendedor</h2>
      <p>Registra um novo vendedor no sistema</p>
    </div>

    <?php
    // Verifica se salvar.php redirecionou de volta com uma mensagem na URL
    if (isset($_GET['msg']) && $_GET['msg'] === 'sucesso') {
        echo '<div class="alerta alerta-sucesso">✅ Vendedor cadastrado com sucesso!</div>';
    }
    if (isset($_GET['msg']) && $_GET['msg'] === 'erro') {
        echo '<div class="alerta alerta-erro">❌ Erro ao cadastrar o vendedor.</div>';
    }
    ?>

    <!-- Formulário simples: vendedor tem apenas o campo nome por enquanto -->
    <form action="salvar.php" method="POST">

      <div class="form-grupo">
        <label class="form-label" for="nome">Nome do vendedor</label>
        <!-- required impede o envio do formulário sem preencher o campo -->
        <input class="form-control" type="text" id="nome" name="nome"
          placeholder="Ex: Maria Oliveira" required>
      </div>

      <div class="form-acoes">
        <button type="submit" class="btn btn-primario">✅ Cadastrar Vendedor</button>
        <a href="listar.php" class="btn btn-contorno">Cancelar</a>
      </div>

    </form>
  </div>
</div>
</body>
</html>
