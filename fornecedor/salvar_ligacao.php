<?php 
include("../conexao.php"); // Importa a conexão com o banco de dados para permitir a execução de consultas SQL e operações de banco de dados necessárias para salvar a ligação entre fornecedor e produto, incluindo o preço de compra.


// Recebe os dados enviados pelo formulário (método POST) para criar a ligação entre fornecedor e produto, incluindo o preço de compra. Esses dados são usados para inserir um novo registro na tabela de fornecedor_produto, que representa a relação entre fornecedores e os produtos que eles fornecem, juntamente com o preço de compra acordado.
$idFornecedor = $_POST['id_fornecedor'];
$idProduto = $_POST['id_produto'];
$preco = $_POST['preco_compra'];

// Prepara a query SQL para inserir a ligação entre fornecedor e produto, usando parâmetros nomeados para garantir segurança contra SQL Injection. A query insere o ID do fornecedor, o ID do produto e o preço de compra na tabela de fornecedor_produto.
$sql = "INSERT INTO fornecedor_produto (id_fornecedor, id_produto, preco_compra)
        VALUES (:fornecedor, :produto, :preco)";
// Usa prepared statements para evitar SQL Injection, garantindo que os dados sejam tratados de forma segura e correta no banco de dados, bindParam é usado para associar os valores dos parâmetros nomeados na query SQL com as variáveis PHP correspondentes, garantindo que os dados sejam inseridos de forma segura e correta no banco de dados.
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':fornecedor', $idFornecedor);
$stmt->bindParam(':produto', $idProduto);
$stmt->bindParam(':preco', $preco);

$stmt->execute(); // Executa a query de inserção da ligação entre fornecedor e produto, salvando os dados no banco de dados. Se a execução for bem-sucedida, a ligação será criada e os dados serão armazenados corretamente na tabela de fornecedor_produto.

echo "Ligação criada com sucesso!"; // Exibe uma mensagem de sucesso indicando que a ligação entre fornecedor e produto foi criada com sucesso. Essa mensagem pode ser usada para confirmar ao usuário que a operação foi realizada corretamente.
?>