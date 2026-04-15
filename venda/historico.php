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

// Busca todas as vendas com os dados de cliente, vendedor, produto e data
// Vários JOINs conectam as tabelas para montar o histórico completo em uma só query
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

$vendas = $pdo->query($sql)->fetchAll(); // Executa e guarda todas as vendas em um array
?>

<div class="container-largo">

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem">
    <a href="../index.html" class="link-voltar">← Início</a>
    <a href="vender.php" class="btn btn-primario">🛍️ Nova Venda</a>
  </div>

  <div class="card">
    <div class="card-topo">
      <h2>📜 Histórico de Vendas</h2>
      <p><?= count($vendas) ?> venda(s) registrada(s)</p>
    </div>

    <?php if (empty($vendas)): ?>
      <!-- Estado vazio — nenhuma venda registrada ainda -->
      <div class="estado-vazio">
        <div class="icone">🛍️</div>
        <p>Nenhuma venda registrada ainda.</p>
        <a href="vender.php" class="btn btn-primario" style="margin-top:1rem">Realizar a primeira venda</a>
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
            <?php foreach ($vendas as $v): ?>
              <?php
              // Calcula o total multiplicando o preço do produto pela quantidade vendida
              $total = $v['preco'] * $v['quantidade'];

              // Formata a data do banco (formato Y-m-d H:i:s) para o padrão brasileiro (dd/mm/aaaa hh:mm)
              $data = date('d/m/Y H:i', strtotime($v['data']));
              ?>
              <tr>
                <!-- ID da venda em formato de badge azul -->
                <td><span class="badge badge-azul">#<?= $v['id'] ?></span></td>
                <td style="color:var(--text-muted); font-size:0.875rem"><?= $data ?></td>
                <td><strong><?= htmlspecialchars($v['produto']) ?></strong></td>
                <td><?= $v['quantidade'] ?> un.</td>
                <td><?= htmlspecialchars($v['cliente']) ?></td>
                <td><?= htmlspecialchars($v['vendedor']) ?></td>
                <!-- Total formatado em reais com duas casas decimais -->
                <td style="font-weight:600; color:var(--primary)">
                  R$ <?= number_format($total, 2, ',', '.') ?>
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
