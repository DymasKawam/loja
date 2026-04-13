<?php
// require_once inclui auth.php uma única vez para disponibilizar exigir_papel()
require_once '../auth.php';
// exigir_papel('admin') impede que vendedores acessem a consulta de estoque
exigir_papel('admin');
?>
<?php
// include carrega conexao.php e cria $pdo para as queries abaixo
include("../conexao.php");

// ── Bloco AJAX: responde com JSON quando a requisição traz ?acao=... ──
// Isso permite que o JavaScript atualize a tabela sem recarregar a página inteira
if (isset($_GET['acao'])) { // isset() verifica se o parâmetro "acao" chegou na URL
    header('Content-Type: application/json'); // Informa ao navegador que a resposta é JSON

    // Consulta 1: lista produtos fornecidos por um fornecedor específico
    if ($_GET['acao'] === 'por_fornecedor' && !empty($_GET['id'])) {
        $id = (int) $_GET['id']; // (int) converte para inteiro — evita SQL Injection

        $stmt = $pdo->prepare("
            SELECT p.nome, fp.preco_compra, COALESCE(e.quantidade, 0) AS estoque
            FROM fornecedor_produto fp
            JOIN produto p ON p.id = fp.id_produto       -- JOIN conecta com a tabela de produtos
            LEFT JOIN estoque e ON e.id_produto = p.id   -- LEFT JOIN inclui produtos sem estoque (quantidade = 0)
            WHERE fp.id_fornecedor = :id
            ORDER BY p.nome                              -- ordena em ordem alfabética
        ");
        $stmt->execute([':id' => $id]);
        // json_encode() converte o array PHP em formato JSON para o JavaScript ler
        echo json_encode($stmt->fetchAll());
        exit; // Para aqui — não renderiza o HTML abaixo
    }

    // Consulta 2: lista fornecedores de um produto específico, ordenados pelo menor preço
    if ($_GET['acao'] === 'por_produto' && !empty($_GET['id'])) {
        $id = (int) $_GET['id'];

        $stmt = $pdo->prepare("
            SELECT f.nome, f.cnpj, fp.preco_compra, e.cidade, e.estado
            FROM fornecedor_produto fp
            JOIN fornecedor f ON f.id = fp.id_fornecedor        -- JOIN com a tabela de fornecedores
            LEFT JOIN endereco e ON e.id = f.endereco_forncedor -- LEFT JOIN com o endereço do fornecedor
            WHERE fp.id_produto = :id
            ORDER BY fp.preco_compra ASC                        -- do mais barato para o mais caro
        ");
        $stmt->execute([':id' => $id]);
        echo json_encode($stmt->fetchAll());
        exit;
    }

    echo json_encode([]); // Retorna array vazio se a ação não foi reconhecida
    exit;
}

// ── Carrega as listas para preencher os <select> da página ──
// Busca todos os fornecedores para o select da aba "Por Fornecedor"
$fornecedores = $pdo->query("SELECT id, nome FROM fornecedor ORDER BY nome")->fetchAll();
// DISTINCT evita que o mesmo produto apareça várias vezes caso tenha vários fornecedores
$produtos     = $pdo->query("
    SELECT DISTINCT p.id, p.nome
    FROM produto p
    JOIN fornecedor_produto fp ON fp.id_produto = p.id
    ORDER BY p.nome
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consulta de Estoque</title>
  <link rel="stylesheet" href="../estilo.css">
  <!-- Tom Select: componente de select com busca e autocomplete (CDN externo) -->
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
  <style>
    /* Adapta o visual do Tom Select ao tema do projeto */
    .ts-wrapper .ts-control { font-size: 0.925rem; border: 1px solid var(--borda, #d1d5db); border-radius: 6px; padding: 0.45rem 0.65rem; background: var(--fundo-input, #fff); }
    .ts-wrapper.focus .ts-control { border-color: var(--primario, #6366f1); box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
    .ts-dropdown { border: 1px solid var(--borda, #d1d5db); border-radius: 6px; font-size: 0.925rem; }
    .ts-dropdown .option.selected, .ts-dropdown .option:hover { background: var(--primario, #6366f1); color: #fff; }
  </style>
</head>
<body>

<nav class="navbar">
  <a href="../index.php" class="nav-brand">🛒 <span class="destaque">Loja</span> Sistema</a>
  <div class="nav-links">
    <a href="../cliente/cadastrar.php">Clientes</a>
    <a href="../produto/cadastrar.php">Produtos</a>
    <a href="../vendedor/cadastrar.php">Vendedores</a>
    <a href="../fornecedor/cadastrar.php">Fornecedores</a>
    <a href="../estoque/entrada.php" class="ativo">Estoque</a> <!-- class="ativo" destaca o link atual -->
    <a href="../venda/vender.php">Vendas</a>
  </div>
</nav>

<div class="container-largo">

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem">
    <a href="../index.php" class="link-voltar">← Início</a>
    <a href="entrada.php" class="btn btn-contorno">📥 Registrar Entrada</a>
  </div>

  <div class="card">
    <div class="card-topo">
      <h2>🔍 Consulta de Estoque</h2>
      <p>Pesquise por fornecedor ou por produto</p>
    </div>

    <!-- Sistema de abas para alternar entre os dois tipos de consulta -->
    <div class="abas"> <!-- <div class="abas"> é o container dos botões de aba -->
      <!-- onclick="trocarAba(...)" chama a função JavaScript que troca o painel visível -->
      <button class="aba-btn ativa" onclick="trocarAba('painel-fornecedor', this)">
        🏭 Por Fornecedor
      </button>
      <button class="aba-btn" onclick="trocarAba('painel-produto', this)">
        📦 Por Produto
      </button>
    </div>

    <!-- ── Painel 1: consulta por fornecedor ── -->
    <div id="painel-fornecedor"> <!-- id é usado pelo JavaScript para mostrar/esconder este painel -->
      <p style="color:var(--text-muted); font-size:0.875rem; margin-bottom:1rem">
        Selecione um fornecedor para ver todos os produtos que ele fornece
      </p>

      <div class="form-grupo" style="max-width:420px">
        <label class="form-label" for="sel-fornecedor">Fornecedor</label>
        <!-- O Tom Select transforma este <select> em um campo pesquisável -->
        <!-- O evento onChange é definido no JavaScript abaixo via TomSelect API -->
        <select class="form-control" id="sel-fornecedor">
          <option value="">— Selecione um fornecedor —</option>
          <?php foreach ($fornecedores as $f): ?> <!-- foreach gera uma <option> por fornecedor -->
            <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- <div class="area-resultado"> é preenchida dinamicamente pelo JavaScript -->
      <div class="area-resultado" id="resultado-fornecedor"></div>
    </div>

    <!-- ── Painel 2: consulta por produto (começa oculto) ── -->
    <div id="painel-produto" style="display:none"> <!-- display:none esconde este painel inicialmente -->
      <p style="color:var(--text-muted); font-size:0.875rem; margin-bottom:1rem">
        Selecione um produto para ver todos os fornecedores que o fornecem
      </p>

      <div class="form-grupo" style="max-width:420px">
        <label class="form-label" for="sel-produto">Produto</label>
        <select class="form-control" id="sel-produto">
          <option value="">— Selecione um produto —</option>
          <?php foreach ($produtos as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="area-resultado" id="resultado-produto"></div>
    </div>

  </div><!-- fim do card -->
</div><!-- fim do container -->

<script>
// trocarAba() alterna qual painel de consulta está visível na tela
function trocarAba(idPainel, botao) {
    // Esconde os dois painéis antes de exibir o selecionado
    document.getElementById('painel-fornecedor').style.display = 'none';
    document.getElementById('painel-produto').style.display    = 'none';
    // forEach remove a classe "ativa" de todos os botões de aba
    document.querySelectorAll('.aba-btn').forEach(b => b.classList.remove('ativa'));
    // Exibe somente o painel clicado e marca seu botão como ativo
    document.getElementById(idPainel).style.display = 'block';
    botao.classList.add('ativa');
}

// buscar() faz uma requisição AJAX para este mesmo arquivo PHP (com ?acao=...)
// e monta a tabela de resultados dinamicamente no HTML
async function buscar(acao, id) {
    if (!id) return; // Se nenhum item foi selecionado, não faz nada

    // Decide qual div de resultado atualizar com base na aba ativa
    const divId = acao === 'por_fornecedor' ? 'resultado-fornecedor' : 'resultado-produto';
    const div   = document.getElementById(divId);
    div.innerHTML = '<p style="color:var(--text-muted)">Carregando...</p>'; // Feedback visual enquanto carrega

    // fetch() faz a requisição HTTP; template literal monta a URL com os parâmetros corretos
    const res  = await fetch(`?acao=${acao}&id=${id}`);
    // res.json() converte a resposta JSON em um array JavaScript
    const rows = await res.json();

    if (!rows.length) { // rows.length retorna 0 quando o array de resultados está vazio
        div.innerHTML = '<p style="color:var(--text-muted); padding:1rem 0">Nenhum resultado encontrado.</p>';
        return;
    }

    // Começa a montar a tabela HTML como string
    let html = '<div class="tabela-wrapper"><table><thead><tr>';

    if (acao === 'por_fornecedor') {
        // Cabeçalho para a consulta por fornecedor
        html += '<th>Produto</th><th>Preço de compra</th><th>Estoque atual</th></tr></thead><tbody>';
        rows.forEach(r => {
            // parseFloat() converte o preço de string para número para usar toFixed(2)
            // replace('.', ',') troca o ponto decimal pelo padrão brasileiro (vírgula)
            const badge = r.estoque > 0
                ? `<span class="badge badge-verde">${r.estoque} un.</span>`
                : `<span class="badge badge-vermelho">Esgotado</span>`;
            html += `<tr>
                <td><strong>${r.nome}</strong></td>
                <td>R$ ${parseFloat(r.preco_compra).toFixed(2).replace('.', ',')}</td>
                <td>${badge}</td>
            </tr>`;
        });
    } else {
        // Cabeçalho para a consulta por produto — mostra os fornecedores do mais barato ao mais caro
        html += '<th>Fornecedor</th><th>Cidade / Estado</th><th>Preço de compra</th></tr></thead><tbody>';
        rows.forEach((r, i) => {
            // i === 0 é o primeiro resultado (mais barato) — recebe destaque visual verde
            const destaque = i === 0 ? 'style="background:#f0fdf4"' : '';
            const cidade   = (r.cidade || '—') + ' / ' + (r.estado || '—'); // '—' quando cidade/estado for null
            const preco    = parseFloat(r.preco_compra).toFixed(2).replace('.', ',');
            html += `<tr ${destaque}>
                <td>
                  <strong>${r.nome}</strong>
                  ${i === 0 ? '<span class="badge badge-verde" style="margin-left:0.5rem">Menor preço</span>' : ''}
                </td>
                <td>${cidade}</td>
                <td>R$ ${preco}</td>
            </tr>`;
        });
    }

    html += '</tbody></table></div>';
    div.innerHTML = html; // Substitui o conteúdo da div pelo HTML da tabela gerada
}
</script>

<!-- Carrega o JavaScript do Tom Select para transformar os <select> em campos com busca -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
  // Configurações base compartilhadas pelos dois selects
  const tsBase = { placeholder: '— Digite para buscar —', allowEmptyOption: true };

  // new TomSelect() transforma o <select> em um campo pesquisável
  // onChange: function(value) é chamado automaticamente quando o usuário seleciona um item
  new TomSelect('#sel-fornecedor', {
    ...tsBase, // ...tsBase espalha as propriedades do objeto base (spread operator)
    onChange: function(value) { buscar('por_fornecedor', value); }
  });

  new TomSelect('#sel-produto', {
    ...tsBase,
    onChange: function(value) { buscar('por_produto', value); }
  });
</script>

</body>
</html>
