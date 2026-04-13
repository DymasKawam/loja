<?php
// require_once inclui auth.php apenas uma vez — garante que exigir_login() esteja disponível
require_once '../auth.php';
// exigir_login() verifica se o usuário tem sessão ativa; se não tiver, redireciona para login.php
exigir_login();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"> <!-- Codificação que suporta acentos e caracteres especiais -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsividade em celulares -->
  <title>Cadastrar Cliente</title>
  <!-- "../" sobe uma pasta (saindo de cliente/) para encontrar estilo.css na raiz -->
  <link rel="stylesheet" href="../estilo.css">
</head>
<body>
<!-- <nav class="navbar"> define a barra de navegação com os estilos do estilo.css -->
<nav class="navbar">
  <!-- href="../index.php" sobe uma pasta para voltar ao painel principal -->
  <a href="../index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>
  <div class="nav-links">
    <a href="../cliente/cadastrar.php" class="ativo">Clientes</a> <!-- class="ativo" destaca a página atual -->
    <a href="../produto/cadastrar.php">Produtos</a>
    <a href="../vendedor/cadastrar.php">Vendedores</a>
    <a href="../fornecedor/cadastrar.php">Fornecedores</a>
    <a href="../estoque/entrada.php">Estoque</a>
    <a href="../venda/vender.php">Vendas</a>
  </div>
</nav>

<!-- <div class="container"> limita a largura e centraliza o conteúdo da página -->
<div class="container">

  <!-- Link para voltar à lista sem perder nada -->
  <a href="listar.php" class="link-voltar">← Voltar para a lista</a>

  <!-- <div class="card"> é o painel branco com sombra definido em estilo.css -->
  <div class="card">
    <div class="card-topo"> <!-- Cabeçalho do card -->
      <h2>Cadastrar Cliente</h2>
      <p>Preencha os dados do cliente e o endereço</p>
    </div>

    <?php
    // isset() verifica se o parâmetro "msg" existe na URL
    // $_GET['msg'] captura o parâmetro vindo de salvar.php após o redirecionamento
    if (isset($_GET['msg']) && $_GET['msg'] === 'sucesso') {
        // Exibe alerta verde de confirmação quando o cadastro foi bem-sucedido
        echo '<div class="alerta alerta-sucesso">✅ Cliente cadastrado com sucesso!</div>';
    }
    if (isset($_GET['msg']) && $_GET['msg'] === 'erro') {
        // Exibe alerta vermelho com o detalhe do erro vindo da URL
        // htmlspecialchars() previne XSS ao exibir mensagens vindas da URL
        echo '<div class="alerta alerta-erro">❌ Erro: ' . htmlspecialchars($_GET['detalhe'] ?? '') . '</div>';
    }
    ?>

    <!-- action="salvar.php" envia os dados para salvar.php; method="POST" oculta os dados da URL -->
    <form action="salvar.php" method="POST">

      <!-- <p class="secao-titulo"> é um separador de seção com estilo definido em estilo.css -->
      <p class="secao-titulo">Dados Pessoais</p>
      <!-- <div class="form-grade-2"> cria um layout de dois colunas para os campos -->
      <div class="form-grade-2">

        <!-- class="span-2" faz o campo ocupar as duas colunas da grade -->
        <div class="form-grupo span-2">
          <!-- <label> associa o texto ao campo pelo atributo "for" = "id" do input -->
          <label class="form-label" for="nome">Nome completo</label>
          <!-- required impede o envio do formulário sem preencher este campo -->
          <input class="form-control" type="text" id="nome" name="nome"
            placeholder="Ex: João da Silva" required>
        </div>

        <div class="form-grupo span-2">
          <label class="form-label" for="cpf">CPF</label>
          <!-- oninput executa JavaScript a cada tecla; replace() remove tudo que não for número -->
          <!-- maxlength="11" limita a 11 caracteres (tamanho do CPF sem formatação) -->
          <input class="form-control" type="text" id="cpf" name="cpf"
            placeholder="Apenas números (11 dígitos)"
            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            maxlength="11" required>
        </div>

      </div>

      <p class="secao-titulo">Endereço</p>
      <div class="form-grade-2">

        <div class="form-grupo span-2">
          <label class="form-label" for="rua">Rua</label>
          <input class="form-control" type="text" id="rua" name="rua"
            placeholder="Ex: Rua das Flores" required>
        </div>

        <!-- Dois campos lado a lado (uma coluna cada) -->
        <div class="form-grupo">
          <label class="form-label" for="numero">Número</label>
          <input class="form-control" type="text" id="numero" name="numero"
            placeholder="Ex: 123" required>
        </div>

        <div class="form-grupo">
          <label class="form-label" for="cidade">Cidade</label>
          <input class="form-control" type="text" id="cidade" name="cidade"
            placeholder="Ex: São Paulo" required>
        </div>

        <div class="form-grupo">
          <label class="form-label" for="estado">Estado (UF)</label>
          <!-- maxlength="2" limita ao tamanho de uma UF (ex: SP, RJ) -->
          <input class="form-control" type="text" id="estado" name="estado"
            placeholder="Ex: SP" maxlength="2" required>
        </div>

        <div class="form-grupo">
          <label class="form-label" for="cep">CEP</label>
          <!-- maxlength="9" comporta o formato 00000-000 -->
          <input class="form-control" type="text" id="cep" name="cep"
            placeholder="Ex: 01001-000" maxlength="9" required>
        </div>

      </div>

      <!-- <div class="form-acoes"> agrupa os botões de ação com o espaçamento correto -->
      <div class="form-acoes">
        <!-- type="submit" envia o formulário ao clicar -->
        <button type="submit" class="btn btn-primario">✅ Cadastrar Cliente</button>
        <!-- Link de cancelar: descarta tudo e volta para a lista sem salvar -->
        <a href="listar.php" class="btn btn-contorno">Cancelar</a>
      </div>

    </form>
  </div><!-- fim do card -->
</div><!-- fim do container -->

</body>
</html>
