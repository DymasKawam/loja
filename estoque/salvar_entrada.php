<?php
include("../conexao.php"); // Conecta ao banco de dados

// Recebe os dados do formulário e converte para os tipos certos
$idFornecedor = (int)   $_POST['id_fornecedor']; // ID do fornecedor (inteiro)
$idProduto    = (int)   $_POST['id_produto'];    // ID do produto (inteiro)
$preco        = (float) $_POST['preco_compra'];  // Preço como decimal (ex: 12.50)
$qtd          = (int)   $_POST['quantidade'];    // Quantidade recebida (inteiro)

try {
    $pdo->beginTransaction(); // Iniciar transação para garantir que o registro da entrada e a atualização do estoque sejam feitos juntos ou nenhum seja feito em caso de erro. Isso é importante para manter a integridade dos dados, já que a entrada depende do fornecedor e do produto, e o estoque precisa ser atualizado corretamente com base nessa entrada. Se qualquer parte do processo de inserção ou atualização falhar, a transação pode ser revertida para evitar dados inconsistentes no banco.

    // 1. Registra a entrada do produto pelo fornecedor, incluindo o preço de compra. O comando INSERT INTO adiciona uma nova linha na tabela fornecedor_produto, que é uma tabela de relacionamento que vincula fornecedores e produtos, armazenando também o preço de compra para cada combinação de fornecedor e produto. Isso permite rastrear quais produtos foram fornecidos por quais fornecedores e a que preço, o que é essencial para o controle de estoque e para futuras análises de compras.
    $stmt = $pdo->prepare("INSERT INTO fornecedor_produto (id_fornecedor, id_produto, preco_compra)
                           VALUES (:f, :p, :preco)");
    $stmt->execute([':f' => $idFornecedor, ':p' => $idProduto, ':preco' => $preco]);

    // 2. Atualiza o estoque do produto somando a quantidade recebida. O comando UPDATE com a cláusula ON DUPLICATE KEY UPDATE é usado para garantir que, se já existir um registro de estoque para o produto (identificado pelo id_produto como chave primária), a quantidade seja atualizada somando a nova quantidade recebida. Se não existir um registro para o produto, o comando INSERT cria um novo registro com a quantidade inicial. Isso simplifica o processo de atualização do estoque, evitando a necessidade de verificar previamente se o registro existe ou não. A quantidade é somada ao estoque atual, garantindo que o controle de estoque reflita corretamente as entradas de produtos feitas pelos fornecedores. 
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
