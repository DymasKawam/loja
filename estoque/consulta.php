<?php
require_once '../auth.php';
exigir_papel('admin');
?>
<?php
include("../conexao.php"); // Conecta ao banco de dados

// ── Bloco AJAX: quando a página recebe ?acao=..., ela responde com JSON em vez de HTML ──
// Isso permite que o JavaScript atualize a tabela sem recarregar a página inteira
if (isset($_GET['acao'])) {
    header('Content-Type: application/json'); // Informa que a resposta é JSON, não HTML

    // Consulta 1: lista todos os produtos que um fornecedor específico fornece
    if ($_GET['acao'] === 'por_fornecedor' && !empty($_GET['id'])) {
        $id = (int) $_GET['id']; // Converte para inteiro — evita SQL Injection

        $stmt = $pdo->prepare("
            SELECT p.nome, fp.preco_compra, COALESCE(e.quantidade, 0) AS estoque
            FROM fornecedor_produto fp
            JOIN produto p ON p.id = fp.id_produto       -- junta com a tabela de produtos pelo ID
            LEFT JOIN estoque e ON e.id_produto = p.id   -- LEFT JOIN inclui produtos mesmo sem estoque
            WHERE fp.id_fornecedor = :id
            ORDER BY p.nome                              -- ordena em ordem alfabética
        ");
        $stmt->execute([':id' => $id]);
        echo json_encode($stmt->fetchAll()); // Envia o resultado como JSON para o JavaScript
        exit; // Para aqui — não renderiza o HTML abaixo
    }

    // Consulta 2: lista todos os fornecedores que fornecem um produto específico
    if ($_GET['acao'] === 'por_produto' && !empty($_GET['id'])) {
        $id = (int) $_GET['id']; // Converte para inteiro

        $stmt = $pdo->prepare("
            SELECT f.nome, f.cnpj, fp.preco_compra, e.cidade, e.estado
            FROM fornecedor_produto fp
            JOIN fornecedor f ON f.id = fp.id_fornecedor         -- junta com a tabela de fornecedores
            LEFT JOIN endereco e ON e.id = f.endereco_forncedor  -- pega o endereço do fornecedor
            WHERE fp.id_produto = :id
            ORDER BY fp.preco_compra ASC                         -- do mais barato para o mais caro
        ");
        $stmt->execute([':id' => $id]);
        echo json_encode($stmt->fetchAll());
        exit;
    }

    echo json_encode([]); // Retorna array vazio se a ação não foi reconhecida
    exit;
}

// ── Carrega os dados para preencher os selects da página ──
// Só pega produtos que realmente têm fornecedor vinculado (DISTINCT evita repetição)
$fornecedores = $pdo->query("SELECT id, nome FROM fornecedor ORDER BY nome")->fetchAll();
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
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
  <style>
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
    <a href="../estoque/entrada.php" class="ativo">Estoque</a>
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

    <!-- Abas para escolher o tipo de consulta -->
    <div class="abas">
      <button class="aba-btn ativa" onclick="trocarAba('painel-fornecedor', this)">
        🏭 Por Fornecedor
      </button>
      <button class="aba-btn" onclick="trocarAba('painel-produto', this)">
        📦 Por Produto
      </button>
    </div>

    <!-- ── Painel 1: busca por fornecedor ── -->
    <div id="painel-fornecedor">
      <p style="color:var(--text-muted); font-size:0.875rem; margin-bottom:1rem">
        Selecione um fornecedor para ver todos os produtos que ele fornece
      </p>

      <div class="form-grupo" style="max-width:420px">
        <label class="form-label" for="sel-fornecedor">Fornecedor</label>
        <!-- onchange dispara a busca automaticamente quando o usuário seleciona um fornecedor -->
        <select class="form-control" id="sel-fornecedor">
          <option value="">— Selecione um fornecedor —</option>
          <?php foreach ($fornecedores as $f): ?>
            <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Área onde a tabela de resultados vai aparecer dinamicamente -->
      <div class="area-resultado" id="resultado-fornecedor"></div>
    </div>

    <!-- ── Painel 2: busca por produto ── -->
    <div id="painel-produto" style="display:none">
      <p style="color:var(--text-muted); font-size:0.875rem; margin-bottom:1rem">
        Selecione um produto para ver todos os fornecedores que o fornecem
      </p>

      <div class="form-grupo" style="max-width:420px">
        <label class="form-label" for="sel-produto">Produto</label>
        <!-- Mesmo comportamento: busca ao selecionar -->
        <select class="form-control" id="sel-produto">
          <option value="">— Selecione um produto —</option>
          <?php foreach ($produtos as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Área de resultado para a aba de produto -->
      <div class="area-resultado" id="resultado-produto"></div>
    </div>

  </div><!-- fim do card -->
</div><!-- fim do container -->

<script>
// Troca qual painel está visível e atualiza o estilo do botão ativo
function trocarAba(idPainel, botao) {
    document.getElementById('painel-fornecedor').style.display = 'none';
    document.getElementById('painel-produto').style.display    = 'none';
    document.querySelectorAll('.aba-btn').forEach(b => b.classList.remove('ativa'));
    document.getElementById(idPainel).style.display = 'block';
    botao.classList.add('ativa');
}

// Função assíncrona que faz a busca AJAX e monta a tabela de resultados
async function buscar(acao, id) {
    if (!id) return; // Não faz nada se nenhum item foi selecionado

    // Decide qual div de resultado atualizar conforme a aba ativa
    const divId = acao === 'por_fornecedor' ? 'resultado-fornecedor' : 'resultado-produto';
    const div   = document.getElementById(divId);
    div.innerHTML = '<p style="color:var(--text-muted)">Carregando...</p>'; // Feedback visual

    // Chama esta mesma página passando ?acao=...&id=... para receber o JSON
    const res  = await fetch(`?acao=${acao}&id=${id}`);
    const rows = await res.json(); // Converte a resposta em array JavaScript

    // Se não encontrou nenhum resultado, mostra mensagem amigável
    if (!rows.length) {
        div.innerHTML = '<p style="color:var(--text-muted); padding:1rem 0">Nenhum resultado encontrado.</p>';
        return;
    }

    // Começa a montar a tabela HTML dinamicamente
    let html = '<div class="tabela-wrapper"><table><thead><tr>';

    if (acao === 'por_fornecedor') {
        // Cabeçalhos para consulta por fornecedor
        html += '<th>Produto</th><th>Preço de compra</th><th>Estoque atual</th></tr></thead><tbody>';
        rows.forEach(r => {
            // Monta cada linha com o nome do produto, preço formatado e badge de estoque
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
        // Cabeçalhos para consulta por produto — mostra fornecedores ordenados pelo menor preço
        html += '<th>Fornecedor</th><th>Cidade / Estado</th><th>Preço de compra</th></tr></thead><tbody>';
        rows.forEach((r, i) => {
            // Destaca o primeiro item (mais barato) com fundo verde claro
            const destaque = i === 0 ? 'style="background:#f0fdf4"' : '';
            const cidade   = (r.cidade || '—') + ' / ' + (r.estado || '—');
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
    div.innerHTML = html; // Insere a tabela montada na área de resultado
}
</script>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
  const tsBase = { placeholder: '— Digite para buscar —', allowEmptyOption: true };

  new TomSelect('#sel-fornecedor', {
    ...tsBase,
    onChange: function(value) { buscar('por_fornecedor', value); }
  });

  new TomSelect('#sel-produto', {
    ...tsBase,
    onChange: function(value) { buscar('por_produto', value); }
  });
</script>

</body>
</html>
