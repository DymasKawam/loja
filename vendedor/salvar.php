<?php
include("../conexao.php"); // Inclui o arquivo de conexão com o banco de dados para permitir a execução de consultas SQL. O arquivo conexao.php contém a configuração e a criação da instância PDO para se conectar ao banco de dados, permitindo que este script execute consultas para salvar o novo vendedor no banco de dados.

$nome = trim($_POST['nome']); // $nome recebe o valor do campo 'nome' enviado via POST, e trim() é usado para remover espaços em branco extras no início e no final do nome, garantindo que o valor seja limpo antes de ser inserido no banco de dados.
// $stmt é uma variável que armazena a declaração preparada para inserir um novo vendedor na tabela "vendedor". O método prepare() do objeto PDO é usado para criar uma declaração SQL preparada, onde ":nome" é um marcador de parâmetro que será substituído pelo valor real do nome do vendedor quando a declaração for executada. Isso ajuda a prevenir ataques de injeção SQL, garantindo que os dados sejam tratados de forma segura.
$stmt = $pdo->prepare("INSERT INTO vendedor (nome) VALUES (:nome)");

if ($stmt->execute([':nome' => $nome])) {  // $stmt->execute() é usado para executar a declaração preparada, passando um array associativo onde a chave ':nome' corresponde ao marcador de parâmetro na declaração SQL, e o valor é a variável $nome que contém o nome do vendedor a ser inserido. Se a execução for bem-sucedida, a função retorna true, indicando que o vendedor foi cadastrado com sucesso no banco de dados.
    // Redireciona de volta para o formulário de cadastro com uma mensagem de sucesso na URL, usando o parâmetro "msg=sucesso" para indicar que a operação foi concluída com êxito. O header() é usado para enviar um cabeçalho HTTP de redirecion
    header("Location: cadastrar.php?msg=sucesso");
} else { // Se a execução falhar, o código dentro do else será executado. Isso pode ocorrer por vários motivos, como problemas de conexão com o banco de dados, erros na consulta SQL ou restrições de integridade. Neste caso, o script redireciona o usuário de volta para a página de cadastro com um parâmetro "msg=erro" na URL para indicar que houve um problema ao tentar cadastrar o vendedor.
    // Redireciona de volta para o formulário de cadastro com uma mensagem de erro na URL, usando o parâmetro "msg=erro" para indicar que houve um problema ao tentar cadastrar o vendedor. O header() é usado para enviar um cabeçalho HTTP de redirecionamento para a página de cadastro.
    header("Location: cadastrar.php?msg=erro");
}
exit; // Para a execução aqui para o redirecionamento funcionar corretamente
?>
