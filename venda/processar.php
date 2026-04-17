<?php
include("../conexao.php"); // Inclui o arquivo de conexão com o banco de dados para permitir a execução de consultas SQL e transações necessárias para processar a venda.

// if é uma estrutura de controle de fluxo que verifica se as variáveis 'produto', 'quantidade', 'cliente' e 'vendedor' estão definidas no array $_POST. Se alguma dessas variáveis não estiver definida, a função die() é chamada para interromper a execução do script e exibir a mensagem "Dados incompletos! Volte e preencha todos os campos.". Isso garante que o script só continue a processar a venda se todas as informações necessárias forem fornecidas pelo formulário e isset é uma função que verifica se uma variável está definida e não é nula, garantindo que os dados necessários para processar a venda estejam presentes antes de prosseguir.
if (!isset($_POST['produto'], $_POST['quantidade'], $_POST['cliente'], $_POST['vendedor'])) {
    die("Dados incompletos! Volte e preencha todos os campos.");
}

// Converte os dados recebidos do formulário para os tipos adequados antes de usar nas consultas SQL. (int) é usado para converter os valores para inteiros, garantindo que sejam do tipo correto para as operações de banco de dados e cálculos posteriores.
$idProduto  = (int) $_POST['produto']; 
$qtdCompra  = (int) $_POST['quantidade'];
$idCliente  = (int) $_POST['cliente'];
$idVendedor = (int) $_POST['vendedor'];

// Validações básicas para garantir que os IDs sejam positivos e a quantidade seja maior que zero. Se alguma dessas condições não for atendida, a função die() é chamada para interromper a execução do script e exibir uma mensagem de erro correspondente. Isso ajuda a prevenir dados inválidos ou maliciosos de serem processados.
if ($qtdCompra <= 0) {
    die("Quantidade inválida! O valor precisa ser maior que zero.");
}

// 1. Consulta o estoque atual do produto ANTES de abrir a transação
// Isso evita travar o banco desnecessariamente se o estoque já for insuficiente
$stmt = $pdo->prepare("SELECT quantidade FROM estoque WHERE id_produto = :id");
$stmt->execute([':id' => $idProduto]);
$estoque = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não encontrou registro de estoque para esse produto, algo está errado
if (!$estoque) {
    die("Produto não encontrado no estoque.");
}

// Compara o que o cliente quer com o que tem disponível
if ($estoque['quantidade'] < $qtdCompra) {
    die("Estoque insuficiente! Disponível: {$estoque['quantidade']} unidade(s).");
}

