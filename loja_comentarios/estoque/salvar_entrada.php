<?php
// require_once inclui auth.php apenas uma vez para disponibilizar exigir_papel()
require_once '../auth.php';
// exigir_papel('admin') impede que vendedores ou usuários não logados registrem entradas de estoque
exigir_papel('admin');

// include carrega conexao.php e cria o objeto $pdo para acesso ao banco de dados
include("../conexao.php");

// (int) converte para inteiro — garante que os IDs sejam numéricos e evita SQL Injection
$idFornecedor = (int)   $_POST['id_fornecedor']; // ID do fornecedor que realizou a entrega
$idProduto    = (int)   $_POST['id_produto'];    // ID do produto recebido
// (float) converte para decimal — garante que o preço aceite centavos (ex: 12.50)
$preco        = (float) $_POST['preco_compra'];  // Preço pago por unidade nesta compra
// (int) converte para inteiro — estoque é sempre em números inteiros de unidades
$qtd          = (int)   $_POST['quantidade'];    // Quantidade de unidades recebidas

try {
    // beginTransaction() abre uma transação — os dois inserts abaixo acontecem juntos
    // Se qualquer um falhar, rollBack() desfaz tudo e o banco permanece consistente
    $pdo->beginTransaction();

    // Passo 1: registra o histórico da compra na tabela fornecedor_produto
    // Isso vincula o fornecedor ao produto com o preço pago nesta entrada específica
    $stmt = $pdo->prepare("INSERT INTO fornecedor_produto (id_fornecedor, id_produto, preco_compra)
                           VALUES (:f, :p, :preco)");
    $stmt->execute([':f' => $idFornecedor, ':p' => $idProduto, ':preco' => $preco]);

    // Passo 2: atualiza o saldo do estoque do produto
    // ON DUPLICATE KEY UPDATE trata dois casos com uma única query:
    //   - Produto sem estoque ainda → INSERT: cria a linha com a quantidade recebida
    //   - Produto já com estoque   → UPDATE: soma a quantidade ao saldo existente
    // :q1 e :q2 têm o mesmo valor mas precisam de nomes diferentes — o PDO não reutiliza o mesmo parâmetro
    $stmt2 = $pdo->prepare("INSERT INTO estoque (id_produto, quantidade)
                            VALUES (:p2, :q1)
                            ON DUPLICATE KEY UPDATE quantidade = quantidade + :q2");
    $stmt2->execute([':p2' => $idProduto, ':q1' => $qtd, ':q2' => $qtd]);

    // commit() confirma as duas operações definitivamente no banco de dados
    $pdo->commit();

    // Redireciona para entrada.php com a mensagem de sucesso na URL
    header("Location: entrada.php?msg=entrada_ok");

} catch (Exception $e) {
    // rollBack() desfaz as duas operações — nenhum dado fica salvo de forma incompleta
    $pdo->rollBack();
    // urlencode() codifica a mensagem de erro para ser transmitida com segurança pela URL
    header("Location: entrada.php?msg=erro&detalhe=" . urlencode($e->getMessage()));
}
// exit interrompe o script para que o redirecionamento funcione corretamente
exit;
?>
