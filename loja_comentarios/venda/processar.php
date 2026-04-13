<?php
// require_once inclui auth.php uma única vez para disponibilizar exigir_login()
require_once '../auth.php';
// exigir_login() garante que apenas usuários autenticados possam processar vendas
exigir_login();

// include carrega conexao.php e cria $pdo para todas as operações no banco
include("../conexao.php");

// isset() verifica se todos os campos obrigatórios chegaram no POST
// Se qualquer campo estiver faltando, o script para imediatamente com die()
if (!isset($_POST['produto'], $_POST['quantidade'], $_POST['cliente'], $_POST['vendedor'])) {
    die("Dados incompletos! Volte e preencha todos os campos.");
}

// (int) converte para inteiro — garante que os IDs sejam numéricos e evita SQL Injection
$idProduto  = (int) $_POST['produto'];    // ID do produto a ser vendido
$qtdCompra  = (int) $_POST['quantidade']; // Quantidade solicitada pelo cliente
$idCliente  = (int) $_POST['cliente'];    // ID do cliente que está comprando
$idVendedor = (int) $_POST['vendedor'];   // ID do vendedor que está registrando a venda

// Validação de quantidade antes de consultar o banco — evita travar tabelas desnecessariamente
if ($qtdCompra <= 0) {
    die("Quantidade inválida! O valor precisa ser maior que zero.");
}

// Consulta 1: verifica o estoque atual ANTES de abrir a transação
// Isso evita bloquear o banco se o estoque já for insuficiente desde o início
$stmt = $pdo->prepare("SELECT quantidade FROM estoque WHERE id_produto = :id");
// execute() passa o :id de forma segura, evitando SQL Injection
$stmt->execute([':id' => $idProduto]);
// fetch() retorna apenas a primeira linha como array associativo
$estoque = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o produto tem registro de estoque (pode acontecer se o produto foi inserido manualmente no banco)
if (!$estoque) {
    die("Produto não encontrado no estoque.");
}

// Compara o que o cliente quer comprar com o que está disponível
if ($estoque['quantidade'] < $qtdCompra) {
    // Exibe quantas unidades estão disponíveis para ajudar o usuário
    die("Estoque insuficiente! Disponível: {$estoque['quantidade']} unidade(s).");
}

try {
    // beginTransaction() abre uma transação — os quatro passos abaixo acontecem juntos
    // Se qualquer passo falhar, rollBack() desfaz tudo e o banco permanece consistente
    $pdo->beginTransaction();

    // Passo 2: registra a venda com o cliente e o vendedor responsável
    $stmt2 = $pdo->prepare("INSERT INTO venda (id_cliente, id_vendedor) VALUES (:c, :v)");
    $stmt2->execute([':c' => $idCliente, ':v' => $idVendedor]);

    // lastInsertId() captura o ID da venda criada para vincular os itens
    $idVenda = $pdo->lastInsertId();

    // Passo 3: registra o item vendido nessa venda (produto + quantidade)
    $stmt3 = $pdo->prepare("INSERT INTO item_venda (id_venda, id_produto, quantidade)
                            VALUES (:venda, :prod, :qtd)");
    $stmt3->execute([':venda' => $idVenda, ':prod' => $idProduto, ':qtd' => $qtdCompra]);

    // Passo 4: desconta a quantidade vendida do estoque
    // quantidade - :qtd reduz o saldo sem precisar buscar o valor atual de novo
    $stmt4 = $pdo->prepare("UPDATE estoque SET quantidade = quantidade - :qtd
                            WHERE id_produto = :id");
    $stmt4->execute([':qtd' => $qtdCompra, ':id' => $idProduto]);

    // Passo 5: busca nome e preço do produto para exibir no resumo da venda
    $stmt5 = $pdo->prepare("SELECT nome, preco FROM produto WHERE id = :id");
    $stmt5->execute([':id' => $idProduto]);
    $produto = $stmt5->fetch(PDO::FETCH_ASSOC);

    // commit() confirma os quatro passos permanentemente no banco de dados
    $pdo->commit();

    // Calcula o total da venda para exibir na tela de confirmação
    $total = $produto['preco'] * $qtdCompra;

} catch (Exception $e) {
    // rollBack() desfaz tudo — estoque volta ao estado original, venda e item são removidos
    $pdo->rollBack();
    // die() exibe o erro e encerra o script — em produção, redirecione com header() em vez disso
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
  <!-- Card de confirmação centralizado -->
  <div class="card" style="text-align:center; padding:2.5rem">

    <!-- Ícone e título de sucesso -->
    <div style="font-size:3rem; margin-bottom:0.75rem">✅</div>
    <h2 style="font-size:1.5rem; margin-bottom:0.5rem">Venda Realizada!</h2>
    <!-- $idVenda contém o ID gerado pela transação acima -->
    <p style="color:var(--text-muted); margin-bottom:2rem">Venda #<?= $idVenda ?> registrada com sucesso</p>

    <!-- Resumo da venda em formato de grade de 2 colunas -->
    <div style="background:#f8fafc; border-radius:0.625rem; padding:1.25rem; text-align:left; margin-bottom:2rem">
      <div class="form-grade-2" style="gap:0.5rem 1.5rem">

        <div>
          <!-- Rótulo pequeno em maiúsculas — padrão de interface para campos de resumo -->
          <p style="font-size:0.78rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em">Produto</p>
          <!-- htmlspecialchars() protege contra XSS ao exibir dados do banco -->
          <p style="font-weight:600"><?= htmlspecialchars($produto['nome']) ?></p>
        </div>

        <div>
          <p style="font-size:0.78rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em">Quantidade</p>
          <p style="font-weight:600"><?= $qtdCompra ?> un.</p>
        </div>

        <div>
          <p style="font-size:0.78rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em">Preço unitário</p>
          <!-- number_format() formata com 2 casas decimais, vírgula decimal e ponto de milhar -->
          <p style="font-weight:600">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
        </div>

        <div>
          <p style="font-size:0.78rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em">Total</p>
          <!-- Total destacado em azul e fonte maior para chamar atenção -->
          <p style="font-weight:700; font-size:1.1rem; color:var(--primary)">
            R$ <?= number_format($total, 2, ',', '.') ?>
          </p>
        </div>

      </div>
    </div>

    <!-- Botões pós-venda: nova venda, histórico ou voltar ao início -->
    <div style="display:flex; gap:0.75rem; justify-content:center">
      <a href="vender.php"    class="btn btn-primario">🛍️ Nova Venda</a>
      <a href="historico.php" class="btn btn-contorno">📜 Ver Histórico</a>
      <a href="../index.php"  class="btn btn-contorno">← Início</a>
    </div>

  </div>
</div>
</body>
</html>
