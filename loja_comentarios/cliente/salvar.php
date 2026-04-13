<?php
// require_once garante que auth.php seja carregado apenas uma vez, mesmo se incluído em vários lugares
require_once '../auth.php';
// exigir_login() verifica se há uma sessão ativa — se não houver, redireciona para login.php
exigir_login();

// include carrega conexao.php, que cria e disponibiliza o objeto $pdo para acesso ao banco
include("../conexao.php");

// $_POST contém os dados enviados pelo formulário HTML via método POST
$nome   = $_POST['nome'];   // Nome completo do cliente
$cpf    = $_POST['cpf'];    // CPF com apenas números (11 dígitos)
$rua    = $_POST['rua'];    // Rua do endereço
$numero = $_POST['numero']; // Número da residência
$cidade = $_POST['cidade']; // Cidade
$estado = $_POST['estado']; // Estado (UF, ex: SP)
$cep    = $_POST['cep'];    // CEP (ex: 01001-000)

try {
    // beginTransaction() abre uma transação — as duas inserções (endereço e cliente) acontecem juntas
    // Se uma falhar, a outra é desfeita também (rollBack), garantindo consistência no banco
    $pdo->beginTransaction();

    // prepare() cria uma consulta parametrizada (evita SQL Injection)
    // Os :parametros são substituídos pelos valores reais apenas na hora do execute()
    $stmt = $pdo->prepare("INSERT INTO endereco (rua, numero, cidade, estado, cep)
                           VALUES (:rua, :numero, :cidade, :estado, :cep)");
    // execute() passa os valores reais para os parâmetros e executa a query no banco
    $stmt->execute([':rua' => $rua, ':numero' => $numero,
                    ':cidade' => $cidade, ':estado' => $estado, ':cep' => $cep]);

    // lastInsertId() retorna o ID gerado automaticamente para o endereço recém inserido
    // Esse ID é usado para vincular o cliente ao endereço correto
    $idEndereco = $pdo->lastInsertId();

    // Insere o cliente na tabela "cliente" com o ID do endereço que acabou de ser criado
    $stmt2 = $pdo->prepare("INSERT INTO cliente (nome, cpf, endereco_cliente)
                            VALUES (:nome, :cpf, :id)");
    $stmt2->execute([':nome' => $nome, ':cpf' => $cpf, ':id' => $idEndereco]);

    // commit() confirma definitivamente as duas inserções no banco de dados
    $pdo->commit();

    // header() redireciona o navegador — padrão PRG (Post/Redirect/Get) evita duplicação ao atualizar
    // ?msg=sucesso é lido por cadastrar.php para exibir a mensagem de confirmação
    header("Location: cadastrar.php?msg=sucesso");

} catch (Exception $e) {
    // rollBack() desfaz toda a transação — se o cliente não foi inserido, o endereço também some
    $pdo->rollBack();
    // urlencode() codifica caracteres especiais da mensagem de erro para caber na URL
    header("Location: cadastrar.php?msg=erro&detalhe=" . urlencode($e->getMessage()));
}
// exit interrompe a execução do script imediatamente, necessário após header() para o redirect funcionar
exit;
?>
