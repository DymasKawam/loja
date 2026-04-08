<?php
require_once '../auth.php';
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
    <a href="../vendedor/cadastrar.php" class="ativo">Vendedores</a>
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
    // Mostra mensagem de sucesso se veio redirecionado do salvar.php com ?msg=sucesso
    if (isset($_GET['msg']) && $_GET['msg'] === 'sucesso') {
        echo '<div class="alerta alerta-sucesso">✅ Vendedor cadastrado com sucesso!</div>';
    }
    ?>

    <!-- Formulário simples — vendedor só tem nome por enquanto -->
    <form action="salvar.php" method="POST">

      <div class="form-grupo">
        <label class="form-label" for="nome">Nome do vendedor</label>
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
