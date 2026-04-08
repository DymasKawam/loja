<?php
include("../conexao.php"); // Importa a conexão com o banco de dados

// Recebe os dados enviados pelo formulário (método POST)
$nome   = $_POST['nome'];
$cpf    = $_POST['cpf'];
$rua    = $_POST['rua'];
$numero = $_POST['numero'];
$cidade = $_POST['cidade'];
$estado = strtoupper(trim($_POST['estado'])); // Converte o estado para maiúsculas (ex: "sp" → "SP")
$cep    = $_POST['cep'];

try {
    $pdo->beginTransaction(); // Abre uma transação: só vai salvar tudo de vez, ou nada se der erro

    // 1. Primeiro salva o endereço, porque o cliente precisa de um ID de endereço para ser criado
    $stmt = $pdo->prepare("INSERT INTO endereco (rua, numero, cidade, estado, cep)
                           VALUES (:rua, :numero, :cidade, :estado, :cep)");
    // Os :parametros evitam que alguém quebre o banco digitando texto malicioso (SQL Injection)
    $stmt->execute([':rua' => $rua, ':numero' => $numero,
                    ':cidade' => $cidade, ':estado' => $estado, ':cep' => $cep]);

    $idEndereco = $pdo->lastInsertId(); // Pega o ID do endereço que acabou de ser inserido

    // 2. Agora salva o cliente, vinculado ao endereço recém criado
    $stmt2 = $pdo->prepare("INSERT INTO cliente (nome, cpf, endereco_cliente)
                            VALUES (:nome, :cpf, :id)");
    $stmt2->execute([':nome' => $nome, ':cpf' => $cpf, ':id' => $idEndereco]);

    $pdo->commit(); // Confirma tudo no banco — endereço e cliente foram salvos juntos

    // Redireciona para o formulário com uma mensagem de sucesso na URL
    header("Location: cadastrar.php?msg=sucesso");

} catch (Exception $e) {
    $pdo->rollBack(); // Se qualquer coisa falhou, desfaz tudo (nem o endereço fica salvo)
    // Redireciona com a mensagem de erro para exibir na tela
    header("Location: cadastrar.php?msg=erro&detalhe=" . urlencode($e->getMessage()));
}
exit; // Garante que o script para aqui e não executa mais nada abaixo
?>
