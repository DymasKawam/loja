<?php
/*
 * nav.php — Barra de navegação dinâmica reutilizável
 *
 * Inclua este arquivo dentro do <body> de qualquer página para ter a navbar.
 * Defina as variáveis abaixo ANTES de incluir este arquivo:
 *
 *   $nav_base  = '../';        // caminho relativo até a raiz do projeto
 *   $nav_ativo = 'clientes';   // nome do item a destacar no menu
 *   include '../nav.php';
 *
 * Valores aceitos por $nav_ativo:
 *   'clientes', 'vendas', 'produtos', 'vendedores', 'fornecedores', 'estoque'
 */

// session_status() verifica se a sessão já foi iniciada nesta requisição
// PHP_SESSION_NONE indica que a sessão ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    // session_start() inicia (ou retoma) a sessão, tornando $_SESSION disponível
    session_start();
}

// ?? é o operador null coalescing: usa o valor de $nav_base se ele existir, ou '../' como padrão
// Variáveis com _ no início indicam uso interno deste arquivo, evitando conflito com o escopo que inclui nav.php
$_nav_base  = $nav_base  ?? '../';
// $nav_ativo define qual link do menu aparece destacado; padrão é '' (nenhum)
$_nav_ativo = $nav_ativo ?? '';
?>
<style>
  /* .badge-papel é a etiqueta colorida que mostra o perfil (admin/vendedor) do usuário logado */
  .badge-papel { display:inline-block; padding:.15rem .55rem; border-radius:999px;
    font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; vertical-align:middle; }
  /* badge-admin usa fundo azul claro com texto azul escuro */
  .badge-admin    { background:#dbeafe; color:#1d4ed8; }
  /* badge-vendedor usa fundo verde claro com texto verde escuro */
  .badge-vendedor { background:#dcfce7; color:#15803d; }
  /* .nav-usuario exibe nome e badge do usuário alinhados horizontalmente */
  .nav-usuario { display:flex; align-items:center; gap:.5rem; color:var(--text-muted,#64748b); font-size:.88rem; }
  /* .nav-sair é o link "Sair" em vermelho; !important sobrepõe estilos de menor prioridade */
  .nav-sair { color:var(--danger,#ef4444) !important; font-weight:600; }
  /* :hover aplica estilo ao passar o mouse — opacity:.8 deixa levemente transparente */
  .nav-sair:hover { opacity:.8; }
</style>

<!-- <nav class="navbar"> define a barra de navegação; "navbar" aplica os estilos do estilo.css -->
<nav class="navbar">
  <!-- Link da marca (logotipo) — leva ao painel principal usando o caminho base configurado -->
  <!-- <?= ?> é a forma curta de <?php echo ?>; imprime o valor da variável diretamente no HTML -->
  <a href="<?= $_nav_base ?>index.php" class="nav-brand">
    🛒 <span class="destaque">Loja</span> Sistema <!-- <span class="destaque"> aplica a cor de destaque ao texto "Loja" -->
  </a>

  <!-- <div class="nav-links"> agrupa todos os links de navegação do lado direito -->
  <div class="nav-links">
    <!-- Clientes e Vendas: visíveis para todos os perfis logados -->
    <!-- O operador ternário ?: imprime class="ativo" somente no link da página atual -->
    <a href="<?= $_nav_base ?>cliente/cadastrar.php"
       <?= $_nav_ativo === 'clientes' ? 'class="ativo"' : '' ?>>Clientes</a>
    <a href="<?= $_nav_base ?>venda/vender.php"
       <?= $_nav_ativo === 'vendas' ? 'class="ativo"' : '' ?>>Vendas</a>

    <!-- Bloco exclusivo para o perfil "admin" — verificado direto na $_SESSION -->
    <?php if (isset($_SESSION['usuario_papel']) && $_SESSION['usuario_papel'] === 'admin'): ?>
      <!-- isset() verifica se a chave existe em $_SESSION antes de comparar -->
      <a href="<?= $_nav_base ?>produto/cadastrar.php"
         <?= $_nav_ativo === 'produtos' ? 'class="ativo"' : '' ?>>Produtos</a>
      <a href="<?= $_nav_base ?>vendedor/cadastrar.php"
         <?= $_nav_ativo === 'vendedores' ? 'class="ativo"' : '' ?>>Vendedores</a>
      <a href="<?= $_nav_base ?>fornecedor/cadastrar.php"
         <?= $_nav_ativo === 'fornecedores' ? 'class="ativo"' : '' ?>>Fornecedores</a>
      <a href="<?= $_nav_base ?>estoque/entrada.php"
         <?= $_nav_ativo === 'estoque' ? 'class="ativo"' : '' ?>>Estoque</a>
    <?php endif; ?> <!-- endif fecha o bloco if do admin -->

    <!-- Exibe o nome e o badge de perfil do usuário logado -->
    <span class="nav-usuario">
      <!-- htmlspecialchars() converte < > & " em entidades HTML — evita XSS com nomes maliciosos -->
      <?= htmlspecialchars($_SESSION['usuario_nome'] ?? '') ?>
      <!-- class="badge-<?= ... ?>" monta dinamicamente a classe badge-admin ou badge-vendedor -->
      <span class="badge-papel badge-<?= $_SESSION['usuario_papel'] ?? '' ?>">
        <?= $_SESSION['usuario_papel'] ?? '' ?>
      </span>
    </span>
    <!-- Link de logout; class="nav-sair" aplica a cor vermelha definida no <style> acima -->
    <a href="<?= $_nav_base ?>logout.php" class="nav-sair">Sair 🚪</a>
  </div>
</nav>
