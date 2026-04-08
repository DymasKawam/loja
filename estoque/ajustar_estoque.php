<?php
include("../conexao.php"); // Conecta ao banco de dados

$idProduto  = (int) $_POST['id_produto'];  // ID do produto a ter o estoque ajustado
$quantidade = (int) $_POST['quantidade'];  // Quantidade a somar no estoque

// Validação básica: não faz sentido adicionar zero ou número negativo
if ($quantidade <= 0) {
    header("Location: entrada.php?msg=erro&detalhe=" . urlencode("Quantidade deve ser maior que zero."));
    exit;
}

// Soma a quantidade informada ao estoque atual do produto
// O WHERE garante que só atualiza o produto correto
$stmt = $pdo->prepare("UPDATE estoque SET quantidade = quantidade + :qtd WHERE id_produto = :id");

if ($stmt->execute([':qtd' => $quantidade, ':id' => $idProduto])) {
    header("Location: entrada.php?msg=ajuste_ok"); // Ajuste feito com sucesso
} else {
    header("Location: entrada.php?msg=erro&detalhe=" . urlencode("Não foi possível atualizar o estoque."));
}
exit;
?>
