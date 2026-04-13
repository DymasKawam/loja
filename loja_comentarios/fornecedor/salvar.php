<?php
// ============================================================
// CORREÇÃO DE BUG: o arquivo original estava inserindo na
// tabela "cliente" com o campo "cpf" em vez de inserir na
// tabela "fornecedor" com o campo "cnpj". Além disso, não
// processava os produtos do formulário.
// ============================================================

// require_once inclui auth.php apenas uma vez, fornecendo as funções de autenticação
require_once '../auth.php';
// exigir_papel('admin') garante que só o administrador acessa esta rota;
// vendedores e usuários não logados são redirecionados
exigir_papel('admin');

// include carrega conexao.php que cria o objeto $pdo para acesso ao banco
include("../conexao.php");

// ── Dados do Fornecedor ──────────────────────────────────────
// $_POST é o array com os dados enviados pelo formulário via método POST
$nome   = trim($_POST['nome']);   // trim() remove espaços extras no início e fim do nome
$cnpj   = $_POST['cnpj'];         // CNPJ do fornecedor (14 dígitos, somente números)

// ── Dados do Endereço ────────────────────────────────────────
$rua    = $_POST['rua'];     // Rua do endereço do fornecedor
$numero = $_POST['numero'];  // Número
$cidade = $_POST['cidade'];  // Cidade
// strtoupper() converte o estado para maiúsculas (ex: "sp" → "SP")
// trim() remove espaços acidentais que o usuário possa ter digitado
$estado = strtoupper(trim($_POST['estado']));
$cep    = $_POST['cep'];     // CEP

// ── Arrays de Produtos ───────────────────────────────────────
// Esses arrays chegam porque o formulário usa name="produto[]", name="preco[]" etc.
// O PHP agrupa campos com o mesmo name[] automaticamente em arrays
$produtos    = $_POST['produto']    ?? []; // IDs dos produtos selecionados
$precos      = $_POST['preco']      ?? []; // Preços de compra de cada produto
$quantidades = $_POST['quantidade'] ?? []; // Quantidades recebidas de cada produto

try {
    // beginTransaction() abre uma transação — todas as inserções acontecem juntas
    // Se qualquer passo falhar, rollBack() desfaz tudo e nenhum dado fica "pela metade" no banco
    $pdo->beginTransaction();

    // ── Passo 1: Salva o endereço ────────────────────────────
    // prepare() cria uma consulta parametrizada; os :parametros evitam SQL Injection
    $stmt = $pdo->prepare("INSERT INTO endereco (rua, numero, cidade, estado, cep)
                           VALUES (:rua, :numero, :cidade, :estado, :cep)");
    // execute() substitui os :parametros pelos valores reais e executa a query
    $stmt->execute([
        ':rua'    => $rua,
        ':numero' => $numero,
        ':cidade' => $cidade,
        ':estado' => $estado,
        ':cep'    => $cep
    ]);

    // lastInsertId() retorna o ID gerado pelo banco para o endereço recém inserido
    // Esse ID será gravado no campo "endereco_forncedor" da tabela fornecedor
    $idEndereco = $pdo->lastInsertId();

    // ── Passo 2: Salva o fornecedor ──────────────────────────
    // CORREÇÃO: agora insere corretamente na tabela "fornecedor" com "cnpj" e "endereco_forncedor"
    // (note: "forncedor" é o nome real da coluna no banco — há um erro de digitação no banco)
    $stmt2 = $pdo->prepare("INSERT INTO fornecedor (nome, cnpj, endereco_forncedor)
                            VALUES (:nome, :cnpj, :end)");
    $stmt2->execute([
        ':nome' => $nome,
        ':cnpj' => $cnpj,
        ':end'  => $idEndereco
    ]);

    // Captura o ID do fornecedor criado para vincular os produtos a seguir
    $idFornecedor = $pdo->lastInsertId();

    // ── Passo 3: Salva cada produto vinculado ao fornecedor ──
    // count() retorna o número de produtos no array; o loop percorre cada um
    for ($i = 0; $i < count($produtos); $i++) {
        $idProduto = (int)   $produtos[$i];    // (int) converte para inteiro, evitando injeção
        $preco     = (float) $precos[$i];      // (float) garante que o preço seja decimal
        $qtd       = (int)   $quantidades[$i]; // (int) garante que a quantidade seja inteira

        // Ignora linhas em que o produto não foi selecionado ou a quantidade é inválida
        if ($idProduto <= 0 || $qtd <= 0) continue; // continue pula para a próxima iteração do loop

        // Registra a relação fornecedor ↔ produto com o preço de compra nesta entrada
        $stmtFP = $pdo->prepare("INSERT INTO fornecedor_produto (id_fornecedor, id_produto, preco_compra)
                                  VALUES (:f, :p, :preco)");
        $stmtFP->execute([':f' => $idFornecedor, ':p' => $idProduto, ':preco' => $preco]);

        // Atualiza o estoque do produto:
        // - Se ainda não existe registro de estoque para esse produto, insere com a quantidade recebida
        // - Se já existe, soma a nova quantidade ao saldo atual (ON DUPLICATE KEY UPDATE)
        // :q1 e :q2 têm o mesmo valor mas nomes diferentes — PDO não permite reutilizar o mesmo parâmetro
        $stmtEst = $pdo->prepare("INSERT INTO estoque (id_produto, quantidade)
                                   VALUES (:p2, :q1)
                                   ON DUPLICATE KEY UPDATE quantidade = quantidade + :q2");
        $stmtEst->execute([':p2' => $idProduto, ':q1' => $qtd, ':q2' => $qtd]);
    }

    // commit() confirma todas as inserções no banco de dados de forma permanente
    $pdo->commit();

    // header() redireciona para o formulário exibindo mensagem de sucesso
    // O "?" inicia os parâmetros da URL; "sucesso" é lido por cadastrar.php para mostrar o alerta
    header("Location: cadastrar.php?sucesso");

} catch (Exception $e) {
    // rollBack() desfaz tudo: endereço, fornecedor e produtos são removidos se algo falhar
    $pdo->rollBack();
    // urlencode() codifica a mensagem de erro para ser transmitida com segurança pela URL
    header("Location: cadastrar.php?erro=" . urlencode($e->getMessage()));
}
// exit para a execução imediatamente após o redirecionamento
exit;
?>
