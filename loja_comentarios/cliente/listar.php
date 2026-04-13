<?php
// require_once inclui auth.php apenas uma vez, disponibilizando as funções de autenticação
require_once '../auth.php';
// exigir_login() verifica se o usuário tem sessão ativa; caso contrário redireciona para login.php
exigir_login();
?>
<!DOCTYPE html> <!-- Declara que este documento usa HTML5 -->
<html lang="pt-BR"> <!-- Define o idioma da página como português do Brasil -->
<head>
  <meta charset="UTF-8"> <!-- Define a codificação de caracteres para suportar acentos e símbolos -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Torna a página responsiva em dispositivos móveis -->
  <title>Clientes</title> <!-- Texto exibido na aba do navegador -->
  <link rel="stylesheet" href="../estilo.css"> <!-- Importa o arquivo CSS do projeto; "../" sobe uma pasta (saindo de cliente/) -->
</head>
<body>

<!-- <nav> define a seção de navegação da página; a classe "navbar" aplica os estilos da barra de menu -->
<nav class="navbar">
  <!-- <a> cria um link; href="../index.php" aponta para o painel principal (subindo da pasta cliente/) -->
  <a href="../index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>
  <!-- <div class="nav-links"> agrupa os links de navegação no lado direito da barra -->
  <div class="nav-links">
    <a href="../cliente/cadastrar.php" class="ativo">Clientes</a> <!-- class="ativo" destaca este link como a página atual -->
    <a href="../produto/cadastrar.php">Produtos</a>
    <a href="../vendedor/cadastrar.php">Vendedores</a>
    <a href="../fornecedor/cadastrar.php">Fornecedores</a>
    <a href="../estoque/entrada.php">Estoque</a>
    <a href="../venda/vender.php">Vendas</a>
  </div>
</nav>

<?php
// include carrega conexao.php, criando a variável $pdo usada nas consultas abaixo
include("../conexao.php");

// SELECT com JOIN busca os dados do cliente e do seu endereço ao mesmo tempo
// JOIN conecta as tabelas "cliente" e "endereco" pelo campo de chave estrangeira endereco_cliente = endereco.id
// ORDER BY cliente.nome ordena os resultados em ordem alfabética
$sql = "SELECT cliente.*, endereco.rua, endereco.numero, endereco.cidade, endereco.estado
        FROM cliente
        JOIN endereco ON cliente.endereco_cliente = endereco.id
        ORDER BY cliente.nome";

// query() executa a consulta diretamente (sem parâmetros, pois não há entrada do usuário)
// fetchAll() retorna todos os registros de uma vez como um array associativo
$clientes = $pdo->query($sql)->fetchAll();
?>

<!-- <div class="container-largo"> limita a largura do conteúdo com mais espaço que o container normal -->
<div class="container-largo">

  <!-- Linha superior com link "Início" e botão "Novo Cliente" lado a lado -->
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem">
    <!-- Link corrigido: aponta para index.php (não index.html) -->
    <a href="../index.php" class="link-voltar">← Início</a>
    <!-- Botão que leva para o formulário de cadastro de novo cliente -->
    <a href="cadastrar.php" class="btn btn-primario">➕ Novo Cliente</a>
  </div>

  <!-- <div class="card"> é o componente de cartão branco com sombra definido no estilo.css -->
  <div class="card">
    <div class="card-topo"> <!-- Área de cabeçalho do cartão com título e subtítulo -->
      <h2>Clientes Cadastrados</h2>
      <!-- count() conta o número de elementos no array $clientes e exibe o total -->
      <p><?= count($clientes) ?> cliente(s) no sistema</p>
    </div>

    <?php if (empty($clientes)): ?> <!-- empty() retorna true quando o array está vazio (nenhum cliente cadastrado) -->
      <!-- Estado vazio: exibido somente quando não há clientes no banco -->
      <div class="estado-vazio">
        <div class="icone">👤</div>
        <p>Nenhum cliente cadastrado ainda.</p>
        <a href="cadastrar.php" class="btn btn-primario" style="margin-top:1rem">Cadastrar o primeiro</a>
      </div>

    <?php else: ?> <!-- else: executa este bloco quando há pelo menos um cliente -->
      <!-- <div class="tabela-wrapper"> adiciona scroll horizontal em telas pequenas -->
      <div class="tabela-wrapper">
        <table> <!-- <table> cria a tabela de dados -->
          <thead> <!-- <thead> agrupa o cabeçalho da tabela (linha com os títulos das colunas) -->
            <tr> <!-- <tr> define uma linha da tabela -->
              <th>Nome</th>       <!-- <th> é uma célula de cabeçalho (negrito e centralizado por padrão) -->
              <th>CPF</th>
              <th>Cidade / Estado</th>
              <th>Endereço</th>
            </tr>
          </thead>
          <tbody> <!-- <tbody> agrupa as linhas de dados da tabela -->
            <?php foreach ($clientes as $c): ?> <!-- foreach percorre cada cliente do array -->
              <tr> <!-- Uma linha para cada cliente -->
                <!-- htmlspecialchars() converte caracteres especiais (<, >, &, ") em entidades HTML
                     isso impede que dados do banco "quebrem" o HTML ou causem XSS -->
                <td><strong><?= htmlspecialchars($c['nome']) ?></strong></td> <!-- <td> é uma célula de dados; <strong> deixa o texto em negrito -->
                <td><?= htmlspecialchars($c['cpf']) ?></td>
                <td>
                  <!-- <span class="badge badge-azul"> é uma etiqueta colorida definida no estilo.css -->
                  <span class="badge badge-azul">
                    <?= htmlspecialchars($c['cidade']) ?> / <?= htmlspecialchars($c['estado']) ?>
                  </span>
                </td>
                <td><?= htmlspecialchars($c['rua']) ?>, <?= htmlspecialchars($c['numero']) ?></td>
              </tr>
            <?php endforeach; ?> <!-- endforeach fecha o loop foreach -->
          </tbody>
        </table>
      </div>
    <?php endif; ?> <!-- endif fecha o bloco if/else -->

  </div><!-- fim do card -->
</div><!-- fim do container -->

</body>
</html>
