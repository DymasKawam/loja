<?php
// require_once é uma função do PHP que inclui e avalia o arquivo especificado. Se o arquivo já tiver sido incluído antes, ele não será incluído novamente, evitando erros de redefinição de funções ou variáveis. Neste caso, estamos incluindo o arquivo "auth.php", que provavelmente contém funções relacionadas à autenticação de usuários, como verificar se um usuário está logado e qual é o papel dele (admin ou vendedor).
require_once __DIR__ . '/auth.php';
//exigir_login é uma função definida em auth.php que verifica se o usuário está logado. Se o usuário não estiver logado, essa função provavelmente redireciona para a página de login ou exibe uma mensagem de erro.
exigir_login('');   // raiz do projeto, sem prefixo de pasta
?>
<!DOCTYPE html> <!-- é a declaração do tipo de documento HTML5, que informa ao navegador que o conteúdo da página está escrito em HTML5. -->
<html lang="pt-BR"> <!-- é a tag de abertura do documento HTML, lang: define o idioma da página como português do Brasil. -->
<head> <!-- é a tag de abertura do cabeçalho do documento HTML. -->
  <meta charset="UTF-8"> <!-- Define a codificação de caracteres como UTF-8, que suporta acentos e emojis -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Garante que a página seja responsiva em dispositivos móveis -->
  <title>Painel — Sistema Loja</title>
  <link rel="stylesheet" href="estilo.css"> <!-- rel serve para indicar que este link é para uma folha de estilo CSS, e href especifica o caminho para o arquivo de estilo. -->
  <style> 
    /* Badge de papel na navbar */
    .badge-papel {
      display: inline-block;
      padding: 0.15rem 0.6rem;
      border-radius: 999px;
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      vertical-align: middle;
    }
    .badge-admin    { background:#dbeafe; color:#1d4ed8; }
    .badge-vendedor { background:#dcfce7; color:#15803d; }

    /* Link de sair na navbar */
    .nav-sair {
      color: var(--danger, #ef4444) !important;
      font-weight: 600;
    }
    .nav-sair:hover { opacity: .8; }
  </style>
</head>
<body>

<!-- nav é um elemento HTML5 usado para definir uma seção de navegação em um documento. -->
<nav class="navbar">
  <!--span é um elemento de contêiner genérico usado para agrupar outros elementos. A classe "nav-brand" é usada para aplicar estilos específicos a este elemento, indicando que se trata da marca ou título do sistema. O conteúdo inclui um emoji de carrinho de compras e o nome "Loja Sistema", com "Loja" destacado usando a classe "destaque". -->
  <a href="index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a> 

  <div class="nav-links"> <!-- div é um elemento de contêiner genérico usado para agrupar outros elementos. A classe "nav-links" é usada para aplicar estilos específicos a este contêiner, como layout e espaçamento dos links de navegação. -->
    <!-- Links visíveis para todos os usuários logados -->
    <a href="cliente/cadastrar.php">Clientes</a>
    <a href="venda/vender.php">Vendas</a>

    <?php if (e_admin()): ?> <!-- e_admin() é uma função definida em auth.php que retorna verdadeiro se o usuário logado tiver o papel de administrador. Se for verdadeiro, os links exclusivos para administradores serão exibidos. -->
      <!-- Links exclusivos do admin -->
      <a href="produto/cadastrar.php">Produtos</a>
      <a href="vendedor/cadastrar.php">Vendedores</a>
      <a href="fornecedor/cadastrar.php">Fornecedores</a>
      <a href="estoque/entrada.php">Estoque</a>
    <?php endif; ?> <!-- endif é usado para fechar a estrutura condicional iniciada por if. Se o usuário não for admin, os links dentro do bloco if serão ignorados e não aparecerão na navbar. -->

    <!-- span é um elemento de contêiner genérico usado para agrupar outros elementos. htmlspecialchars() é uma função do PHP que converte caracteres especiais em entidades HTML. Isso é importante para evitar vulnerabilidades de Cross-Site Scripting (XSS) ao exibir o nome do usuário, garantindo que qualquer caractere special seja tratado de forma segura. nome_usuario() é uma função definida em auth.php que retorna o nome do usuário logado. O resultado é exibido na navbar, seguido por um badge que indica o papel do usuário (admin ou vendedor). -->
    <span style="margin-left:.5rem">
      <?= htmlspecialchars(nome_usuario()) ?>
      <!--  span é um elemento de contêiner genérico usado para agrupar outros elementos. A classe "badge-papel badge-<?= papel_usuario() ?>" é usada para aplicar estilos específicos a este elemento, indicando o papel do usuário logado (admin ou vendedor). A função papel_usuario() retorna o papel do usuário, que é exibido dentro do badge. O resultado é um badge colorido que mostra se o usuário é admin ou vendedor, proporcionando uma indicação visual clara do nível de acesso do usuário. -->
      <span class="badge-papel badge-<?= papel_usuario() ?>">
        <?= papel_usuario() ?>
      </span>
    </span>
    <a href="logout.php" class="nav-sair">Sair</a> <!-- A classe "nav-sair" é usada para aplicar estilos específicos a este link, destacando-o como a opção de logout. O link aponta para "logout.php", que contém o código para encerrar a sessão do usuário e redirecionar para a página de login. -->
  </div>
</nav>

<!-- ── Conteúdo principal ────────────────────────────────── -->
<div class="container"> <!-- div é um elemento de contêiner genérico usado para agrupar outros elementos. A classe "container" é usada para aplicar estilos específicos a este contêiner. -->

  <div class="dash-header" style="margin-top:0.5rem"> <!-- div é um elemento de contêiner genérico usado para agrupar outros elementos. A classe "dash-header" é usada para aplicar estilos específicos a este contêiner, como layout e espaçamento. O estilo "margin-top:0.5rem" adiciona uma margem superior para separar o cabeçalho do conteúdo acima. -->
    <h1>Painel do Sistema</h1> <!-- h1 é um elemento de título de nível 1 usado para definir o título principal da página. O conteúdo "Painel do Sistema" indica que esta é a página principal do sistema, onde os usuários podem acessar diferentes áreas e funcionalidades. -->
    <p>Olá, <strong><?= htmlspecialchars(nome_usuario()) ?></strong>!  <!-- p é um elemento de parágrafo usado para definir um bloco de texto. strong é um elemento usado para destacar o texto "nome_usuario()", <,?= htmlspecialchars(nome_usuario()) ?> é uma função do PHP que retorna o nome do usuário logado, e htmlspecialchars() é usada para garantir que qualquer caractere special seja tratado de forma segura. O resultado é uma saudação personalizada que exibe o nome do usuário, proporcionando uma experiência mais amigável e personalizada. -->
       Selecione uma área abaixo para começar.</p>
  </div>

  <!-- ── Módulo: Clientes (todos os papéis) ── -->
  <p class="grupo-titulo">Clientes</p> <!-- p é um elemento de parágrafo usado para definir um bloco de texto. A classe "grupo-titulo" é usada para aplicar estilos específicos a este elemento, indicando que se trata do título de um grupo ou seção. O conteúdo "Clientes" indica que esta seção da dashboard está relacionada à gestão de clientes, onde os usuários podem acessar funcionalidades como cadastrar novos clientes ou listar os clientes existentes. -->
  <div class="dashboard-grade"> 
    <a href="cliente/cadastrar.php" class="dash-card"> 
      <div class="dash-icone">➕</div>
      <div class="dash-titulo">Cadastrar Cliente</div>
      <div class="dash-desc">Adiciona um novo cliente com endereço</div>
    </a>
    <a href="cliente/listar.php" class="dash-card">
      <div class="dash-icone">📋</div>
      <div class="dash-titulo">Listar Clientes</div>
      <div class="dash-desc">Visualiza todos os clientes cadastrados</div>
    </a>
  </div>

  <!-- ── Módulo: Vendas (todos os papéis) ── -->
  <p class="grupo-titulo">Vendas</p>
  <div class="dashboard-grade">
    <a href="venda/vender.php" class="dash-card">
      <div class="dash-icone">🛍️</div>
      <div class="dash-titulo">Realizar Venda</div>
      <div class="dash-desc">Registra uma nova venda e desconta o estoque</div>
    </a>
    <a href="venda/historico.php" class="dash-card">
      <div class="dash-icone">📜</div>
      <div class="dash-titulo">Histórico de Vendas</div>
      <div class="dash-desc">Consulta todas as vendas realizadas</div>
    </a>
  </div>

  <?php if (e_admin()): ?> <!-- e_admin() é uma função definida em auth.php que retorna verdadeiro se o usuário logado tiver o papel de administrador. Se for verdadeiro, os módulos exclusivos para administradores serão exibidos. -->

  <!-- ── Módulo: Produtos (só admin) ── -->
  <p class="grupo-titulo">Produtos</p>
  <div class="dashboard-grade">
    <a href="produto/cadastrar.php" class="dash-card">
      <div class="dash-icone">📦</div>
      <div class="dash-titulo">Cadastrar Produto</div>
      <div class="dash-desc">Adiciona produto com preço e estoque inicial</div>
    </a>
    <a href="produto/listar.php" class="dash-card">
      <div class="dash-icone">🗂️</div>
      <div class="dash-titulo">Listar Produtos</div>
      <div class="dash-desc">Visualiza todos os produtos e seus preços</div>
    </a>
  </div>

  <!-- ── Módulo: Vendedores (só admin) ── -->
  <p class="grupo-titulo">Vendedores</p>
  <div class="dashboard-grade">
    <a href="vendedor/cadastrar.php" class="dash-card">
      <div class="dash-icone">🧑‍💼</div>
      <div class="dash-titulo">Cadastrar Vendedor</div>
      <div class="dash-desc">Registra um novo vendedor no sistema</div>
    </a>
    <a href="vendedor/listar.php" class="dash-card">
      <div class="dash-icone">👥</div>
      <div class="dash-titulo">Listar Vendedores</div>
      <div class="dash-desc">Vê todos os vendedores cadastrados</div>
    </a>
  </div>

  <!-- ── Módulo: Fornecedores (só admin) ── -->
  <p class="grupo-titulo">Fornecedores</p>
  <div class="dashboard-grade">
    <a href="fornecedor/cadastrar.php" class="dash-card">
      <div class="dash-icone">🏭</div>
      <div class="dash-titulo">Cadastrar Fornecedor</div>
      <div class="dash-desc">Cadastra fornecedor, endereço e produtos de uma vez</div>
    </a>
    <a href="fornecedor/ligar_produto.php" class="dash-card">
      <div class="dash-icone">🔗</div>
      <div class="dash-titulo">Vincular Produto</div>
      <div class="dash-desc">Adiciona um produto a um fornecedor já existente</div>
    </a>
  </div>

  <!-- ── Módulo: Estoque (só admin) ── -->
  <p class="grupo-titulo">Estoque</p> 
  <div class="dashboard-grade">
    <a href="estoque/entrada.php" class="dash-card">
      <div class="dash-icone">📥</div>
      <div class="dash-titulo">Entrada de Estoque</div>
      <div class="dash-desc">Registra entrada com ou sem fornecedor</div>
    </a>
    <a href="estoque/consulta.php" class="dash-card">
      <div class="dash-icone">🔍</div>
      <div class="dash-titulo">Consultar Estoque</div>
      <div class="dash-desc">Pesquisa por fornecedor ou por produto</div>
    </a>
  </div>

  <?php endif; ?> <!-- endif é usado para fechar a estrutura condicional iniciada por if. Se o usuário não for admin, os módulos dentro do bloco if serão ignorados e não aparecerão na dashboard. -->

</div><!-- fim do container -->
</body>
</html>
