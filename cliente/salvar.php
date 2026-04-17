<?php
include("../conexao.php"); // Importa a conexão com o banco

// Recebe os dados do formulário e converte para os tipos certos para evitar erros de tipo no banco de dados. O nome e o CPF são strings, enquanto os campos do endereço são tratados como strings também, mas podem ser validados posteriormente para garantir que estão no formato correto (ex: CEP numérico, número do endereço como inteiro, etc.). Essas variáveis serão usadas para inserir os dados do cliente e do endereço no banco de dados.
$nome = $_POST['nome'];
$cpf  = $_POST['cpf'];
$rua    = $_POST['rua'];
$numero = $_POST['numero'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$cep    = $_POST['cep'];

try { 
    $pdo->beginTransaction(); // Iniciar transação para garantir que o cliente e o endereço sejam salvos juntos ou nenhum seja salvo em caso de erro. Isso é importante para manter a integridade dos dados, já que o cliente depende do endereço (o campo endereco_cliente na tabela cliente é uma chave estrangeira que referencia o ID do endereço). Se qualquer parte do processo de inserção falhar, a transação pode ser revertida para evitar dados inconsistentes no banco.

    // 1. Salva o endereço primeiro para obter o ID do endereço recém criado, que será usado para vincular o cliente ao endereço. O comando INSERT INTO adiciona uma nova linha na tabela endereco com os dados fornecidos, e o método lastInsertId() do PDO é usado para recuperar o ID do endereço que acabou de ser inserido, permitindo que o cliente seja associado corretamente a esse endereço.
    $stmt = $pdo->prepare("INSERT INTO endereco (rua, numero, cidade, estado, cep)
                           VALUES (:rua, :numero, :cidade, :estado, :cep)");
    $stmt->execute([':rua' => $rua, ':numero' => $numero,
                    ':cidade' => $cidade, ':estado' => $estado, ':cep' => $cep]);

    $idEndereco = $pdo->lastInsertId(); // Pega o ID do endereço recém criado para usar na tabela cliente como chave estrangeira 

    // 2. Salva o cliente usando o ID do endereço para criar a relação entre cliente e endereço. O comando INSERT INTO adiciona uma nova linha na tabela cliente com o nome, CPF e o ID do endereço que foi inserido na etapa anterior, estabelecendo a ligação entre o cliente e seu endereço no banco de dados.
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
