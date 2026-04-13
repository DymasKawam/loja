<?php
// require_once inclui auth.php apenas uma vez, disponibilizando as funções de autenticação
require_once '../auth.php';
// exigir_papel('admin') bloqueia o acesso a usuários não-admin e não-logados
exigir_papel('admin');

// include carrega conexao.php, criando o objeto $pdo para acesso ao banco de dados
include("../conexao.php");

// $_POST contém os dados enviados pelo formulário via método POST
// (int) converte para inteiro — evita que texto ou SQL malicioso chegue ao banco
$idFornecedor = (int)   $_POST['id_fornecedor']; // ID do fornecedor selecionado no select
$idProduto    = (int)   $_POST['id_produto'];    // ID do produto selecionado no select
// (float) converte para número decimal — garante que o preço tenha casas decimais
$preco        = (float) $_POST['preco_compra'];  // Preço de compra do produto neste fornecedor

// Cria a query parametrizada para inserir o vínculo entre fornecedor e produto
// Os :parametros evitam SQL Injection (nenhum valor do POST vai direto na query)
$sql = "INSERT INTO fornecedor_produto (id_fornecedor, id_produto, preco_compra)
        VALUES (:fornecedor, :produto, :preco)";

try {
    // prepare() compila a query com os parâmetros nomeados
    $stmt = $pdo->prepare($sql);
    // bindParam() associa cada :parametro ao valor PHP correspondente
    $stmt->bindParam(':fornecedor', $idFornecedor);
    $stmt->bindParam(':produto',    $idProduto);
    $stmt->bindParam(':preco',      $preco);

    // execute() executa a query com os valores vinculados acima
    $stmt->execute();

    // Redireciona para o formulário com mensagem de sucesso na URL
    // O padrão PRG (Post/Redirect/Get) evita que o formulário seja reenviado ao atualizar a página
    header("Location: ligar_produto.php?msg=sucesso");

} catch (Exception $e) {
    // Em caso de erro (ex: chave duplicada, produto inexistente), redireciona com mensagem de erro
    // urlencode() codifica a mensagem de erro para ser transmitida com segurança pela URL
    header("Location: ligar_produto.php?msg=erro&detalhe=" . urlencode($e->getMessage()));
}
// exit interrompe a execução do script imediatamente após o header de redirecionamento
exit;
?>
