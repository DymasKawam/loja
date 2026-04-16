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

    // 3. Registra o item vendido nessa venda
    $stmt3 = $pdo->prepare("INSERT INTO item_venda (id_venda, id_produto, quantidade)
                            VALUES (:venda, :prod, :qtd)");
    $stmt3->execute([':venda' => $idVenda, ':prod' => $idProduto, ':qtd' => $qtdCompra]);

    // 4. Desconta a quantidade vendida do estoque
    $stmt4 = $pdo->prepare("UPDATE estoque SET quantidade = quantidade - :qtd
                            WHERE id_produto = :id");
    $stmt4->execute([':qtd' => $qtdCompra, ':id' => $idProduto]);

    // 5. Busca o nome e preço do produto para montar o resumo na tela
    $stmt5 = $pdo->prepare("SELECT nome, preco FROM produto WHERE id = :id");
    $stmt5->execute([':id' => $idProduto]);
    $produto = $stmt5->fetch(PDO::FETCH_ASSOC);

    $pdo->commit(); // Confirma as três operações no banco de uma vez

    // Calcula o valor total da venda para exibir no resumo
    $total = $produto['preco'] * $qtdCompra;

} catch (Exception $e) {
    $pdo->rollBack(); // Se qualquer passo falhou, desfaz tudo
    die("Erro ao processar venda: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Venda Realizada</title>
  <link rel="stylesheet" href="../estilo.css">
</head>
<body>

<nav class="navbar">
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
