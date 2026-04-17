<?php
include("../conexao.php"); // Importa a conexão com o banco de dados

// Recebe os dados enviados pelo formulário (método POST)
$nome   = $_POST['nome'];
$cpf    = $_POST['cpf'];
$rua    = $_POST['rua'];
$numero = $_POST['numero'];
$cidade = $_POST['cidade'];
$estado = strtoupper(trim($_POST['estado'])); //strtoupper é usado para converter o estado para maiúsculas, garantindo que seja armazenado de forma consistente no banco de dados. trim remove espaços extras antes e depois do texto.
$cep    = $_POST['cep'];

try { // Tenta executar o bloco de código para salvar os dados no banco
    $pdo->beginTransaction(); // Abre uma transação: só vai salvar tudo de vez, ou nada se der erro

    // stmt é a variável que recebe a preparação da query SQL para inserir o endereço do cliente. A query usa parâmetros nomeados (ex: :rua) para evitar SQL Injection, garantindo que os dados sejam tratados de forma segura. 
    $stmt = $pdo->prepare("INSERT INTO endereco (rua, numero, cidade, estado, cep)
                           VALUES (:rua, :numero, :cidade, :estado, :cep)");
    // Executa a query de inserção do endereço, passando os valores dos parâmetros nomeados como um array associativo. Isso garante que os dados sejam inseridos de forma segura e correta no banco de dados, evitando problemas de SQL Injection.
    $stmt->execute([':rua' => $rua, ':numero' => $numero,
                    ':cidade' => $cidade, ':estado' => $estado, ':cep' => $cep]);

    $idEndereco = $pdo->lastInsertId(); // Pega o ID do endereço recém criado para vincular ao cliente. Isso é necessário porque a tabela de cliente tem uma chave estrangeira que aponta para o endereço, então precisamos saber qual é o ID do endereço que acabamos de inserir para associar corretamente o cliente a ele.

    // Prepara a query SQL para inserir o cliente, usando parâmetros nomeados para garantir segurança contra SQL Injection. A query insere o nome, CPF e o ID do endereço (que é a chave estrangeira) na tabela de cliente.
    $stmt2 = $pdo->prepare("INSERT INTO cliente (nome, cpf, endereco_cliente)
                            VALUES (:nome, :cpf, :id)");
    $stmt2->execute([':nome' => $nome, ':cpf' => $cpf, ':id' => $idEndereco]); // Executa a query de inserção do cliente, passando os valores dos parâmetros nomeados como um array associativo. Isso garante que os dados sejam inseridos de forma segura e correta no banco de dados, evitando problemas de SQL Injection.

    $pdo->commit(); // Confirma tudo no banco — endereço e cliente foram salvos juntos

    // Redireciona para o formulário com uma mensagem de sucesso na URL
    header("Location: cadastrar.php?msg=sucesso");

} catch (Exception $e) { // Se der qualquer erro durante o processo de inserção, entra aqui
    $pdo->rollBack(); // Se qualquer coisa falhou, desfaz tudo (nem o endereço fica salvo)
    // Redireciona com a mensagem de erro para exibir na tela
    header("Location: cadastrar.php?msg=erro&detalhe=" . urlencode($e->getMessage()));
}
exit; // Garante que o script para aqui e não executa mais nada abaixo
?>
