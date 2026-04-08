<?php
include("../conexao.php");

$idFornecedor = $_POST['id_fornecedor'];
$idProduto = $_POST['id_produto'];
$preco = $_POST['preco_compra'];

$sql = "INSERT INTO fornecedor_produto (id_fornecedor, id_produto, preco_compra)
        VALUES (:fornecedor, :produto, :preco)";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':fornecedor', $idFornecedor);
$stmt->bindParam(':produto', $idProduto);
$stmt->bindParam(':preco', $preco);

$stmt->execute();

echo "Ligação criada com sucesso!";
?>