try { //try é usado para envolver um bloco de código que pode gerar uma exceção. Se uma exceção for lançada dentro do bloco try, a execução do código é interrompida e o controle é transferido para o bloco catch correspondente, onde a exceção pode ser tratada de forma adequada.
    $pdo->beginTransaction(); //pdo é a variável que representa a conexão com o banco de dados usando PDO (PHP Data Objects). beginTransaction() é um método que inicia uma nova transação no banco de dados. Isso significa que todas as operações de banco de dados executadas após essa chamada serão tratadas como parte de uma única transação, permitindo que sejam confirmadas ou revertidas juntas, garantindo a integridade dos dados durante o processo de venda.

    // stmt2 é uma variável que armazena a consulta SQL preparada para inserir uma nova venda na tabela "venda". prepare() é um método do objeto PDO que prepara a consulta para execução, permitindo o uso de parâmetros nomeados (:c e :v) para evitar injeção de SQL. execute() é chamado em seguida para executar a consulta, passando um array associativo que vincula os parâmetros nomeados aos valores reais ($idCliente e $idVendedor) que serão inseridos na tabela. Isso registra a venda com o cliente e vendedor correspondentes no banco de dados.
    $stmt2 = $pdo->prepare("INSERT INTO venda (id_cliente, id_vendedor) VALUES (:c, :v)");
    $stmt2->execute([':c' => $idCliente, ':v' => $idVendedor]);

    $idVenda = $pdo->lastInsertId(); // idVenda é uma variável que armazena o ID da venda recém-inserida na tabela "venda". lastInsertId() é um método do objeto PDO que retorna o ID da última linha inserida no banco de dados. Isso é útil para obter o ID da venda que acabou de ser registrada, permitindo que seja usado posteriormente para associar os itens vendidos a essa venda específica.

    //$stmt3 é uma variável que armazena a consulta SQL preparada para inserir um novo item de venda na tabela "item_venda". prepare() é um método do objeto PDO que prepara a consulta para execução, permitindo o uso de parâmetros nomeados (:venda, :prod e :qtd) para evitar injeção de SQL. Insert INTO item_venda (id_venda, id_produto, quantidade) VALUES (:venda, :prod, :qtd) é a consulta SQL que insere um novo registro na tabela "item_venda", associando o ID da venda ($idVenda), o ID do produto ($idProduto) e a quantidade vendida ($qtdCompra). VALUES (:venda, :prod, :qtd) indica que os valores a serem inseridos serão fornecidos por meio dos parâmetros nomeados. execute() é chamado em seguida para executar a consulta, passando um array associativo que vincula os parâmetros nomeados aos valores reais. Isso registra o item vendido na tabela "item_venda", associando-o à venda correspondente.
    $stmt3 = $pdo->prepare("INSERT INTO item_venda (id_venda, id_produto, quantidade)
                            VALUES (:venda, :prod, :qtd)");
    $stmt3->execute([':venda' => $idVenda, ':prod' => $idProduto, ':qtd' => $qtdCompra]);

    // 4. stmt4 é uma variável que armazena a consulta SQL preparada para atualizar a quantidade do produto no estoque. prepare() é um método do objeto PDO que prepara a consulta para execução, permitindo o uso de parâmetros nomeados (:qtd e :id) para evitar injeção de SQL. UPDATE estoque SET quantidade = quantidade - :qtd WHERE id_produto = :id é a consulta SQL que atualiza a tabela "estoque", subtraindo a quantidade vendida (:qtd) da quantidade atual do produto identificado por :id. execute() é chamado em seguida para executar a consulta, passando um array associativo que vincula os parâmetros nomeados aos valores reais ($qtdCompra e $idProduto). Isso reduz a quantidade disponível do produto no estoque de acordo com a venda realizada.
    $stmt4 = $pdo->prepare("UPDATE estoque SET quantidade = quantidade - :qtd
                            WHERE id_produto = :id");
    $stmt4->execute([':qtd' => $qtdCompra, ':id' => $idProduto]);

    // 5. stmt5 é uma variável que armazena a consulta SQL preparada para selecionar o nome e preço do produto vendido. prepare() é um método do objeto PDO que prepara a consulta para execução, permitindo o uso de parâmetros nomeados (:id) para evitar injeção de SQL. SELECT nome, preco FROM produto WHERE id = :id é a consulta SQL que seleciona o nome e preço do produto da tabela "produto" onde o ID do produto corresponde ao valor fornecido por :id. execute() é chamado em seguida para executar a consulta, passando um array associativo que vincula o parâmetro nomeado :id ao valor real $idProduto. fetch(PDO::FETCH_ASSOC) é usado para obter os resultados da consulta como um array associativo, permitindo acessar os valores do nome e preço do produto usando as chaves 'nome' e 'preco'. Isso é útil para exibir as informações do produto no resumo da venda.
    $stmt5 = $pdo->prepare("SELECT nome, preco FROM produto WHERE id = :id");
    $stmt5->execute([':id' => $idProduto]);
    $produto = $stmt5->fetch(PDO::FETCH_ASSOC);

    $pdo->commit(); // commit() é um método do objeto PDO que confirma a transação iniciada anteriormente com beginTransaction(). Isso significa que todas as operações de banco de dados executadas desde o início da transação serão permanentemente aplicadas ao banco de dados. Se todas as etapas do processo de venda foram concluídas com sucesso, commit() é chamado para garantir que as alterações sejam salvas no banco de dados. Se alguma etapa falhar, a transação pode ser revertida usando rollBack() para desfazer todas as alterações

    //$total é uma variável que calcula o valor total da venda multiplicando o preço unitário do produto ($produto['preco']) pela quantidade comprada ($qtdCompra). Isso fornece o valor total que o cliente deve pagar pela quantidade de produto adquirida, permitindo exibir esse valor no resumo da venda para o cliente.
    $total = $produto['preco'] * $qtdCompra;

} catch (Exception $e) { // catch é usado para capturar e tratar exceções que possam ocorrer durante a execução do bloco try. Se uma exceção for lançada dentro do bloco try, a execução do código é interrompida e o controle é transferido para o bloco catch correspondente, onde a exceção pode ser tratada de forma adequada. No caso deste código, se ocorrer qualquer erro durante o processo de venda (como falha na inserção de dados ou atualização do estoque), a transação será revertida usando rollBack() para garantir que o banco de dados permaneça consistente, e uma mensagem de erro será exibida ao usuário.
    $pdo->rollBack(); 
    die("Erro ao processar venda: " . $e->getMessage());
} 
?>
<!DOCTYPE html> <!-- Declaração do tipo de documento HTML5, indicando que o conteúdo da página é estruturado usando a linguagem HTML. -->
<html lang="pt-BR"> <!-- Elemento raiz do documento HTML, com o atributo lang definido como "pt-BR" para indicar que o idioma principal da página é o português do Brasil. -->
<head> <!-- Elemento de cabeçalho do documento HTML, onde são definidas as metatags, título da página e links para arquivos de estilo. -->
  <meta charset="UTF-8"> <!-- Define a codificação de caracteres do documento como UTF-8, garantindo que caracteres acentuados e especiais sejam exibidos corretamente. -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define a configuração de visualização para dispositivos móveis, garantindo que a página seja responsiva e se ajuste corretamente em diferentes tamanhos de tela. -->
  <title>Venda Realizada</title> <!-- Define o título da página, que é exibido na aba do navegador e em resultados de pesquisa. -->
  <link rel="stylesheet" href="../estilo.css"> <!-- Link para o arquivo de estilo CSS externo, que contém as regras de estilo para a aparência da página. O caminho "../estilo.css" indica que o arquivo está localizado um nível acima do diretório atual. -->
</head><!-- O elemento head é fechado aqui, indicando o fim da seção de cabeçalho do documento HTML. -->
<body> <!-- Elemento de corpo do documento HTML, onde o conteúdo visível da página é colocado. Tudo o que for exibido para o usuário deve estar dentro deste elemento. -->

<nav class="navbar"> <!-- Elemento de navegação da página, contendo links para diferentes seções do sistema. -->

  <!--href é um atributo do elemento a (âncora) que especifica o destino do link. Neste caso, "../index.php" indica que o link levará o usuário de volta à página inicial do sistema, que está localizada um nível acima do diretório atual. class="nav-brand" é uma classe CSS aplicada a este link para estilização específica, geralmente usada para destacar a marca ou nome do sistema na barra de navegação. -->
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

<div class="container">
  <div class="card" style="text-align:center; padding:2.5rem">

    <!-- Ícone e título de confirmação -->
    <div style="font-size:3rem; margin-bottom:0.75rem">✅</div>
    <h2 style="font-size:1.5rem; margin-bottom:0.5rem">Venda Realizada!</h2>
    <p style="color:var(--text-muted); margin-bottom:2rem">Venda #<?= $idVenda ?> registrada com sucesso</p>

    <!-- Resumo da venda em formato de grade -->
    <div style="background:#f8fafc; border-radius:0.625rem; padding:1.25rem; text-align:left; margin-bottom:2rem">
      <div class="form-grade-2" style="gap:0.5rem 1.5rem">
        <div>
          <p style="font-size:0.78rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em">Produto</p>
          <p style="font-weight:600"><?= htmlspecialchars($produto['nome']) ?></p>
        </div>
        <div>
          <p style="font-size:0.78rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em">Quantidade</p>
          <p style="font-weight:600"><?= $qtdCompra ?> un.</p>
        </div>
        <div>
          <p style="font-size:0.78rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em">Preço unitário</p>
          <p style="font-weight:600">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
        </div>
        <div>
          <p style="font-size:0.78rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em">Total</p>
          <!-- Total em azul e maior para destaque -->
          <p style="font-weight:700; font-size:1.1rem; color:var(--primary)">
            R$ <?= number_format($total, 2, ',', '.') ?>
          </p>
        </div>
      </div>
    </div>

    <!-- Botões de ação pós-venda -->
    <div style="display:flex; gap:0.75rem; justify-content:center">
      <a href="vender.php"   class="btn btn-primario">🛍️ Nova Venda</a>
      <a href="historico.php" class="btn btn-contorno">📜 Ver Histórico</a>
      <a href="../index.php" class="btn btn-contorno">← Início</a>
    </div>

  </div>
</div>
</body>
</html>
