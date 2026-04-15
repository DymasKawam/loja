<?php
//Require_once é uma maneira de incluir um arquivo PHP, mas com a garantia de que ele só será incluído uma vez durante a execução do script. Se o arquivo já tiver sido incluído antes, ele não será incluído novamente, evitando erros de redefinição de funções, classes ou variáveis. No caso do auth.php, isso é importante para garantir que as funções de autenticação e verificação de login sejam definidas apenas uma vez, mesmo que este script seja incluído em outros arquivos que também incluem auth.php.
require_once '../auth.php';
//exigir_login() é uma função definida no auth.php que verifica se o usuário está autenticado. Se o usuário não estiver logado, essa função geralmente redireciona para a página de login ou exibe uma mensagem de acesso negado. Isso é importante para proteger páginas que devem ser acessadas apenas por usuários autenticados, como o histórico de vendas neste caso.
exigir_login();
?>
<!DOCTYPE html> <!-- Declaração do tipo de documento HTML5 para garantir que o navegador renderize a página corretamente -->
<html lang="pt-BR"><!-- Define o idioma da página como português do Brasil para melhor acessibilidade e SEO -->
<head>  <!--A tag <head> contém metadados e links para recursos externos, como arquivos CSS. Ela é essencial para definir a estrutura e o estilo da página. -->
  <meta charset="UTF-8"> <!-- Define a codificação de caracteres para UTF-8, garantindo suporte a caracteres acentuados e especiais do português -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Garante que a página seja responsiva em dispositivos móveis -->
  <title>Histórico de Vendas</title> <!-- Título da página exibido na aba do navegador -->
  <link rel="stylesheet" href="../estilo.css"> <!-- Link para o arquivo de estilos CSS -->
</head>
<body> <!-- A tag <body> contém todo o conteúdo visível da página, como texto, imagens e links. É onde a estrutura principal da interface do usuário é construída. -->

<nav class="navbar"> <!-- nav é usado para definir uma seção de navegação. A classe "navbar" é  estilizada no CSS para criar um menu de navegação horizontal. -->
  <a href="../index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>
  <div class="nav-links">
    <a href="../cliente/cadastrar.php">Clientes</a>
    <a href="../produto/cadastrar.php">Produtos</a>
    <a href="../vendedor/cadastrar.php">Vendedores</a>
    <a href="../fornecedor/cadastrar.php">Fornecedores</a>
    <a href="../estoque/entrada.php">Estoque</a>
    <a href="../venda/vender.php" class="ativo">Vendas</a>
  </div>
</nav>

<?php
include("../conexao.php"); // Include é usado para incluir o arquivo de conexão com o banco de dados. Ele é necessário para executar consultas SQL e obter os dados necessários para exibir o histórico de vendas. O arquivo conexao.php contém a configuração de conexão e a criação do objeto PDO para interagir com o banco de dados.

// $sql é uma string que contém a consulta SQL para buscar o histórico de vendas. Ela utiliza várias junções (JOIN) para combinar dados de diferentes tabelas: venda, cliente, vendedor, item_venda e produto. A consulta seleciona informações como ID da venda, data, nome do cliente, nome do vendedor, nome do produto, preço e quantidade vendida. Os resultados são ordenados pela data da venda em ordem decrescente (da mais recente para a mais antiga). Essa consulta é fundamental para obter os dados necessários para exibir o histórico de vendas na interface do usuário.
$sql = "
    SELECT
        venda.id,
        venda.data,
        cliente.nome    AS cliente,
        vendedor.nome   AS vendedor,
        produto.nome    AS produto,
        produto.preco,
        item_venda.quantidade
    FROM venda
    JOIN cliente    ON cliente.id   = venda.id_cliente    -- conecta com a tabela de clientes
    JOIN vendedor   ON vendedor.id  = venda.id_vendedor   -- conecta com a tabela de vendedores
    JOIN item_venda ON item_venda.id_venda  = venda.id    -- conecta com os itens de cada venda
    JOIN produto    ON produto.id   = item_venda.id_produto -- conecta com a tabela de produtos
    ORDER BY venda.id DESC                                -- da venda mais recente para a mais antiga
