<?php
/*
 * index.php — Painel principal do sistema
 *
 * Exibe os módulos disponíveis de acordo com o perfil do usuário:
 *   admin    → todos os módulos
 *   vendedor → apenas Clientes e Vendas
 */
// require_once inclui auth.php apenas uma vez; disponibiliza exigir_login() e e_admin()
require_once __DIR__ . '/auth.php';
// exigir_login('') usa '' como base porque este arquivo está na raiz (sem subpasta)
exigir_login('');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel — Sistema Loja</title> <!-- Título exibido na aba do navegador -->
  <link rel="stylesheet" href="estilo.css"> <!-- Importa os estilos do projeto -->
  <style>
    /* Estilos do badge de perfil (admin/vendedor) na navbar inline deste arquivo */
    .badge-papel { display:inline-block; padding:.15rem .6rem; border-radius:999px; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; vertical-align:middle; }
    .badge-admin    { background:#dbeafe; color:#1d4ed8; } /* Azul para admin */
    .badge-vendedor { background:#dcfce7; color:#15803d; } /* Verde para vendedor */
    /* .nav-sair é o link "Sair" em vermelho na navbar */
    .nav-sair { color:var(--danger,#ef4444) !important; font-weight:600; }
    .nav-sair:hover { opacity:.8; } /* Leve transparência ao passar o mouse */
  </style>
</head>
<body>

<!-- Navbar inline (sem include de nav.php) para ter controle total do layout do painel -->
<nav class="navbar"> <!-- <nav> define a área de navegação; "navbar" aplica os estilos CSS -->
  <!-- Link da marca que leva ao próprio painel -->
  <a href="index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>

  <div class="nav-links"> <!-- Agrupa os links de navegação à direita -->
    <!-- Links visíveis para todos os perfis logados -->
    <a href="cliente/cadastrar.php">Clientes</a>
    <a href="venda/vender.php">Vendas</a>

    <?php if (e_admin()): ?> <!-- e_admin() retorna true quando o usuário tem perfil 'admin' -->
      <!-- Estes links só aparecem para administradores -->
      <a href="produto/cadastrar.php">Produtos</a>
      <a href="vendedor/cadastrar.php">Vendedores</a>
      <a href="fornecedor/cadastrar.php">Fornecedores</a>
      <a href="estoque/entrada.php">Estoque</a>
    <?php endif; ?>

    <!-- Exibe nome e badge do usuário logado -->
    <span style="margin-left:.5rem">
      <!-- htmlspecialchars() protege contra XSS — converte caracteres especiais em entidades HTML -->
      <?= htmlspecialchars(nome_usuario()) ?>
      <!-- badge-<?= papel_usuario() ?> monta dinamicamente a classe: badge-admin ou badge-vendedor -->
      <span class="badge-papel badge-<?= papel_usuario() ?>"><?= papel_usuario() ?></span>
    </span>
    <!-- Link de logout com estilo vermelho -->
    <a href="logout.php" class="nav-sair">Sair</a>
  </div>
</nav>

<!-- <div class="container"> limita a largura do conteúdo e adiciona margens laterais -->
<div class="container">

  <!-- Cabeçalho de boas-vindas do painel -->
  <div class="dash-header" style="margin-top:0.5rem">
    <h1>Painel do Sistema</h1>
    <!-- <strong> deixa o nome do usuário em negrito para destaque -->
    <p>Olá, <strong><?= htmlspecialchars(nome_usuario()) ?></strong>!
       Selecione uma área abaixo para começar.</p>
  </div>

  <!-- ── Módulo: Clientes (todos os perfis) ── -->
  <!-- <p class="grupo-titulo"> é um subtítulo de seção definido em estilo.css -->
  <p class="grupo-titulo">Clientes</p>
  <!-- <div class="dashboard-grade"> cria uma grade de cards lado a lado -->
  <div class="dashboard-grade">
    <!-- <a class="dash-card"> é um card clicável que funciona como link -->
    <a href="cliente/cadastrar.php" class="dash-card">
      <div class="dash-icone">➕</div>       <!-- Ícone grande do card -->
      <div class="dash-titulo">Cadastrar Cliente</div>  <!-- Título do card -->
      <div class="dash-desc">Adiciona um novo cliente com endereço</div> <!-- Descrição -->
    </a>
    <a href="cliente/listar.php" class="dash-card">
      <div class="dash-icone">📋</div>
      <div class="dash-titulo">Listar Clientes</div>
      <div class="dash-desc">Visualiza todos os clientes cadastrados</div>
    </a>
  </div>

  <!-- ── Módulo: Vendas (todos os perfis) ── -->
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

  <?php if (e_admin()): ?> <!-- Bloco exclusivo para administradores -->

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

  <?php endif; ?> <!-- Fim do bloco exclusivo para admin -->

</div><!-- fim do container -->
</body>
</html>
