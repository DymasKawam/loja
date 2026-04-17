<?php
include("../conexao.php"); // Conecta ao banco de dados

$idProduto  = (int) $_POST['id_produto'];  //$idproduto é uma variável que armazena o ID do produto para o qual o estoque será ajustado. O valor é obtido a partir do formulário enviado via POST, onde o campo 'id_produto' deve conter um número inteiro representando o ID do produto no banco de dados. A conversão para inteiro é feita para garantir que o valor seja do tipo correto, evitando possíveis erros de tipo ao executar a consulta SQL que atualizará o estoque do produto. 
$quantidade = (int) $_POST['quantidade'];  // $quantidade é uma variável que armazena a quantidade que será adicionada ao estoque do produto. Assim como $idProduto, o valor é obtido a partir do formulário enviado via POST, onde o campo 'quantidade' deve conter um número inteiro representando a quantidade a ser ajustada no estoque. A conversão para inteiro é feita para garantir que o valor seja do tipo correto, evitando possíveis erros de tipo ao executar a consulta SQL que atualizará o estoque do produto. A quantidade pode ser positiva (para adicionar ao estoque) ou negativa (para subtrair do estoque), dependendo do ajuste necessário.

// Validações básicas para garantir que os dados sejam válidos antes de tentar atualizar o estoque. Verifica se o ID do produto é válido (maior que zero) e se a quantidade é maior que zero. Se alguma dessas condições não for atendida, o script redireciona de volta para a página de entrada com uma mensagem de erro detalhada, usando urlencode para garantir que a mensagem seja corretamente formatada na URL. Essas validações ajudam a evitar erros de banco de dados e garantem que apenas ajustes válidos sejam processados.
if ($quantidade <= 0) {
    header("Location: entrada.php?msg=erro&detalhe=" . urlencode("Quantidade deve ser maior que zero."));
    exit;
}

//stmt é uma variável que armazena a consulta SQL preparada para atualizar o estoque do produto. prepare é um método do objeto PDO que prepara a consulta SQL para execução, permitindo o uso de parâmetros nomeados (como :qtd e :id) para evitar injeção de SQL e facilitar a passagem de valores. A consulta SQL em si é um comando UPDATE que incrementa a quantidade existente no estoque do produto identificado por id_produto. O uso de parâmetros nomeados torna o código mais seguro e legível, além de permitir a reutilização da consulta com diferentes valores para quantidade e ID do produto.
$stmt = $pdo->prepare("UPDATE estoque SET quantidade = quantidade + :qtd WHERE id_produto = :id");

if ($stmt->execute([':qtd' => $quantidade, ':id' => $idProduto])) { 
    header("Location: entrada.php?msg=ajuste_ok"); // Se a execução da consulta for bem-sucedida, o script redireciona para a página de entrada com uma mensagem de sucesso indicando que o ajuste do estoque foi realizado com sucesso. A mensagem "ajuste_ok" pode ser usada na página de entrada para exibir uma notificação ao usuário confirmando que o estoque foi ajustado corretamente.
} else {
    header("Location: entrada.php?msg=erro&detalhe=" . urlencode("Não foi possível atualizar o estoque.")); // Se a execução da consulta falhar, o script redireciona para a página de entrada com uma mensagem de erro detalhada, usando urlencode para garantir que a mensagem seja corretamente formatada na URL. A mensagem "Não foi possível atualizar o estoque." indica que houve um problema ao tentar atualizar o estoque do produto, o que pode ser devido a vários motivos, como um ID de produto inválido ou um erro de conexão com o banco de dados.
}
exit;
?>
