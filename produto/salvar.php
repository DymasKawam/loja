<?php
include("../conexao.php"); // Importa a conexão com o banco

// Recebe os dados do formulário
$nome       = $_POST['nome'];
$descricao  = $_POST['descricao'];
$preco      = (float) $_POST['preco'];      // Converte para decimal — garante que "19,99" não quebre
$quantidade = (int)   $_POST['quantidade']; // Converte para inteiro — não aceita "5.5 unidades"

try {
    $pdo->beginTransaction(); // Abre a transação: produto + estoque salvos juntos ou nenhum dos dois

    // 1. Insere o produto na tabela "produto" usando parâmetros seguros
    $stmt = $pdo->prepare("INSERT INTO produto (nome, descricao, preco)
                           VALUES (:nome, :descricao, :preco)");
    $stmt->execute([':nome' => $nome, ':descricao' => $descricao, ':preco' => $preco]);

    $idProduto = $pdo->lastInsertId(); // Captura o ID do produto recém criado para usar no estoque

    // 2. Cria o registro de estoque para esse produto com a quantidade informada no formulário
    $stmt2 = $pdo->prepare("INSERT INTO estoque (id_produto, quantidade) VALUES (:id, :qtd)");
    $stmt2->execute([':id' => $idProduto, ':qtd' => $quantidade]);

    $pdo->commit(); // Confirma produto e estoque no banco ao mesmo tempo

    // Redireciona de volta ao formulário com mensagem de sucesso
    header("Location: cadastrar.php?msg=sucesso");

} catch (Exception $e) {
    $pdo->rollBack(); // Se algo falhou, desfaz tudo — nem o produto nem o estoque ficam salvos
    header("Location: cadastrar.php?msg=erro&detalhe=" . urlencode($e->getMessage()));
}
exit;
?>
