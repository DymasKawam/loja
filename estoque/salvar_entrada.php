<?php
include("../conexao.php"); // Conecta ao banco de dados

// Recebe os dados do formulário e converte para os tipos certos
$idFornecedor = (int)   $_POST['id_fornecedor']; // ID do fornecedor (inteiro)
$idProduto    = (int)   $_POST['id_produto'];    // ID do produto (inteiro)
$preco        = (float) $_POST['preco_compra'];  // Preço como decimal (ex: 12.50)
$qtd          = (int)   $_POST['quantidade'];    // Quantidade recebida (inteiro)

try {
    $pdo->beginTransaction(); // Abre transação: os dois inserts salvam juntos ou nenhum salva

    // 1. Registra a relação entre o fornecedor e o produto, com o preço de compra dessa entrada
    $stmt = $pdo->prepare("INSERT INTO fornecedor_produto (id_fornecedor, id_produto, preco_compra)
                           VALUES (:f, :p, :preco)");
    $stmt->execute([':f' => $idFornecedor, ':p' => $idProduto, ':preco' => $preco]);

    // 2. Atualiza o estoque do produto
    // Se o produto ainda não tem linha no estoque, insere com a quantidade recebida
    // Se já existe, soma a nova quantidade ao saldo atual (ON DUPLICATE KEY UPDATE)
    // :q1 e :q2 têm o mesmo valor, mas precisam de nomes diferentes — o PDO não aceita o mesmo parâmetro duas vezes
    $stmt2 = $pdo->prepare("INSERT INTO estoque (id_produto, quantidade)
                            VALUES (:p2, :q1)
                            ON DUPLICATE KEY UPDATE quantidade = quantidade + :q2");
    $stmt2->execute([':p2' => $idProduto, ':q1' => $qtd, ':q2' => $qtd]);

    $pdo->commit(); // Confirma as duas operações no banco ao mesmo tempo
    header("Location: entrada.php?msg=entrada_ok");

} catch (Exception $e) {
    $pdo->rollBack(); // Se qualquer coisa deu errado, desfaz tudo
    header("Location: entrada.php?msg=erro&detalhe=" . urlencode($e->getMessage()));
}
exit;
?>
