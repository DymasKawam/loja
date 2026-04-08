<?php
include("../conexao.php"); // Importa a conexão com o banco

// Recebe os dados do formulário
$nome = $_POST['nome'];
$cpf  = $_POST['cpf'];
$rua    = $_POST['rua'];
$numero = $_POST['numero'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$cep    = $_POST['cep'];

try {
    $pdo->beginTransaction(); // Inicia a transação para garantir que tudo salva junto

    // 1. Salva o endereço com parâmetros nomeados (sem SQL Injection)
    $stmt = $pdo->prepare("INSERT INTO endereco (rua, numero, cidade, estado, cep)
                           VALUES (:rua, :numero, :cidade, :estado, :cep)");
    $stmt->execute([':rua' => $rua, ':numero' => $numero,
                    ':cidade' => $cidade, ':estado' => $estado, ':cep' => $cep]);

    $idEndereco = $pdo->lastInsertId(); // Pega o ID do endereço recém criado

    // 2. Salva o cliente vinculado ao endereço
    $stmt2 = $pdo->prepare("INSERT INTO cliente (nome, cpf, endereco_cliente)
                            VALUES (:nome, :cpf, :id)");
    $stmt2->execute([':nome' => $nome, ':cpf' => $cpf, ':id' => $idEndereco]);

    $pdo->commit(); // Confirma tudo no banco
    echo "Cliente cadastrado!";

} catch (Exception $e) {
    $pdo->rollBack(); // Desfaz tudo se der qualquer erro
    echo "Erro ao cadastrar cliente: " . $e->getMessage();
}
?>
