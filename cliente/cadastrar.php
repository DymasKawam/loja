<?php
require_once '../auth.php';
exigir_login();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar Cliente</title>
  <!-- Importa os estilos do projeto — o ".." sobe uma pasta (saindo de "cliente/") -->
  <link rel="stylesheet" href="../estilo.css">
</head>
<body>
<!-- Navegação principal — os links usam "../" porque estamos dentro de uma subpasta -->
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

<div class="container">

  <!-- Link para voltar à lista de clientes -->
  <a href="listar.php" class="link-voltar">← Voltar para a lista</a>

  <div class="card">
    <div class="card-topo">
      <h2>Cadastrar Cliente</h2>
      <p>Preencha os dados do cliente e o endereço</p>
    </div>

    <?php
    /* Verifica se a URL tem ?msg=sucesso para mostrar mensagem de confirmação.
       Isso acontece depois de um cadastro bem-sucedido, quando salvar.php redireciona de volta aqui. */
    if (isset($_GET['msg']) && $_GET['msg'] === 'sucesso') {
        echo '<div class="alerta alerta-sucesso">✅ Cliente cadastrado com sucesso!</div>';
    }
    ?>

    <!-- Formulário que envia os dados para salvar.php usando o método POST -->
    <form action="salvar.php" method="POST">

      <!-- Seção de dados pessoais do cliente -->
      <p class="secao-titulo">Dados Pessoais</p>
      <div class="form-grade-2">

        <!-- Campo de nome — span-2 faz ele ocupar as duas colunas da grade -->
        <div class="form-grupo span-2">
          <label class="form-label" for="nome">Nome completo</label>
          <input class="form-control" type="text" id="nome" name="nome" placeholder="Ex: João da Silva" required>
        </div>

        <!-- Campo de CPF — o "oninput" remove qualquer caractere que não seja número enquanto digita -->
        <div class="form-grupo span-2">
          <label class="form-label" for="cpf">CPF</label>
          <input class="form-control" type="text" id="cpf" name="cpf"
            placeholder="Apenas números (11 dígitos)"
            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            maxlength="11" required>
        </div>

      </div>

      <!-- Seção de endereço do cliente -->
      <p class="secao-titulo">Endereço</p>
      <div class="form-grade-2">

        <!-- Rua ocupa as duas colunas -->
        <div class="form-grupo span-2">
          <label class="form-label" for="rua">Rua</label>
          <input class="form-control" type="text" id="rua" name="rua" placeholder="Ex: Rua das Flores" required>
        </div>

        <!-- Número e Cidade ficam lado a lado -->
        <div class="form-grupo">
          <label class="form-label" for="numero">Número</label>
          <input class="form-control" type="text" id="numero" name="numero" placeholder="Ex: 123" required>
        </div>

        <div class="form-grupo">
          <label class="form-label" for="cidade">Cidade</label>
          <input class="form-control" type="text" id="cidade" name="cidade" placeholder="Ex: São Paulo" required>
        </div>

        <!-- Estado e CEP lado a lado -->
        <div class="form-grupo">
          <label class="form-label" for="estado">Estado (UF)</label>
          <input class="form-control" type="text" id="estado" name="estado"
            placeholder="Ex: SP" maxlength="2" required>
        </div>

        <div class="form-grupo">
          <label class="form-label" for="cep">CEP</label>
          <input class="form-control" type="text" id="cep" name="cep"
            placeholder="Ex: 01001-000" maxlength="9" required>
        </div>

      </div>

      <!-- Botões de ação do formulário -->
      <div class="form-acoes">
        <button type="submit" class="btn btn-primario">✅ Cadastrar Cliente</button>
        <!-- Link de cancelar que leva para a lista sem salvar nada -->
        <a href="listar.php" class="btn btn-contorno">Cancelar</a>
      </div>

    </form>
  </div><!-- fim do card -->
</div><!-- fim do container -->

</body>
</html>
