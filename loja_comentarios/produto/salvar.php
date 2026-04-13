<?php
// require_once inclui auth.php apenas uma vez para disponibilizar as funções de autenticação
require_once '../auth.php';
// exigir_papel('admin') impede que vendedores ou usuários não logados cadastrem produtos
exigir_papel('admin');

// include carrega conexao.php, criando o objeto $pdo para acesso ao banco de dados
include("../conexao.php");

// $_POST['nome'] recupera o nome enviado pelo formulário
$nome       = $_POST['nome'];
// $_POST['descricao'] recupera a descrição (pode ser vazia — campo opcional)
$descricao  = $_POST['descricao'];
// (float) converte a string do POST para número decimal — garante que o preço seja numérico
$preco      = (float) $_POST['preco'];
// (int) converte para inteiro — não existe meia unidade em estoque
$quantidade = (int)   $_POST['quantidade'];

try {
    // beginTransaction() abre uma transação: produto e estoque são inseridos juntos
    // Se qualquer um falhar, rollBack() desfaz tudo — o banco não fica com produto sem estoque
    $pdo->beginTransaction();

    // prepare() compila a query com :parametros nomeados — evita SQL Injection
    $stmt = $pdo->prepare("INSERT INTO produto (nome, descricao, preco)
                           VALUES (:nome, :descricao, :preco)");
    // execute() substitui os parâmetros pelos valores reais e executa no banco
    $stmt->execute([':nome' => $nome, ':descricao' => $descricao, ':preco' => $preco]);

    // lastInsertId() retorna o ID gerado pelo banco para o produto recém inserido
    // Esse ID é necessário para criar o registro de estoque vinculado ao produto
    $idProduto = $pdo->lastInsertId();

    // Insere o registro de estoque para o produto, com a quantidade inicial informada
    $stmt2 = $pdo->prepare("INSERT INTO estoque (id_produto, quantidade) VALUES (:id, :qtd)");
    $stmt2->execute([':id' => $idProduto, ':qtd' => $quantidade]);

    // commit() confirma as duas inserções permanentemente no banco de dados
    $pdo->commit();

    // header() redireciona para o formulário; ?msg=sucesso aciona o alerta verde em cadastrar.php
    header("Location: cadastrar.php?msg=sucesso");

} catch (Exception $e) {
    // rollBack() desfaz tudo — se o estoque falhou, o produto também não é salvo
    $pdo->rollBack();
    // urlencode() codifica a mensagem de erro para ser transmitida com segurança pela URL
    header("Location: cadastrar.php?msg=erro&detalhe=" . urlencode($e->getMessage()));
}
// exit interrompe o script imediatamente após o redirecionamento
exit;
?>
