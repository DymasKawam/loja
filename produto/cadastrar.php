<?php
require_once '../auth.php';
exigir_papel('admin');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar Produto</title>
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

<div class="container">

  <a href="listar.php" class="link-voltar">← Voltar para a lista</a>

  <div class="card">
    <div class="card-topo">
      <h2>Cadastrar Produto</h2>
      <p>Preencha as informações do produto e a quantidade inicial em estoque</p>
    </div>

    <?php
    // Exibe mensagem de sucesso se o cadastro anterior funcionou
    if (isset($_GET['msg']) && $_GET['msg'] === 'sucesso') {
        echo '<div class="alerta alerta-sucesso">✅ Produto cadastrado com sucesso!</div>';
    }
    ?>

    <!-- Formulário de cadastro de produto — envia para salvar.php via POST -->
    <form action="salvar.php" method="POST">

      <!-- Nome e Descrição -->
      <div class="form-grupo">
        <label class="form-label" for="nome">Nome do produto</label>
        <input class="form-control" type="text" id="nome" name="nome"
          placeholder="Ex: Camiseta Azul Tam. M" required>
      </div>

      <div class="form-grupo">
        <label class="form-label" for="descricao">Descrição <span style="color:var(--text-muted);font-weight:400">(opcional)</span></label>
        <!-- Textarea para descrições mais longas -->
        <textarea class="form-control" id="descricao" name="descricao"
          placeholder="Descreva o produto brevemente..."></textarea>
      </div>

      <!-- Preço e Quantidade ficam lado a lado -->
      <div class="form-grade-2">

        <div class="form-grupo">
          <label class="form-label" for="preco">Preço de venda (R$)</label>
          <!-- step="0.01" permite inserir centavos (ex: 19.99) -->
          <input class="form-control" type="number" id="preco" name="preco"
            placeholder="0.00" step="0.01" min="0" required>
        </div>

        <div class="form-grupo">
          <label class="form-label" for="quantidade">Quantidade inicial em estoque</label>
          <!-- value="0" inicia o campo com zero por padrão -->
          <input class="form-control" type="number" id="quantidade" name="quantidade"
            placeholder="0" value="0" min="0" required>
        </div>

      </div>

      <!-- Botões de ação -->
      <div class="form-acoes">
        <button type="submit" class="btn btn-primario">✅ Cadastrar Produto</button>
        <a href="listar.php" class="btn btn-contorno">Cancelar</a>
      </div>

    </form>
  </div>

</div>
</body>
</html>
