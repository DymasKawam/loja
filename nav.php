<?php
//if é uma estrutra Condicional. Session_status() vai verificar a sessao. 'PHP_SESSION_NONE é um valor que indica que a sessão não foi iniciada. session_start() inicia a sessão, permitindo acessar variáveis de sessão como $_SESSION['usuario_nome'] e $_SESSION['usuario_papel'].
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//$nav_ base serve para garantir que links funcionem corretamente. ?? serve para definifir um valor padrao caso a variavel seja nula. '../' serve para voltar um nível no diretório, o que é útil para garantir que os links funcionem corretamente independentemente de onde a página atual esteja localizada na estrutura de diretórios do projeto.
$_nav_base  = $nav_base  ?? '../';
//$nav_ativo é usado para destacar a página atual no menu. Ele deve ser definido em cada página antes de incluir nav.php.
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

<nav class="navbar"> <!-- <nav> é usada para definir uma seção de navegação em um documento HTML. A classe é uma classe CSS que pode ser usada para estilizar o elemento "navbar" é usada para aplicar estilos específicos a essa barra de navegação. -->
  <a href="<?= $_nav_base ?>index.php" class="nav-brand"> <!-- <a> é usada para criar um link em HTML. O atributo href especifica o destino do link, que neste caso é a página index.php localizada no caminho definido por $_nav_base. A classe nav-brand é usada para estilizar o elemento como a marca ou logotipo da barra de navegação. -->
    🛒 <span class="destaque">Loja</span> Sistema <!-- <span> é usada para criar um elemento de texto que pode ser estilizado com CSS. -->
  </a>

  <div class="nav-links"> <!-- <div> é usada para criar um elemento de contêiner em HTML. A classe nav-links é usada para estilizar o elemento como os links de navegação. -->
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
