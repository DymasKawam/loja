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

<nav class="navbar">
   <!-- <nav> é usada para definir uma seção de navegação em um documento HTML. A classe é uma classe CSS que pode ser usada para estilizar o elemento "navbar" é usada para aplicar estilos específicos a essa barra de navegação. -->
  <a href="<?= $_nav_base ?>index.php" class="nav-brand">
     <!-- <a> é usada para criar um link em HTML. O atributo href especifica o destino do link, que neste caso é a página index.php localizada no caminho definido por $_nav_base. A classe nav-brand é usada para estilizar o elemento como a marca ou logotipo da barra de navegação. -->
    🛒 <span class="destaque">Loja</span> Sistema 
    <!-- <span> é usada para criar um elemento de texto que pode ser estilizado com CSS. -->
  </a>

  <div class="nav-links"> <!-- <div> é usada para criar um elemento de contêiner em HTML. A classe nav-links é usada para estilizar o elemento como os links de navegação. -->
    <!-- Clientes e Vendas: todos os papéis -->
    <!--<?= $_nav_ativo === 'clientes' ? 'class="ativo"' : '' ?> é uma expressão PHP que verifica se a variável $_nav_ativo é igual a 'clientes'. Se for, ela retorna class="ativo", o que adiciona a classe CSS "ativo" ao link, destacando-o como a página atual. Se não for, retorna uma string vazia, sem adicionar nenhuma classe. -->    

    <a href="<?= $_nav_base ?>cliente/cadastrar.php" 
       <?= $_nav_ativo === 'clientes' ? 'class="ativo"' : '' ?>>Clientes</a>
    <a href="<?= $_nav_base ?>venda/vender.php"
       <?= $_nav_ativo === 'vendas' ? 'class="ativo"' : '' ?>>Vendas</a>

    <?php if (isset($_SESSION['usuario_papel']) && $_SESSION['usuario_papel'] === 'admin'): ?> <!--
    (isset) é uma função que verifica se uma variável está definida e não é nula. $_SESSION['usuario_papel'] é a variável de sessão que armazena o papel do usuário logado. A condição verifica se o papel do usuário é 'admin', e se for, exibe os links adicionais para produtos, vendedores, fornecedores e estoque. && é um operador lógico que significa "e", ou seja, ambas as condições devem ser verdadeiras para que o bloco de código dentro do if seja executado. -->
      <!-- Somente admin -->
      <a href="<?= $_nav_base ?>produto/cadastrar.php" 
         <?= $_nav_ativo === 'produtos' ? 'class="ativo"' : '' ?>>Produtos</a>
         <a href="<?= $_nav_base ?>vendedor/cadastrar.php" 
         <?= $_nav_ativo === 'vendedores' ? 'class="ativo"' : '' ?>>Vendedores</a>
      <a href="<?= $_nav_base ?>fornecedor/cadastrar.php"
         <?= $_nav_ativo === 'fornecedores' ? 'class="ativo"' : '' ?>>Fornecedores</a>
      <a href="<?= $_nav_base ?>estoque/entrada.php"
         <?= $_nav_ativo === 'estoque' ? 'class="ativo"' : '' ?>>Estoque</a>  
         <!-- serve para inserir o resultado de uma expressão PHP diretamente no HTML. $_nav_ativo é uma variável que deve ser definida em cada página para indicar qual link deve ser destacado como ativo.
         -
    <?php endif; ?> <!-- endif é usado para fechar a estrutura condicional iniciada pelo if. Ele indica o fim do bloco de código que deve ser executado se a condição do if for verdadeira. -->

    <!-- Usuário logado + sair -->
    <span class="nav-usuario"> <!-- <span> é usado para exibir o nome do usuário logado e seu papel. A classe nav-usuario é usada para estilizar esse elemento. -->
      <?= htmlspecialchars($_SESSION['usuario_nome'] ?? '') ?> <!-- htmlspecialchars() é uma função que converte caracteres especiais em entidades HTML, prevenindo ataques de Cross-Site Scripting (XSS). $_SESSION['usuario_nome'] é a variável de sessão que armazena o nome do usuário logado. O operador ?? é usado para fornecer um valor padrão (neste caso, uma string vazia) caso a variável não esteja definida ou seja nula. -->
      <span class="badge-papel badge-<?= $_SESSION['usuario_papel'] ?? '' ?>"> <!-- <span> é uma tag HTML usada para agrupar e estilizar um trecho de texto. A classe badge-papel é usada para estilizar o papel do usuário, enquanto badge-<?= $_SESSION['usuario_papel'] ?? '' ?> adiciona uma classe específica com base no papel do usuário (por exemplo, badge-admin ou badge-vendedor). O operador ?? é usado para fornecer um valor padrão (neste caso, uma string vazia) caso a variável $_SESSION['usuario_papel'] não esteja definida ou seja nula. -->
        <?= $_SESSION['usuario_papel'] ?? '' ?> <!-- $_session é uma variavel de sessão que armazena informações sobre o usuário logado, como nome e papel. O operador ?? é usado para fornecer um valor padrão (neste caso, uma string vazia) -->
      </span> 
    </span>
    <a href="<?= $_nav_base ?>logout.php" class="nav-sair">Sair 🚪</a> <!-- <a> é usado para criar um link para a página de logout.A classe nav-sair é usada para estilizar esse link, geralmente destacando-o em vermelho para indicar que é uma ação de saída. -->
  </div>
</nav>
