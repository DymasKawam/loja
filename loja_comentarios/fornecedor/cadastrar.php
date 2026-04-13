<?php
// require_once inclui auth.php uma única vez — garante que as funções de autenticação estejam disponíveis
require_once '../auth.php';
// exigir_papel('admin') bloqueia vendedores e usuários não logados; só admin acessa esta página
exigir_papel('admin');
?>
<?php
// include carrega conexao.php e cria $pdo, necessário para buscar os produtos do banco
include("../conexao.php");

// Busca todos os produtos cadastrados em ordem alfabética para preencher o select do formulário
$produtos = $pdo->query("SELECT id, nome FROM produto ORDER BY nome")->fetchAll();

// Verifica se a URL contém ?sucesso (vindo de salvar.php após cadastro bem-sucedido)
$mensagem = "";
if (isset($_GET['sucesso'])) {
    // Exibe alerta verde de sucesso
    $mensagem = '<div class="alerta alerta-sucesso">✅ Fornecedor cadastrado com sucesso!</div>';
}
// Verifica se a URL contém ?erro (vindo de salvar.php após falha)
if (isset($_GET['erro'])) {
    // htmlspecialchars() evita que a mensagem de erro contenha HTML malicioso
    $mensagem = '<div class="alerta alerta-erro">❌ Erro: ' . htmlspecialchars($_GET['erro']) . '</div>';
}
?>
<!DOCTYPE html> <!-- Declara que o documento usa HTML5 -->
<html lang="pt-BR"> <!-- Idioma da página: português do Brasil -->
<head>
  <meta charset="UTF-8"> <!-- Codificação de caracteres para suportar acentos e caracteres especiais -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsividade para celulares -->
  <title>Cadastrar Fornecedor</title> <!-- Título na aba do navegador -->
  <link rel="stylesheet" href="../estilo.css"> <!-- Importa os estilos do projeto -->
</head>
<body>

<!-- <nav> define a barra de navegação; class="navbar" aplica os estilos CSS do menu -->
<nav class="navbar">
  <a href="../index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>
  <div class="nav-links"> <!-- Agrupa os links do menu -->
    <a href="../cliente/cadastrar.php">Clientes</a>
    <a href="../produto/cadastrar.php">Produtos</a>
    <a href="../vendedor/cadastrar.php">Vendedores</a>
    <a href="../fornecedor/cadastrar.php" class="ativo">Fornecedores</a> <!-- class="ativo" destaca a página atual -->
    <a href="../estoque/entrada.php">Estoque</a>
    <a href="../venda/vender.php">Vendas</a>
  </div>
</nav>

