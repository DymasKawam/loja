<?php
/*
 * nav.php — Barra de navegação dinâmica
 *
 * Inclua este arquivo dentro do <body> de qualquer página para ter
 * a navbar com controle de acesso e botão de sair.
 *
 * USO (em páginas dentro de subpastas como cliente/, venda/ etc.):
 *   <?php
 *     $nav_base = '../';          // caminho até a raiz do projeto
 *     $nav_ativo = 'clientes';    // nome do item ativo no menu
 *     include '../nav.php';
 *   ?>
 *
 * Valores possíveis para $nav_ativo:
 *   'clientes', 'vendas', 'produtos', 'vendedores', 'fornecedores', 'estoque'
 */

// Garante que auth está carregado (seguro chamar session_start() mais de uma vez)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_nav_base  = $nav_base  ?? '../';
$_nav_ativo = $nav_ativo ?? '';
?>
<style>
  .badge-papel { display:inline-block; padding:.15rem .55rem; border-radius:999px;
    font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; vertical-align:middle; }
  .badge-admin    { background:#dbeafe; color:#1d4ed8; }
  .badge-vendedor { background:#dcfce7; color:#15803d; }
  .nav-usuario { display:flex; align-items:center; gap:.5rem; color:var(--text-muted,#64748b); font-size:.88rem; }
  .nav-sair { color:var(--danger,#ef4444) !important; font-weight:600; }
  .nav-sair:hover { opacity:.8; }
</style>

<nav class="navbar">
  <a href="<?= $_nav_base ?>index.php" class="nav-brand">
    🛒 <span class="destaque">Loja</span> Sistema
  </a>

  <div class="nav-links">
    <!-- Clientes e Vendas: todos os papéis -->
    <a href="<?= $_nav_base ?>cliente/cadastrar.php"
       <?= $_nav_ativo === 'clientes' ? 'class="ativo"' : '' ?>>Clientes</a>
    <a href="<?= $_nav_base ?>venda/vender.php"
       <?= $_nav_ativo === 'vendas' ? 'class="ativo"' : '' ?>>Vendas</a>

    <?php if (isset($_SESSION['usuario_papel']) && $_SESSION['usuario_papel'] === 'admin'): ?>
      <!-- Somente admin -->
      <a href="<?= $_nav_base ?>produto/cadastrar.php"
         <?= $_nav_ativo === 'produtos' ? 'class="ativo"' : '' ?>>Produtos</a>
      <a href="<?= $_nav_base ?>vendedor/cadastrar.php"
         <?= $_nav_ativo === 'vendedores' ? 'class="ativo"' : '' ?>>Vendedores</a>
      <a href="<?= $_nav_base ?>fornecedor/cadastrar.php"
         <?= $_nav_ativo === 'fornecedores' ? 'class="ativo"' : '' ?>>Fornecedores</a>
      <a href="<?= $_nav_base ?>estoque/entrada.php"
         <?= $_nav_ativo === 'estoque' ? 'class="ativo"' : '' ?>>Estoque</a>
    <?php endif; ?>

    <!-- Usuário logado + sair -->
    <span class="nav-usuario">
      <?= htmlspecialchars($_SESSION['usuario_nome'] ?? '') ?>
      <span class="badge-papel badge-<?= $_SESSION['usuario_papel'] ?? '' ?>">
        <?= $_SESSION['usuario_papel'] ?? '' ?>
      </span>
    </span>
    <a href="<?= $_nav_base ?>logout.php" class="nav-sair">Sair 🚪</a>
  </div>
</nav>
