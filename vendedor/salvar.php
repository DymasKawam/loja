<?php
include("../conexao.php"); // Conecta ao banco de dados

$nome = trim($_POST['nome']); // Recebe o nome e remove espaços extras no início e fim

// Insere o vendedor usando parâmetro nomeado — evita SQL Injection
$stmt = $pdo->prepare("INSERT INTO vendedor (nome) VALUES (:nome)");

if ($stmt->execute([':nome' => $nome])) {
    // Deu certo — redireciona com mensagem de sucesso
    header("Location: cadastrar.php?msg=sucesso");
} else {
    // Algo deu errado — redireciona com aviso de erro
    header("Location: cadastrar.php?msg=erro");
}
exit; // Para a execução aqui para o redirecionamento funcionar corretamente
?>
