<?php
//require_once é usado para incluir e avaliar o arquivo especificado durante a execução do script. Neste caso, ele inclui o arquivo auth.php, que contém funções relacionadas à autenticação e autorização de usuários. Isso permite que o código nesta página utilize essas funções para verificar se o usuário tem permissão para acessar esta funcionalidade (cadastrar vendedores). Se o arquivo auth.php não for encontrado ou tiver erros, require_once gerará um erro fatal e interromperá a execução do script, garantindo que as verificações de segurança sejam aplicadas corretamente.
require_once '../auth.php';
//exigir_papel() é uma função definida em auth.php que verifica se o usuário logado tem um papel específico (neste caso, 'admin'). Se o usuário não tiver o papel de 'admin', a função redirecionará o usuário para uma página de acesso negado e terminará a execução do script.
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
    // isset() é uma função do PHP que verifica se uma variável está definida e não é nula. $_GET é uma variável superglobal que contém os dados enviados via método GET na URL. Neste caso, o código verifica se a variável 'msg' está definida na URL e se seu valor é igual a 'sucesso'. Se ambas as condições forem verdadeiras, isso indica que um vendedor foi cadastrado com sucesso, e o código dentro do if será executado para exibir uma mensagem de sucesso para o usuário.
    if (isset($_GET['msg']) && $_GET['msg'] === 'sucesso') {
        echo '<div class="alerta alerta-sucesso">✅ Vendedor cadastrado com sucesso!</div>';
    }
    ?>

    <!-- Formulário simples — vendedor só tem nome por enquanto -->
    <form action="salvar.php" method="POST"> <!-- action é um atributo do elemento form que especifica para onde os dados do formulário devem ser enviados quando o formulário é submetido. Neste caso, os dados serão enviados para o arquivo salvar.php, que contém a lógica para processar os dados do formulário e salvar o novo vendedor no banco de dados. method="POST" indica que os dados do formulário serão enviados usando o método POST, que é mais seguro para enviar informações sensíveis, como dados de cadastro, pois não expõe os dados na URL. -->

      <div class="form-grupo">
        <label class="form-label" for="nome">Nome do vendedor</label> <!-- label é um elemento HTML usado para definir um rótulo para um elemento de formulário. O atributo for="nome" associa este rótulo ao campo de entrada com id="nome". Isso melhora a acessibilidade, permitindo que os usuários saibam qual campo estão preenchendo. -->
        
        <!-- input é um elemento HTML usado para criar campos de entrada em um formulário. class="form-control" é uma classe CSS queaplica estilos específicos ao campo de entrada. type="text" indica que este campo de entrada é do tipo texto, permitindo que os usuários digitem informações alfanuméricas. id="nome" é um identificador único para este campo de entrada. name="nome" é o nome do campo que será enviado no corpo da requisição POST para salvar.php, permitindo que o script acesse o valor usando $_POST['nome']. placeholder="Ex: Maria Oliveira" é um texto de exemplo que aparece dentro do campo de entrada quando ele está vazio, orientando os usuários sobre o formato esperado para o nome do vendedor. required é um atributo booleano que torna este campo obrigatório, impedindo que o formulário seja submetido sem que este campo seja preenchido.
         -->
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
