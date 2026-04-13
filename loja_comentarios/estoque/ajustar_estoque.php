<?php
// require_once inclui auth.php uma única vez para disponibilizar as funções de autenticação
require_once '../auth.php';
// exigir_papel('admin') impede que vendedores ou usuários não logados ajustem o estoque
exigir_papel('admin');

// include carrega conexao.php, criando o objeto $pdo para acesso ao banco de dados
include("../conexao.php");

// $_POST contém os dados enviados pelo formulário via método POST
// (int) converte para inteiro — garante que não chegue texto nem SQL no lugar de número
$idProduto  = (int) $_POST['id_produto'];  // ID do produto a ter o estoque ajustado
$quantidade = (int) $_POST['quantidade'];  // Quantidade a somar ao estoque

// Validação: não faz sentido adicionar zero ou número negativo ao estoque
if ($quantidade <= 0) {
    // header() redireciona o navegador para a página de entrada com a mensagem de erro na URL
    // urlencode() codifica o texto para ser transmitido com segurança como parâmetro de URL
    header("Location: entrada.php?msg=erro&detalhe=" . urlencode("Quantidade deve ser maior que zero."));
    exit; // exit interrompe o script imediatamente após o redirecionamento
}

// prepare() cria uma query parametrizada para atualizar o estoque do produto
// quantidade = quantidade + :qtd soma a nova quantidade ao saldo atual, sem sobrescrever
// WHERE id_produto = :id garante que apenas o produto correto seja atualizado
$stmt = $pdo->prepare("UPDATE estoque SET quantidade = quantidade + :qtd WHERE id_produto = :id");

// execute() substitui os parâmetros pelos valores e executa a query no banco
if ($stmt->execute([':qtd' => $quantidade, ':id' => $idProduto])) {
    // Redireciona com mensagem de sucesso quando a atualização funcionar
    header("Location: entrada.php?msg=ajuste_ok");
} else {
    // Redireciona com mensagem de erro quando a atualização falhar
    header("Location: entrada.php?msg=erro&detalhe=" . urlencode("Não foi possível atualizar o estoque."));
}
// exit garante que nada mais seja executado após o redirecionamento
exit;
?>