<div class="container"> <!-- Limita a largura e centraliza o conteúdo da página -->

  <a href="../index.php" class="link-voltar">← Início</a> <!-- Link para voltar ao painel principal -->

  <!-- <?= $mensagem ?> imprime o HTML da mensagem de sucesso ou erro se houver -->
  <?= $mensagem ?>

  <!-- Formulário único que envia dados do fornecedor, endereço e produtos para salvar.php -->
  <form action="salvar.php" method="POST"> <!-- method="POST" envia os dados no corpo da requisição, não na URL -->

    <!-- ── Bloco 1: Dados do Fornecedor ── -->
    <div class="card"> <!-- card é o painel branco com sombra definido no estilo.css -->
      <div class="card-topo"> <!-- Cabeçalho do card com título e descrição -->
        <h2>Cadastrar Fornecedor</h2>
        <p>Dados do fornecedor, endereço e produtos fornecidos — tudo em uma tela só</p>
      </div>

      <p class="secao-titulo">Dados do Fornecedor</p> <!-- Subtítulo de seção -->
      <div class="form-grade-2"> <!-- grade de dois colunas para os campos do formulário -->

        <div class="form-grupo span-2"> <!-- span-2 faz o campo ocupar as duas colunas da grade -->
          <label class="form-label" for="nome">Razão Social / Nome</label> <!-- <label> associa o texto ao campo pelo atributo "for" -->
          <input class="form-control" type="text" id="nome" name="nome"
            placeholder="Ex: Distribuidora Brasil Ltda" required> <!-- required impede o envio sem preencher -->
        </div>

        <div class="form-grupo span-2">
          <label class="form-label" for="cnpj">CNPJ</label>
          <!-- oninput é um evento JavaScript que executa a cada tecla digitada
               replace(/[^0-9]/g,'') remove qualquer caractere que não seja número -->
          <input class="form-control" type="text" id="cnpj" name="cnpj"
            placeholder="Apenas números (14 dígitos)"
            oninput="this.value = this.value.replace(/[^0-9]/g,'')"
            maxlength="14" required> <!-- maxlength limita a 14 caracteres (tamanho do CNPJ) -->
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

      <?php if (empty($produtos)): ?> <!-- empty() retorna true se não houver produtos cadastrados -->
        <!-- Aviso mostrado quando não há produtos no banco para selecionar -->
        <div class="alerta alerta-info">
          ⚠️ Nenhum produto cadastrado ainda.
          <a href="../produto/cadastrar.php">Cadastre um produto primeiro</a> e volte aqui.
        </div>
      <?php else: ?> <!-- else: exibe o formulário de produtos somente quando há pelo menos um produto -->

        <!-- Container onde as linhas de produto serão inseridas pelo JavaScript -->
        <div id="container-produtos">
          <!-- Primeira linha de produto — renderizada pelo PHP diretamente na página -->
          <div class="produto-linha">
            <div class="form-grupo" style="margin:0">
              <label class="form-label">Produto</label>
              <!-- name="produto[]" — os colchetes [] indicam que esse campo é parte de um array
                   Quando o formulário é enviado, PHP recebe $_POST['produto'] como um array -->
              <select class="form-control" name="produto[]" required>
                <option value="">— Selecione —</option>
                <!-- foreach percorre o array de produtos e gera uma <option> para cada um -->
                <?php foreach ($produtos as $p): ?>
                  <!-- <?= $p['id'] ?> é a forma curta de <?php echo $p['id']; ?> -->
                  <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-grupo" style="margin:0">
              <label class="form-label">Preço de compra (R$)</label>
              <!-- type="number" limita a entrada a valores numéricos
                   step="0.01" permite inserir centavos; min="0" impede valores negativos -->
              <input class="form-control" type="number" name="preco[]" step="0.01" min="0"
                placeholder="0.00" required>
            </div>

            <div class="form-grupo" style="margin:0">
              <label class="form-label">Quantidade recebida</label>
              <input class="form-control" type="number" name="quantidade[]" min="1"
                placeholder="0" required>
            </div>

            <!-- Espaço em branco para alinhar o layout (a primeira linha não tem botão remover) -->
            <div></div>
          </div>
        </div>

        <!-- Botão que chama a função JavaScript adicionarProduto() para criar mais linhas -->
        <button type="button" class="btn btn-adicionar" onclick="adicionarProduto()">
          ➕ Adicionar outro produto
        </button>

      <?php endif; ?>
    </div><!-- fim do card de produtos -->

    <!-- Botões de ação do formulário completo -->
    <div class="form-acoes">
      <button type="submit" class="btn btn-primario">✅ Cadastrar Fornecedor</button>
      <!-- CORREÇÃO: link apontava para ../index.html; corrigido para ../index.php -->
      <a href="../index.php" class="btn btn-contorno">Cancelar</a>
    </div>

  </form>

</div><!-- fim do container -->

<script>
// json_encode() converte o array PHP de produtos em um objeto JSON legível pelo JavaScript
// Isso permite gerar os selects dinamicamente sem nova requisição ao servidor
const produtos = <?= json_encode($produtos) ?>;

// adicionarProduto() é chamada ao clicar em "Adicionar outro produto"
function adicionarProduto() {
    // getElementById() busca o elemento HTML pelo seu atributo id
    const container = document.getElementById('container-produtos');

    // map() transforma cada produto em uma string <option>; join('') une tudo em uma string
    const options = '<option value="">— Selecione —</option>' +
        produtos.map(p => `<option value="${p.id}">${p.nome}</option>`).join('');

    // createElement() cria um novo elemento <div> em memória (ainda não está na página)
    const div = document.createElement('div');
    // className define a classe CSS do elemento — igual à primeira linha de produto
    div.className = 'produto-linha';

    // innerHTML define o conteúdo HTML interno do div criado
    // Template literals (crase) permitem inserir variáveis JavaScript diretamente no HTML
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
          <!-- onclick usa closest('.produto-linha') para subir na árvore DOM até o div pai e removê-lo -->
          <button type="button" class="btn btn-perigo btn-sm" onclick="this.closest('.produto-linha').remove()">✕</button>
        </div>
    `;

    // appendChild() insere o novo div no final do container de produtos
    container.appendChild(div);
}
</script>

</body>
</html>
