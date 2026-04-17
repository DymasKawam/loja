<?php

require_once '../auth.php';// Verifica se o usuário tem o papel "admin" para acessar esta página. Se o usuário não tiver permissão, ele será redirecionado ou receberá uma mensagem de acesso negado, garantindo que apenas administradores possam visualizar a lista de produtos cadastrados no sistema.
exigir_papel('admin');
?>

<!DOCTYPE html> <!-- Define o tipo de documento como HTML5 -->
<html lang="pt-BR"> <!-- Define o idioma da página como português do Brasil -->
<head> <!-- Início da seção de cabeçalho do documento, onde são incluídas informações sobre o documento, como metadados, título e links para arquivos de estilo. -->
  <meta charset="UTF-8"> <!-- Define a codificação de caracteres como UTF-8, garantindo que caracteres acentuados e especiais sejam exibidos corretamente na página. -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define a configuração de viewport para garantir que a página seja responsiva e se adapte a diferentes tamanhos de tela, especialmente em dispositivos móveis. -->
  <title>Produtos</title> <!-- Define o título da página, que é exibido na aba do navegador e usado para identificar a página. -->
  <link rel="stylesheet" href="../estilo.css"> <!-- Link para o arquivo de estilo CSS externo, que contém as regras de estilo para a aparência da página. O caminho "../estilo.css" indica que o arquivo está localizado um nível acima do diretório atual. -->
</head> 
<body> <!-- Início do corpo do documento, onde o conteúdo visível da página é colocado. -->

<nav class="navbar"> <!-- Início da barra de navegação, que contém links para diferentes seções do sistema. A classe "navbar" é usada para aplicar estilos específicos à barra de navegação, como layout, cores e espaçamento. -->
  <a href="../index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>
  <div class="nav-links">
    <a href="../cliente/cadastrar.php">Clientes</a>
    <a href="../produto/cadastrar.php" class="ativo">Produtos</a>
    <a href="../vendedor/cadastrar.php">Vendedores</a>
    <a href="../fornecedor/cadastrar.php">Fornecedores</a>
    <a href="../estoque/entrada.php">Estoque</a>
    <a href="../venda/vender.php">Vendas</a>
  </div>
</nav>

<?php 
include("../conexao.php"); // Inclui o arquivo de conexão com o banco de dados para permitir a execução de consultas SQL e operações de banco de dados necessárias para buscar e exibir a lista de produtos cadastrados no sistema, juntamente com as quantidades disponíveis em estoque.

// Busca todos os produtos junto com a quantidade atual do estoque
// LEFT JOIN garante que produtos sem registro de estoque também apareçam (mostra 0)
// COALESCE retorna 0 quando a quantidade está nula (produto sem estoque registrado)
$sql = "SELECT produto.*, COALESCE(estoque.quantidade, 0) AS quantidade
        FROM produto
        LEFT JOIN estoque ON estoque.id_produto = produto.id
        ORDER BY produto.nome"; // Ordem alfabética

$produtos = $pdo->query($sql)->fetchAll(); // Executa e guarda todos os resultados em um array associativo para uso posterior na exibição dos produtos na tabela.
?>

<div class="container-largo"> <!-- Container principal da página, com uma classe que provavelmente define uma largura maior e centraliza o conteúdo. -->

  <div style="display:flex; justify-content:space-between; align-items:center;  margin-bottom:1.25rem"> <!-- Container para os links de navegação e ações, usando flexbox para distribuir os elementos horizontalmente, com espaçamento entre eles e alinhamento vertical centralizado. -->
    <a href="../index.php" class="link-voltar">← Início</a>
    <a href="cadastrar.php" class="btn btn-primario">➕ Novo Produto</a>
  </div>

  <div class="card"> <!-- Card que contém a lista de produtos, com uma classe que provavelmente define um estilo de caixa com bordas, sombra e espaçamento interno para destacar o conteúdo. -->
    <div class="card-topo">  
      <h2>Produtos Cadastrados</h2>
      <p><?= count($produtos) ?> produto(s) no sistema</p>
    </div>

    <?php if (empty($produtos)): ?> <!-- Verifica se o array de produtos está vazio. Se estiver, exibe uma mensagem indicando que não há produtos cadastrados e um link para cadastrar o primeiro produto. -->
      <div class="estado-vazio"> <!-- Container para o estado vazio, com uma classe que provavelmente define um estilo para centralizar o conteúdo e aplicar estilos visuais que indicam que não há dados disponíveis. -->
        <div class="icone">📦</div> 
        <p>Nenhum produto cadastrado ainda.</p>
        <a href="cadastrar.php" class="btn btn-primario" style="margin-top:1rem">Cadastrar o primeiro</a>
      </div>

    <?php else: ?> <!-- Se houver produtos cadastrados, exibe uma tabela com os detalhes de cada produto, incluindo nome, descrição, preço de venda e quantidade em estoque. A tabela é formatada para destacar o nome do produto e usar cores para indicar a disponibilidade em estoque. -->
      <div class="tabela-wrapper"> <!-- Container para a tabela, com uma classe que provavelmente define estilos para tornar a tabela responsiva, como rolagem horizontal em telas menores e espaçamento adequado. -->
        <table> <!-- Tabela que exibe a lista de produtos, com colunas para nome, descrição, preço de venda e estoque. A tabela é formatada para destacar o nome do produto e usar cores para indicar a disponibilidade em estoque. -->
          <thead> <!-- Cabeçalho da tabela, que define os títulos das colunas. --> 
            <tr> <!-- Linha do cabeçalho da tabela, que contém as células de título para cada coluna. -->
              <th>Nome</th> 
              <th>Descrição</th>
              <th>Preço de venda</th>
              <th>Estoque</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($produtos as $p): ?> <!-- Loop que percorre cada produto no array de produtos e gera uma linha na tabela para cada um, exibindo os detalhes do produto, como nome, descrição, preço de venda e quantidade em estoque. O nome do produto é destacado em negrito, a descrição é exibida ou substituída por um traço se estiver vazia, o preço é formatado no padrão brasileiro e a quantidade em estoque é indicada com cores para mostrar se o produto está disponível ou esgotado. -->
              <tr>
                <td><strong><?= htmlspecialchars($p['nome']) ?></strong></td>
                <td><?= htmlspecialchars($p['descricao']) ?: '<span style="color:var(--text-muted)">—</span>' ?></td>
                <!-- number_format formata o preço com 2 casas decimais, vírgula e ponto no padrão BR -->
                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td> <!-- Formata o preço de venda do produto para o formato brasileiro, com duas casas decimais, vírgula como separador decimal e ponto como separador de milhares. -->
                <td>
                  <?php if ($p['quantidade'] > 0): ?> <!-- Verde quando tem estoque disponível -->
                    <span class="badge badge-verde"><?= $p['quantidade'] ?> un.</span>
                  <?php else: ?> <!-- Vermelho quando o produto está esgotado -->
                    <span class="badge badge-vermelho">Esgotado</span> <!-- Exibe um badge vermelho com a palavra "Esgotado" quando a quantidade em estoque é zero ou menor, indicando que o produto não está disponível para venda. -->
                  <?php endif; ?> 
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

  </div>
</div>
</body>
</html>
