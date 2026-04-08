<?php
require_once '../auth.php';
exigir_papel('admin');
?>
<?php
include("../conexao.php"); // Conecta ao banco para carregar a lista de produtos

// Busca todos os produtos cadastrados para preencher o select de produtos
$produtos = $pdo->query("SELECT id, nome FROM produto ORDER BY nome")->fetchAll();

// Verifica se a URL traz mensagem de retorno após o cadastro
$mensagem = "";
if (isset($_GET['sucesso'])) {
    $mensagem = '<div class="alerta alerta-sucesso">✅ Fornecedor cadastrado com sucesso!</div>';
}
if (isset($_GET['erro'])) {
    $mensagem = '<div class="alerta alerta-erro">❌ Erro: ' . htmlspecialchars($_GET['erro']) . '</div>';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar Fornecedor</title>
  <link rel="stylesheet" href="../estilo.css">
</head>
<body>

<nav class="navbar">
  <a href="../index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>
  <div class="nav-links">
    <a href="../cliente/cadastrar.php">Clientes</a>
    <a href="../produto/cadastrar.php">Produtos</a>
    <a href="../vendedor/cadastrar.php">Vendedores</a>
    <a href="../fornecedor/cadastrar.php" class="ativo">Fornecedores</a>
    <a href="../estoque/entrada.php">Estoque</a>
    <a href="../venda/vender.php">Vendas</a>
  </div>
</nav>

<div class="container">

  <a href="../index.php" class="link-voltar">← Início</a>

  <!-- Exibe mensagem de sucesso ou erro se houver -->
  <?= $mensagem ?>

  <!-- Formulário principal que envia todos os dados para salvar.php -->
  <form action="salvar.php" method="POST">

    <!-- ── Bloco 1: Dados do Fornecedor ── -->
    <div class="card">
      <div class="card-topo">
        <h2>Cadastrar Fornecedor</h2>
        <p>Dados do fornecedor, endereço e produtos fornecidos — tudo em uma tela só</p>
      </div>

      <p class="secao-titulo">Dados do Fornecedor</p>
      <div class="form-grade-2">

        <div class="form-grupo span-2">
          <label class="form-label" for="nome">Razão Social / Nome</label>
          <input class="form-control" type="text" id="nome" name="nome"
            placeholder="Ex: Distribuidora Brasil Ltda" required>
        </div>

        <div class="form-grupo span-2">
          <label class="form-label" for="cnpj">CNPJ</label>
          <!-- oninput remove qualquer coisa que não seja número ou ./- enquanto digita -->
          <input class="form-control" type="text" id="cnpj" name="cnpj"
            placeholder="Apenas números (14 dígitos)"
            oninput="this.value = this.value.replace(/[^0-9]/g,'')"
            maxlength="14" required>
        </div>

      </div>

      <!-- ── Endereço do fornecedor ── -->
      <p class="secao-titulo">Endereço</p>
      <div class="form-grade-2">

        <div class="form-grupo span-2">
          <label class="form-label" for="rua">Rua</label>
          <input class="form-control" type="text" id="rua" name="rua"
            placeholder="Ex: Av. Paulista" required>
        </div>

        <div class="form-grupo">
          <label class="form-label" for="numero">Número</label>
          <input class="form-control" type="text" id="numero" name="numero" required>
        </div>

        <div class="form-grupo">
          <label class="form-label" for="cidade">Cidade</label>
          <input class="form-control" type="text" id="cidade" name="cidade" required>
        </div>

        <div class="form-grupo">
          <label class="form-label" for="estado">Estado (UF)</label>
          <input class="form-control" type="text" id="estado" name="estado"
            maxlength="2" placeholder="Ex: SP" required>
        </div>

        <div class="form-grupo">
          <label class="form-label" for="cep">CEP</label>
          <input class="form-control" type="text" id="cep" name="cep"
            maxlength="9" placeholder="00000-000" required>
        </div>

      </div>
    </div><!-- fim do card dados do fornecedor -->

    <!-- ── Bloco 2: Produtos Fornecidos ── -->
    <div class="card">
      <div class="card-topo">
        <h2>📦 Produtos Fornecidos</h2>
        <p>Adicione os produtos que este fornecedor entregou e as quantidades</p>
      </div>

      <?php if (empty($produtos)): ?>
        <!-- Aviso caso não haja produtos cadastrados para selecionar -->
        <div class="alerta alerta-info">
          ⚠️ Nenhum produto cadastrado ainda.
          <a href="../produto/cadastrar.php">Cadastre um produto primeiro</a> e volte aqui.
        </div>
      <?php else: ?>

        <!-- Container onde as linhas de produto serão inseridas pelo JavaScript -->
        <div id="container-produtos">
          <!-- Primeira linha de produto — já vem preenchida na tela -->
          <div class="produto-linha">
            <div class="form-grupo" style="margin:0">
              <label class="form-label">Produto</label>
              <!-- O "[]" no name indica que esse campo faz parte de um array -->
              <select class="form-control" name="produto[]" required>
                <option value="">— Selecione —</option>
                <!-- Gera as opções dinamicamente com os produtos do banco -->
                <?php foreach ($produtos as $p): ?>
                  <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-grupo" style="margin:0">
              <label class="form-label">Preço de compra (R$)</label>
              <input class="form-control" type="number" name="preco[]" step="0.01" min="0"
                placeholder="0.00" required>
            </div>

            <div class="form-grupo" style="margin:0">
              <label class="form-label">Quantidade recebida</label>
              <input class="form-control" type="number" name="quantidade[]" min="1"
                placeholder="0" required>
            </div>

            <!-- Placeholder vazio para alinhar o layout (a primeira linha não tem botão de remover) -->
            <div></div>
          </div>
        </div>

        <!-- Botão para adicionar mais linhas de produto via JavaScript -->
        <button type="button" class="btn btn-adicionar" onclick="adicionarProduto()">
          ➕ Adicionar outro produto
        </button>

      <?php endif; ?>
    </div><!-- fim do card de produtos -->

    <!-- Botão de submissão final -->
    <div class="form-acoes">
      <button type="submit" class="btn btn-primario">✅ Cadastrar Fornecedor</button>
      <a href="../index.html" class="btn btn-contorno">Cancelar</a>
    </div>

  </form>

</div><!-- fim do container -->

<script>
// Transforma o array PHP de produtos em um objeto JavaScript para gerar os selects dinamicamente
const produtos = <?= json_encode($produtos) ?>;

// Essa função é chamada ao clicar em "Adicionar outro produto"
function adicionarProduto() {
    const container = document.getElementById('container-produtos'); // pega o bloco onde as linhas ficam

    // Monta as opções do select com todos os produtos disponíveis
    const options = '<option value="">— Selecione —</option>' +
        produtos.map(p => `<option value="${p.id}">${p.nome}</option>`).join('');

    // Cria um novo elemento div com a mesma estrutura da primeira linha
    const div = document.createElement('div');
    div.className = 'produto-linha'; // aplica o mesmo estilo da linha de produto

    // Preenche a linha com os campos de produto, preço, quantidade e o botão de remover
    div.innerHTML = `
        <div class="form-grupo" style="margin:0">
          <label class="form-label">Produto</label>
          <select class="form-control" name="produto[]" required>${options}</select>
        </div>
        <div class="form-grupo" style="margin:0">
          <label class="form-label">Preço de compra (R$)</label>
          <input class="form-control" type="number" name="preco[]" step="0.01" min="0" placeholder="0.00" required>
        </div>
        <div class="form-grupo" style="margin:0">
          <label class="form-label">Quantidade recebida</label>
          <input class="form-control" type="number" name="quantidade[]" min="1" placeholder="0" required>
        </div>
        <div style="padding-bottom:0.25rem">
          <label class="form-label">&nbsp;</label>
          <!-- Botão de remover: ao clicar, remove o div pai inteiro da tela -->
          <button type="button" class="btn btn-perigo btn-sm" onclick="this.closest('.produto-linha').remove()">✕</button>
        </div>
    `;

    container.appendChild(div); // Insere a nova linha no final do container
}
</script>

</body>
</html>
