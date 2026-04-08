<?php
/*
 * index.php — Painel principal do sistema
 *
 * Substitui o index.html. Verifica login e exibe apenas os módulos
 * permitidos para o papel do usuário:
 *   admin    → tudo
 *   vendedor → Clientes e Vendas
 */
require_once __DIR__ . '/auth.php';
exigir_login('');   // raiz do projeto, sem prefixo de pasta
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel — Sistema Loja</title>
  <link rel="stylesheet" href="estilo.css">
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

<!-- ── Navbar ────────────────────────────────────────────── -->
<nav class="navbar">
  <a href="index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>

  <div class="nav-links">
    <!-- Links visíveis para todos os usuários logados -->
    <a href="cliente/cadastrar.php">Clientes</a>
    <a href="venda/vender.php">Vendas</a>

    <?php if (e_admin()): ?>
      <!-- Links exclusivos do admin -->
      <a href="produto/cadastrar.php">Produtos</a>
      <a href="vendedor/cadastrar.php">Vendedores</a>
      <a href="fornecedor/cadastrar.php">Fornecedores</a>
      <a href="estoque/entrada.php">Estoque</a>
    <?php endif; ?>

    <!-- Nome + papel + botão sair -->
    <span style="margin-left:.5rem">
      <?= htmlspecialchars(nome_usuario()) ?>
      <span class="badge-papel badge-<?= papel_usuario() ?>">
        <?= papel_usuario() ?>
      </span>
    </span>
    <a href="logout.php" class="nav-sair">Sair</a>
  </div>
</nav>

<!-- ── Conteúdo principal ────────────────────────────────── -->
<div class="container">

  <div class="dash-header" style="margin-top:0.5rem">
    <h1>Painel do Sistema</h1>
    <p>Olá, <strong><?= htmlspecialchars(nome_usuario()) ?></strong>!
       Selecione uma área abaixo para começar.</p>
  </div>

  <!-- ── Módulo: Clientes (todos os papéis) ── -->
  <p class="grupo-titulo">Clientes</p>
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

  <?php if (e_admin()): ?>

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

  <?php endif; ?>

</div><!-- fim do container -->
</body>
</html>
