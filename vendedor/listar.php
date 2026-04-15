<?php
//require_once é uma construção do PHP que inclui e avalia o arquivo especificado durante a execução do script. Se o arquivo não for encontrado ou tiver erros, require_once gerará um erro fatal e interromperá a execução do script. Neste caso, auth.php é incluído para garantir que as funções de autenticação e autorização estejam disponíveis para este script. A função exigir_papel() é chamada para verificar se o usuário logado tem o papel de 'admin'. Se o usuário não for um administrador, ele será redirecionado para a página de acesso negado, garantindo que apenas usuários com permissões adequadas possam acessar esta página de listagem de vendedores.
require_once '../auth.php';
exigir_papel('admin'); // Verifica se o usuário tem papel de admin, caso contrário redireciona para acesso negado
?>
<!DOCTYPE html> <!-- Declaração do tipo de documento HTML5 -->
<html lang="pt-BR"> <!-- Define o idioma da página como português do Brasil -->
<head> <!-- Elemento head contém metadados e links para recursos externos -->
  <meta charset="UTF-8"> <!-- Define a codificação de caracteres como UTF-8, que suporta caracteres acentuados e especiais usados no português -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configura a viewport para garantir que a página seja responsiva em dispositivos móveis -->
  <title>Vendedores</title> <!-- Define o título da página que aparece na aba do navegador -->
  <link rel="stylesheet" href="../estilo.css"> <!-- Link para o arquivo de estilo CSS que contém as regras de estilo para a página -->
</head> 
<body> <!-- Elemento body contém o conteúdo visível da página -->

<nav class="navbar"> <!--nav é um elemento HTML5 usado para definir uma seção de navegação. A classe "navbar" é usada para aplicar estilos específicos a esta barra de navegação, como layout, cores e espaçamento. -->
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

<?php 
include("../conexao.php"); // Inclui o arquivo de conexão com o banco de dados para permitir a execução de consultas SQL. O arquivo conexao.php contém a configuração e a criação da instância PDO para se conectar ao banco de dados, permitindo que este script execute consultas para buscar os vendedores cadastrados.

// Executa uma consulta SQL para selecionar todos os vendedores da tabela "vendedor", ordenados por nome. O método query() é usado para executar a consulta, e fetchAll() é uma função que retorna todos os resultados. O resultado é armazenado na variável $vendedores como um array de vendedores, onde cada vendedor é representado como um array associativo com as colunas da tabela (por exemplo, 'id' e 'nome').
$vendedores = $pdo->query("SELECT * FROM vendedor ORDER BY nome")->fetchAll();
?>

<div class="container-largo">

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem">
    <a href="../index.php" class="link-voltar">← Início</a>
    <a href="cadastrar.php" class="btn btn-primario">➕ Novo Vendedor</a>
  </div>

  <div class="card">
    <div class="card-topo">
      <h2>Vendedores Cadastrados</h2>
      <p><?= count($vendedores) ?> vendedor(es) no sistema</p> <!-- Exibe a quantidade de vendedores cadastrados usando a função count() para contar os elementos do array $vendedores. O resultado é exibido dentro de um parágrafo para informar ao usuário quantos vendedores estão registrados no sistema. -->
    </div>

    <?php if (empty($vendedores)): ?>
      <div class="estado-vazio">
        <div class="icone">🧑‍💼</div>
        <p>Nenhum vendedor cadastrado ainda.</p>
        <a href="cadastrar.php" class="btn btn-primario" style="margin-top:1rem">Cadastrar o primeiro</a> <!--O atributo class em HTML é usado para agrupar e identificar elementos, permitindo aplicar estilos CSS ou comportamentos JavaScript a múltiplos elementos de uma só vez. style é um atributo que permite aplicar estilos CSS diretamente a um elemento HTML. -->
      </div>

    <?php else: ?> <!-- else é usado para definir um bloco de código que será executado se a condição do if for falsa. Neste caso, se o array $vendedores não estiver vazio (ou seja, se houver vendedores cadastrados), o código dentro do bloco else será executado para exibir a tabela com os vendedores. -->
      <div class="tabela-wrapper"> <!-- A classe "tabela-wrapper" é usada para aplicar estilos específicos à tabela, como rolagem horizontal em telas menores, garantindo que a tabela seja responsiva e fácil de ler em diferentes dispositivos. -->
        <table> <!-- Elemento table é usado para criar uma tabela HTML. Ele contém os elementos thead para o cabeçalho da tabela e tbody para o corpo da tabela, onde os dados dos vendedores serão exibidos. -->
          <thead> <!-- Elemento thead é usado para agrupar o conteúdo do cabeçalho da tabela. Ele contém uma linha (tr) com células de cabeçalho (th) que definem os títulos das colunas da tabela, como "ID" e "Nome". -->
            <tr> <!-- Elemento tr é usado para definir uma linha na tabela. Neste caso, ele contém as células de cabeçalho (th) que definem os títulos das colunas da tabela. -->
              <th>#</th> <!-- O símbolo # é usado para indicar que esta coluna contém identificadores (IDs) dos vendedores. -->
              <th>Nome</th> <!-- O título da coluna "Nome" é exibido dentro de uma célula de cabeçalho (th). -->
            </tr>
          </thead>
          <tbody> <!-- Elemento tbody é usado para agrupar o conteúdo do corpo da tabela, onde os dados dos vendedores serão exibidos. Ele contém várias linhas (tr), cada uma representando um vendedor, e dentro de cada linha, há células (td) que exibem os detalhes do vendedor, como ID e nome. -->
            <?php foreach ($vendedores as $v): ?>
              <tr> <!-- Elemento tr é usado para definir uma linha na tabela. Neste caso, ele contém as células (td) que exibem os detalhes do vendedor. -->
                <!-- A célula que exibe o ID do vendedor tem um estilo inline que define a cor do texto como uma variável CSS (--text-muted) para dar um tom mais suave, e uma largura fixa de 60 pixels para garantir que os IDs fiquem alinhados e não ocupem muito espaço na tabela. O ID é exibido com um símbolo # antes do número para indicar que é um identificador. -->
                <td style="color:var(--text-muted); width:60px">#<?= $v['id'] ?></td>
                <td><strong><?= htmlspecialchars($v['nome']) ?></strong></td> <!-- A função htmlspecialchars() é usada para converter caracteres especiais em entidades HTML, garantindo que o nome do vendedor seja exibido de forma segura, evitando problemas de segurança como XSS (Cross-Site Scripting). O nome do vendedor é exibido dentro de uma tag strong para dar ênfase visual. -->
              </tr> <!-- Elemento tr é fechado para indicar o fim da linha que representa um vendedor. -->
            <?php endforeach; ?> <!-- O loop foreach é usado para iterar sobre o array $vendedores, onde cada elemento do array é atribuído à variável $v em cada iteração. O código dentro do loop é executado para cada vendedor, criando uma nova linha na tabela com os detalhes do vendedor. O loop é fechado com endforeach para indicar o fim da estrutura de repetição. -->
          </tbody><!-- Elemento tbody é fechado para indicar o fim do corpo da tabela. -->
        </table><!-- Elemento table é fechado para indicar o fim da tabela HTML. -->
      </div><!-- Elemento div com a classe "tabela-wrapper" é fechado para indicar o fim do contêiner que envolve a tabela. -->
    <?php endif; ?> <!-- O bloco if-else é fechado para indicar o fim da estrutura de controle que verifica se há vendedores cadastrados e exibe a tabela ou a mensagem de estado vazio, dependendo do resultado da verificação. -->

  </div>
</div>
</body>
</html>