";

$vendas = $pdo->query($sql)->fetchAll(); // $vendas é uma variável que armazena o resultado da consulta SQL executada. $pdo é o objeto de conexão com o banco de dados criado no arquivo conexão. query é um método do PDO que executa a consulta SQL e retorna um objeto de resultado. fetchAll() é um método que recupera todas as linhas do resultado da consulta e as armazena em um array associativo. Cada elemento do array representa uma venda, contendo informações como ID, data, cliente, vendedor, produto, preço e quantidade. Esse array é usado posteriormente para exibir o histórico de vendas na interface do usuário.
?>

<div class="container-largo">

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem">  
    <a href="../index.html" class="link-voltar">← Início</a>
    <a href="vender.php" class="btn btn-primario">🛍️ Nova Venda</a>
  </div>

  <div class="card"> <!-- class é um atributo HTML usado para atribuir uma ou mais classes a um elemento. No CSS, as classes são usadas para aplicar estilos específicos a elementos que compartilham a mesma classe. Neste caso, "card" é  uma classe definida no arquivo estilo.css que estiliza o contêiner para parecer um cartão, com bordas arredondadas, sombra e espaçamento interno. Isso ajuda a organizar visualmente o conteúdo do histórico de vendas, tornando-o mais agradável e fácil de ler. -->
    <div class="card-topo"> <!-- A classe "card-topo" é usada para estilizar a parte superior do cartão, onde o título e a descrição do histórico de vendas são exibidos. Ela pode incluir estilos como margens, fontes e cores para destacar essa seção do cartão. -->
      <h2>📜 Histórico de Vendas</h2> 
      <p><?= count($vendas) ?> venda(s) registrada(s)</p> <!-- A tag <p> exibe o número total de vendas registradas, usando a função count() para contar quantos elementos existem no array $vendas. O resultado é exibido dinamicamente na página, informando ao usuário quantas vendas foram feitas até o momento. -->
    </div>

    <?php if (empty($vendas)): ?> <!-- A função empty() é usada para verificar se o array $vendas está vazio, ou seja, se não há vendas registradas. Se o array estiver vazio, a condição será verdadeira e o código dentro do bloco if será executado, exibindo uma mensagem de estado vazio. Caso contrário, se houver vendas registradas, o código dentro do bloco else será executado, exibindo a tabela com o histórico de vendas. -->
      <!-- Estado vazio — nenhuma venda registrada ainda -->
      <div class="estado-vazio">
        <div class="icone">🛍️</div>
        <p>Nenhuma venda registrada ainda.</p>
        <a href="vender.php" class="btn btn-primario" style="margin-top:1rem">Realizar a primeira venda</a> <!-- A tag <a> é usada para criar um link que direciona o usuário para a página de realizar uma nova venda (vender.php). A classe "btn btn-primario" estiliza o link como um botão primário, tornando-o mais chamativo e incentivando o usuário a clicar para registrar a primeira venda.o style é usado para adicionar uma margem superior ao botão, criando um espaçamento visual entre o texto e o botão. -->
      </div>

    <?php else: ?>
      <div class="tabela-wrapper">
        <table>
          <thead>
            <tr>
              <th>Venda #</th>
              <th>Data</th>
              <th>Produto</th>
              <th>Qtd.</th>
              <th>Cliente</th>
              <th>Vendedor</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($vendas as $v): ?> <!-- foreach é uma estrutura de controle de loop em PHP usada para iterar sobre arrays. Neste caso, ela é usada para percorrer o array $vendas, onde cada elemento do array representa uma venda registrada. A variável $v é usada para representar cada venda individualmente dentro do loop, permitindo acessar os dados de cada venda e exibi-los na tabela do histórico de vendas. O loop continua até que todas as vendas no array tenham sido processadas e exibidas na tabela. -->
              <?php
              // $v é uma variável que representa cada venda individualmente dentro do loop foreach. Ela é um array associativo que contém os dados de uma venda específica, como ID, data, cliente, vendedor, produto, preço e quantidade. Dentro do loop, $v é usado para acessar esses dados e exibi-los na tabela do histórico de vendas. Por exemplo, $v['id'] acessa o ID da venda, $v['produto'] acessa o nome do produto vendido, e assim por diante. Cada linha da tabela é preenchida com os dados correspondentes de cada venda usando a variável $v.
              $total = $v['preco'] * $v['quantidade'];

              // %data é uma string de formato usada para formatar a data de venda. a função date() é usada para formatar a data de acordo com o formato 'd/m/t H:i' strtotime() é usada para converter a string de data do banco de dados em um timestamp Unix, que pode ser formatado pela função date(). O resultado é uma data legível no formato dia/mês/ano hora:minuto, facilitando a compreensão para o usuário. Por exemplo, se a data original for '2024-06-15 14:30:00', ela será exibida como '15/06/2024 14:30' na tabela do histórico de vendas.
              $data = date('d/m/Y H:i', strtotime($v['data']));
              ?>
              <tr> <!-- tr é usada para definir uma linha na tabela. cada representa uma tabela diferente do histórico de vendas. Dentro de cada linha, as tags <td> são usadas para definir as células que contêm os dados específicos de cada venda, como ID, data, produto, quantidade, cliente, vendedor e total. -->
                <!-- ID da venda em formato de badge azul -->
                <td><span class="badge badge-azul">#<?= $v['id'] ?></span></td>  <!-- td é usada para definir uma célula na tabela. -->
                <td style="color:var(--text-muted); font-size:0.875rem"><?= $data ?></td> <!-- style é usado para aplicar estilos CSS diretamente à célula da tabela, definindo a cor do texto como uma variável CSS (--text-muted) e o tamanho da fonte para 0.875rem,  <,?= $data ?> é usado para exibir a data formatada da venda. -->
                <td><strong><?= htmlspecialchars($v['produto']) ?></strong></td>  <!-- strong é usada para destacar o nome do produto em negrito. htmlspecialchars() é uma função que converte caracteres especiais em entidades HTML, prevenindo ataques de Cross-Site Scripting (XSS) e garantindo que o nome do produto seja exibido corretamente mesmo que contenha caracteres especiais. -->
                
                <td><?= $v['quantidade'] ?> un.</td> <!-- $v ['quantidade'] exibe a quantidade vendida do produto, seguida de "un." para indicar unidades. --> 
              
                <td><?= htmlspecialchars($v['cliente']) ?></td> <!-- <td> é usada para definir uma célula na tabela que exibe o nome do cliente associado à venda. htmlspecialchars() é uma função que converte caracteres especiais em entidades HTML, prevenindo ataques de Cross-Site Scripting (XSS) e garantindo que o nome do cliente seja exibido corretamente mesmo que contenha caracteres especiais. -->
               
                <td><?= htmlspecialchars($v['vendedor']) ?></td> <!-- <td> é usada para definir uma célula na tabela que exibe o nome do vendedor associado à venda. htmlspecialchars() é uma função que converte caracteres especiais em entidades HTML, prevenindo ataques de Cross-Site Scripting (XSS) e garantindo que o nome do vendedor seja exibido corretamente mesmo que contenha caracteres especiais. -->
                <!-- Total formatado em reais com duas casas decimais -->
                <td style="font-weight:600; color:var(--primary)"> <!-- style é usado para aplicar estilos CSS diretamente à célula da tabela, definindo o peso da fonte como 600 (semi-negrito) e a cor do texto como a variável CSS (--primary), que geralmente é usada para destacar elementos importantes. -->
                  R$ <?= number_format($total, 2, ',', '.') ?> <!-- number_format() é uma função que formata um número com as opções de casas decimais, separador decimal e separador de milhares. Neste caso, $total é formatado para exibir 2 casas decimais, usando a vírgula como separador decimal e o ponto como separador de milhares, seguindo o formato monetário brasileiro. O resultado é exibido com o símbolo "R$" para indicar que se trata de um valor em reais. -->
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
