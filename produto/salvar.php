<?php
include("../conexao.php"); // Inclui o arquivo de conexão com o banco de dados para permitir a execução de consultas SQL e operações de banco de dados necessárias para salvar o produto e o estoque.

// Captura os dados do formulário usando $_POST e realiza as conversões necessárias para garantir que os valores sejam do tipo correto antes de serem inseridos no banco de dados. Isso inclui converter o preço para um número decimal e a quantidade para um número inteiro, garantindo que os dados sejam armazenados corretamente e evitando erros de tipo ao salvar as informações do produto e do estoque.
$nome       = $_POST['nome'];
$descricao  = $_POST['descricao'];
$preco      = (float) $_POST['preco'];      // Converte para float — aceita "19.99" mas não "dezenove reais"
$quantidade = (int)   $_POST['quantidade']; // Converte para inteiro — não aceita "5.5 unidades"

try { // Inicia um bloco try-catch para lidar com possíveis erros durante o processo de salvamento do produto e do estoque. Se ocorrer algum erro, a transação será revertida para garantir a integridade dos dados no banco de dados, e uma mensagem de erro será exibida para o usuário.
    $pdo->beginTransaction(); // Inicia uma transação no banco de dados usando o método beginTransaction() do objeto PDO. Isso permite que todas as operações de banco de dados executadas dentro do bloco try sejam tratadas como uma única unidade de trabalho, garantindo que todas as alterações sejam aplicadas ou revertidas juntas, mantendo a consistência dos dados.

    // 1. Cria o registro do produto na tabela "produto" usando uma consulta SQL preparada para evitar injeção de SQL. A consulta insere o nome, descrição e preço do produto na tabela "produto". Após a execução da consulta, o ID do produto recém criado é capturado usando lastInsertId() para ser usado posteriormente ao criar o registro de estoque correspondente.
    $stmt = $pdo->prepare("INSERT INTO produto (nome, descricao, preco)
                           VALUES (:nome, :descricao, :preco)");
    $stmt->execute([':nome' => $nome, ':descricao' => $descricao, ':preco' => $preco]); // Executa a consulta SQL preparada, passando um array associativo que vincula os parâmetros nomeados (:nome, :descricao, :preco) aos valores reais capturados do formulário. Isso insere um novo registro na tabela "produto" com as informações fornecidas pelo usuário.

    $idProduto = $pdo->lastInsertId(); // Captura o ID do produto recém criado usando o método lastInsertId() do objeto PDO. Esse ID é necessário para criar o registro de estoque correspondente ao produto, garantindo que o estoque esteja vinculado corretamente ao produto recém adicionado.

    // 2. Cria o registro de estoque na tabela "estoque" usando uma consulta SQL preparada para evitar injeção de SQL. A consulta insere o ID do produto e a quantidade disponível em estoque na tabela "estoque". Isso garante que o estoque do produto seja registrado corretamente no banco de dados, permitindo que as informações de disponibilidade sejam gerenciadas e exibidas posteriormente.
    $stmt2 = $pdo->prepare("INSERT INTO estoque (id_produto, quantidade) VALUES (:id, :qtd)");
    $stmt2->execute([':id' => $idProduto, ':qtd' => $quantidade]); // Executa a consulta SQL preparada para inserir um novo registro na tabela "estoque", passando um array associativo que vincula os parâmetros nomeados (:id, :qtd) aos valores reais do ID do produto e da quantidade em estoque. Isso cria um registro de estoque para o produto recém criado, permitindo que a quantidade disponível seja gerenciada e exibida corretamente no sistema.

    $pdo->commit(); // Confirma as operações de banco de dados executadas dentro do bloco try usando o método commit() do objeto PDO. Isso garante que todas as alterações feitas no banco de dados, incluindo a criação do produto e do estoque, sejam permanentemente aplicadas. Se todas as operações forem bem-sucedidas, commit() é chamado

    // Redireciona para a página de cadastro com uma mensagem de sucesso usando a função header() para enviar um cabeçalho HTTP que redireciona o navegador para a página "cadastrar.php" e inclui um parâmetro de consulta "msg=sucesso" para indicar que o produto foi salvo com sucesso. Isso permite que a página de cadastro exiba uma mensagem de confirmação ao usuário, informando que o processo de salvamento do produto e do estoque foi concluído com êxito.
    header("Location: cadastrar.php?msg=sucesso");

} catch (Exception $e) {
    $pdo->rollBack(); // Reverte a transação iniciada anteriormente usando o método rollBack() do objeto PDO. Isso desfaz todas as alterações feitas no banco de dados desde o início da transação, garantindo que o banco de dados permaneça em um estado consistente mesmo se ocorrer um erro durante o processo de salvamento do produto e do estoque.
    header("Location: cadastrar.php?msg=erro&detalhe=" . urlencode($e->getMessage())); // Redireciona para a página de cadastro com uma mensagem de erro usando a função header() para enviar um cabeçalho HTTP que redireciona o navegador para a página "cadastrar.php" e inclui parâmetros de consulta "msg=erro" e "detalhe" contendo a mensagem de erro capturada da exceção. Isso permite que a página de cadastro exiba uma mensagem de erro ao usuário, informando que ocorreu um problema durante o processo de salvamento do produto e do estoque, e fornecendo detalhes sobre o erro para ajudar na resolução do problema.
}
exit;
?>
