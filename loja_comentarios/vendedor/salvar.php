<?php
// require_once inclui auth.php para disponibilizar as funções de autenticação
require_once '../auth.php';
// exigir_papel('admin') garante que apenas administradores possam cadastrar vendedores
exigir_papel('admin');

// include carrega conexao.php e cria o objeto $pdo para acesso ao banco de dados
include("../conexao.php");

// trim() remove espaços em branco no início e fim do nome digitado pelo usuário
// $_POST['nome'] recupera o valor enviado pelo formulário via método POST
$nome = trim($_POST['nome']);

// prepare() cria uma query parametrizada com :nome — evita SQL Injection
$stmt = $pdo->prepare("INSERT INTO vendedor (nome) VALUES (:nome)");

// execute() substitui :nome pelo valor real e executa a inserção no banco
if ($stmt->execute([':nome' => $nome])) {
    // Redireciona para o formulário com mensagem de sucesso na URL (padrão PRG)
    header("Location: cadastrar.php?msg=sucesso");
} else {
    // Redireciona com mensagem de erro quando a inserção falhar
    header("Location: cadastrar.php?msg=erro");
}
// exit interrompe o script imediatamente para o redirecionamento funcionar corretamente
exit;
?>
