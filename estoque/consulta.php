<?php
require_once '../auth.php';
exigir_papel('admin');
?>
<?php
include("../conexao.php"); // Conecta ao banco de dados 

// if verifica se a variável $_GET['acao'] está definida, o que indica que uma ação de consulta foi solicitada. Se estiver definida, o script processa a consulta e retorna os resultados em formato JSON, sem renderizar o HTML da página. Isso é útil para permitir que a mesma página sirva tanto para exibir a interface de consulta quanto para responder às requisições AJAX feitas pelo JavaScript quando o usuário seleciona um fornecedor ou produto. Se $_GET['acao'] não estiver definida, o script continua normalmente e renderiza a página HTML com os formulários de seleção e as áreas de resultado.
if (isset($_GET['acao'])) {
    header('Content-Type: application/json'); // Informa que a resposta é JSON, não HTML

    // $_GET é  um array superglobal em PHP que contém os parâmetros passados na URL. Neste caso, o script espera um parâmetro 'acao' que indica o tipo de consulta a ser realizada (por fornecedor ou por produto) e um parâmetro 'id' que especifica o ID do fornecedor ou produto a ser consultado. O script verifica o valor de 'acao' para determinar qual consulta SQL executar e usa o valor de 'id' para filtrar os resultados da consulta, garantindo que apenas os dados relevantes sejam retornados em formato JSON para o JavaScript processar e exibir na página.
    if ($_GET['acao'] === 'por_fornecedor' && !empty($_GET['id'])) {
        $id = (int) $_GET['id']; // Converte para inteiro para evitar SQL injection e garantir que o valor seja do tipo correto para a consulta SQL


        // Consulta 1: lista todos os produtos fornecidos por um fornecedor específico, incluindo o preço de compra e a quantidade em estoque. A consulta SQL utiliza JOINs para combinar as tabelas fornecedor_produto, produto e estoque, permitindo obter o nome do produto, o preço de compra e a quantidade disponível em estoque para cada produto fornecido pelo fornecedor selecionado. O resultado é ordenado alfabeticamente pelo nome do produto para facilitar a leitura. O uso de COALESCE na quantidade do estoque garante que, mesmo que um produto não tenha um registro correspondente na tabela de estoque (ou seja, nunca teve entrada registrada), ele ainda apareça na lista com uma quantidade de 0, evitando que produtos fornecidos mas sem estoque sejam omitidos da consulta.
        $stmt = $pdo->prepare("
            SELECT p.nome, fp.preco_compra, COALESCE(e.quantidade, 0) AS estoque
            FROM fornecedor_produto fp
            JOIN produto p ON p.id = fp.id_produto       -- junta com a tabela de produtos pelo ID
            LEFT JOIN estoque e ON e.id_produto = p.id   -- LEFT JOIN inclui produtos mesmo sem estoque
            WHERE fp.id_fornecedor = :id
            ORDER BY p.nome                              -- ordena em ordem alfabética
        ");

        // Executa a consulta passando o ID do fornecedor como parâmetro e retorna os resultados em formato JSON para o JavaScript processar e exibir na página. O método fetchAll() é usado para obter todas as linhas retornadas pela consulta como um array associativo, que é então codificado em JSON usando json_encode() e enviado como resposta à requisição AJAX feita pelo JavaScript. O exit é usado para garantir que o script pare de executar após enviar a resposta JSON, evitando que o HTML da página seja renderizado desnecessariamente quando a consulta é feita via AJAX.
        $stmt->execute([':id' => $id]);
        echo json_encode($stmt->fetchAll()); // Retorna os resultados da consulta em formato JSON para o JavaScript processar e exibir na página

        exit; // Para aqui — não renderiza o HTML abaixo
    }

    // Consulta 2: lista todos os fornecedores que fornecem um produto específico, incluindo o preço de compra e a localização do fornecedor. A consulta SQL utiliza JOINs para combinar as tabelas fornecedor_produto, fornecedor e endereco, permitindo obter o nome do fornecedor, o preço de compra e a cidade/estado do fornecedor para cada fornecedor que fornece o produto selecionado. O resultado é ordenado pelo preço de compra em ordem crescente, destacando o fornecedor mais barato no topo da lista. O uso de LEFT JOIN com a tabela de endereço garante que, mesmo que um fornecedor não tenha um endereço registrado, ele ainda apareça na lista com campos de cidade e estado vazios (ou seja, '—'), evitando que fornecedores sem endereço sejam omitidos da consulta.
    if ($_GET['acao'] === 'por_produto' && !empty($_GET['id'])) {
        $id = (int) $_GET['id']; // Converte para inteiro para evitar SQL injection e garantir que o valor seja do tipo correto para a consulta SQL

        $stmt = $pdo->prepare("
            SELECT f.nome, f.cnpj, fp.preco_compra, e.cidade, e.estado
            FROM fornecedor_produto fp
            JOIN fornecedor f ON f.id = fp.id_fornecedor         -- junta com a tabela de fornecedores
            LEFT JOIN endereco e ON e.id = f.endereco_forncedor  -- pega o endereço do fornecedor
            WHERE fp.id_produto = :id
            ORDER BY fp.preco_compra ASC                         -- do mais barato para o mais caro
        ");
        // Executa a consulta passando o ID do produto como parâmetro e retorna os resultados em formato JSON para o JavaScript processar e exibir na página. O método fetchAll() é usado para obter todas as linhas retornadas pela consulta como um array associativo, que é então codificado em JSON usando json_encode() e enviado como resposta à requisição AJAX feita pelo JavaScript. O exit é usado para garantir que o script pare de executar após enviar a resposta JSON, evitando que o HTML da página seja renderizado desnecessariamente quando a consulta é feita via AJAX.
        $stmt->execute([':id' => $id]);
        echo json_encode($stmt->fetchAll()); // Retorna os resultados da consulta em formato JSON para o JavaScript processar e exibir na página
        exit;
    }
    // Se a ação não foi reconhecida ou o ID não foi fornecido, retorna um array vazio em formato JSON para o JavaScript processar, indicando que não há resultados para exibir. O exit é usado para garantir que o script pare de executar após enviar a resposta JSON, evitando que o HTML da página seja renderizado desnecessariamente quando uma ação inválida é solicitada via AJAX.
    echo json_encode([]); 
}

//$fornecedores é uma variável que armazena o resultado da consulta SQL que seleciona o ID e o nome de todos os fornecedores cadastrados no banco de dados, ordenados alfabeticamente pelo nome. A consulta é executada usando o método query do objeto PDO, que retorna um objeto de resultado, e o método fetchAll() é usado para obter todas as linhas retornadas pela consulta como um array associativo. Esse array é então utilizado para preencher a lista de opções no formulário de seleção de fornecedores na interface do usuário, permitindo que o usuário escolha um fornecedor para consultar os produtos que ele fornece.
$fornecedores = $pdo->query("SELECT id, nome FROM fornecedor ORDER BY nome")->fetchAll();
// $produtos é uma variável que armazena o resultado da consulta SQL que seleciona o ID e o nome de todos os produtos que estão associados a pelo menos um fornecedor, ordenados alfabeticamente pelo nome. A consulta utiliza um JOIN entre as tabelas produto e fornecedor_produto para garantir que apenas os produtos que têm um fornecedor registrado sejam incluídos na lista. O método query do objeto PDO é usado para executar a consulta, e o método fetchAll() é usado para obter todas as linhas retornadas pela consulta como um array associativo. Esse array é então utilizado para preencher a lista de opções no formulário de seleção de produtos na interface do usuário, permitindo que o usuário escolha um produto para consultar os fornecedores que o fornecem.
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

      <div class="form-grupo" style="max-width:420px"> <!-- form-grupo é só para dar um espacinho entre o label e a select, e limitar a largura da select para não ficar gigante -->
        <label class="form-label" for="sel-fornecedor">Fornecedor</label> <!-- label para o select de fornecedores, com for="sel-fornecedor" para associar o rótulo ao campo de seleção. Isso melhora a acessibilidade, permitindo que os leitores de tela e outros dispositivos assistivos identifiquem corretamente o propósito do campo de seleção. O texto "Fornecedor" indica ao usuário que ele deve escolher um fornecedor específico para consultar os produtos que esse fornecedor fornece. O label é estilizado com a classe form-label para manter a consistência visual com outros formulários da aplicação. -->
        <!-- onchange dispara a busca automaticamente quando o usuário seleciona um fornecedor -->
        <select class="form-control" id="sel-fornecedor"> <!-- campo de seleção para escolher um fornecedor, com id="sel-fornecedor" para ser facilmente referenciado pelo JavaScript. A classe form-control é usada para aplicar estilos consistentes aos campos de formulário. O atributo onchange é configurado para chamar a função JavaScript buscar() sempre que o usuário fizer uma seleção diferente, passando os parâmetros 'por_fornecedor' e o valor selecionado (this.value) para realizar a consulta AJAX e atualizar a tabela de resultados dinamicamente sem recarregar a página. -->
          <option value="">— Selecione um fornecedor —</option> <!-- opção padrão vazia para incentivar o usuário a fazer uma seleção. O texto "— Selecione um fornecedor —" serve como um prompt visual, indicando que o usuário deve escolher um fornecedor específico para iniciar a consulta. Essa opção não tem valor (value="") para garantir que, se o usuário tentar realizar a consulta sem selecionar um fornecedor, a função JavaScript pode detectar isso e evitar fazer uma requisição AJAX desnecessária ou mostrar uma mensagem de erro apropriada. -->
          <?php foreach ($fornecedores as $f): ?> 
            <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome']) ?></option> <!-- Loop PHP que percorre o array de fornecedores e gera uma opção para cada um no campo de seleção. O valor da opção é definido como o ID do fornecedor (value="<?= $f['id'] ?>"), enquanto o texto exibido para o usuário é o nome do fornecedor (<?= htmlspecialchars($f['nome']) ?>). A função htmlspecialchars() é usada para escapar caracteres especiais no nome do fornecedor, garantindo que ele seja exibido corretamente na página e prevenindo possíveis vulnerabilidades de segurança, como injeção de HTML ou JavaScript. -->
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Área de resultado para a aba de fornecedor, onde a tabela de produtos fornecidos por um fornecedor específico será exibida dinamicamente após a consulta AJAX ser realizada. O JavaScript irá atualizar o conteúdo desta div com a tabela de resultados formatada em HTML, mostrando o nome do produto, o preço de compra e a quantidade em estoque para cada produto fornecido pelo fornecedor selecionado. A classe area-resultado é usada para aplicar estilos específicos a esta seção, como margens, padding e formatação da tabela, garantindo que os resultados sejam apresentados de forma clara e organizada para o usuário. O id="resultado-fornecedor" permite que o JavaScript identifique facilmente esta div para inserir os resultados da consulta quando um fornecedor for selecionado. -->
      <div class="area-resultado" id="resultado-fornecedor"></div>
    </div>

    <!--  ── Painel 2: busca por produto ── -->
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

      <!--class é a mesma da outra aba para manter o estilo consistente, só muda o id para resultado-produto para o JavaScript saber onde colocar os resultados da consulta por produto. Aqui será exibida uma tabela de fornecedores que fornecem o produto selecionado, mostrando o nome do fornecedor, a cidade/estado e o preço de compra, com destaque para o fornecedor mais barato. O JavaScript irá atualizar o conteúdo desta div dinamicamente após a consulta AJAX ser realizada quando um produto for selecionado. -->
      <div class="area-resultado" id="resultado-produto"></div>
    </div>

  </div><!-- fim do card -->
</div><!-- fim do container -->

<script>
// trocarAba é uma função JavaScript que controla a exibição dos painéis de consulta por fornecedor e por produto. Ela recebe dois parâmetros: idPainel, que é o ID do painel a ser exibido, e botao, que é o elemento do botão que foi clicado para ativar a aba. A função começa ocultando ambos os painéis usando style.display = 'none', garantindo que apenas um painel seja visível por vez. Em seguida, ela remove a classe 'ativa' de todos os botões de aba para desmarcar visualmente a aba anteriormente ativa. Depois disso, a função exibe o painel correspondente ao ID fornecido (idPainel) definindo style.display = 'block' e adiciona a classe 'ativa' ao botão clicado para destacar visualmente a aba ativa. Essa função é chamada pelos eventos onclick dos botões de aba na interface do usuário, permitindo que o usuário alterne entre as consultas por fornecedor e por produto de forma intuitiva e responsiva.
function trocarAba(idPainel, botao) {
    document.getElementById('painel-fornecedor').style.display = 'none';
    document.getElementById('painel-produto').style.display    = 'none';
    document.querySelectorAll('.aba-btn').forEach(b => b.classList.remove('ativa'));
    document.getElementById(idPainel).style.display = 'block';
    botao.classList.add('ativa');
}

// buscar é uma função JavaScript assíncrona que realiza uma consulta AJAX para buscar informações de estoque com base na ação (por fornecedor ou por produto) e no ID selecionado. A função começa verificando se um ID foi fornecido; se não, ela retorna imediatamente sem fazer nada. Em seguida, ela determina qual div de resultado deve ser atualizada com base na ação (por fornecedor ou por produto) e exibe uma mensagem de "Carregando..." para fornecer feedback visual ao usuário enquanto a consulta está sendo processada. A função então faz uma requisição fetch para a mesma página, passando os parâmetros de ação e ID na URL para solicitar os dados em formato JSON. Após receber a resposta, a função converte o JSON em um array JavaScript e verifica se há resultados. Se não houver resultados, ela exibe uma mensagem amigável indicando que nenhum resultado foi encontrado. Caso contrário, a função monta dinamicamente uma tabela HTML com os resultados da consulta, formatando os dados de acordo com o tipo de consulta (fornecedor ou produto) e inserindo a tabela na div de resultado correspondente para exibição ao usuário.
async function buscar(acao, id) {
    if (!id) return; // Não faz nada se nenhum item foi selecionado

    // Determina qual div de resultado atualizar com base na ação e mostra mensagem de carregamento enquanto espera a resposta
    const divId = acao === 'por_fornecedor' ? 'resultado-fornecedor' : 'resultado-produto';
    const div   = document.getElementById(divId);
    div.innerHTML = '<p style="color:var(--text-muted)">Carregando...</p>'; // Feedback visual para o usuário enquanto a consulta AJAX está sendo processada

    // Faz a requisição AJAX para a mesma página, passando os parâmetros de ação e ID na URL para solicitar os dados em formato JSON. O método fetch é usado para fazer a requisição, e a resposta é convertida para JSON usando res.json(), que retorna uma promessa que resolve para um array JavaScript contendo os resultados da consulta. O uso de async/await torna o código mais legível e fácil de entender, permitindo que a função espere pela resposta da requisição antes de continuar a execução.
    const res  = await fetch(`?acao=${acao}&id=${id}`);
    const rows = await res.json(); // Converte a resposta JSON em um array JavaScript para processar os resultados da consulta

    // Verifica se há resultados; se não houver, exibe uma mensagem amigável indicando que nenhum resultado foi encontrado. O uso de rows.length permite verificar se o array de resultados está vazio, e a mensagem "Nenhum resultado encontrado." é exibida para informar ao usuário que a consulta não retornou dados correspondentes ao fornecedor ou produto selecionado. A mensagem é estilizada com cor e padding para garantir que seja claramente visível e tenha um espaçamento adequado dentro da área de resultado.
    if (!rows.length) {
        div.innerHTML = '<p style="color:var(--text-muted); padding:1rem 0">Nenhum resultado encontrado.</p>';
        return;
    }

    // Monta dinamicamente uma tabela HTML com os resultados da consulta, formatando os dados de acordo com o tipo de consulta (fornecedor ou produto) e inserindo a tabela na div de resultado correspondente para exibição ao usuário. A estrutura da tabela é construída usando template literals para facilitar a inserção de variáveis e a formatação do HTML. Para a consulta por fornecedor, a tabela inclui colunas para o nome do produto, preço de compra formatado em reais e um badge indicando a quantidade em estoque ou se o produto está esgotado. Para a consulta por produto, a tabela inclui colunas para o nome do fornecedor (com destaque para o mais barato), cidade/estado e preço de compra formatado em reais. O uso de classes CSS como badge, badge-verde e badge-vermelho permite estilizar visualmente os elementos da tabela para melhorar a legibilidade e destacar informações importantes, como o estoque disponível ou o fornecedor mais barato.
    let html = '<div class="tabela-wrapper"><table><thead><tr>';

    if (acao === 'por_fornecedor') { // if é usado para determinar a estrutura da tabela com base no tipo de consulta realizada (por fornecedor ou por produto). Dependendo do valor da variável acao, a função monta os cabeçalhos e as linhas da tabela de forma diferente para exibir as informações relevantes para cada tipo de consulta. Para a consulta por fornecedor, a tabela é estruturada para mostrar os produtos fornecidos por um fornecedor específico, enquanto para a consulta por produto, a tabela é estruturada para mostrar os fornecedores que fornecem um produto específico, destacando o fornecedor mais barato. Essa abordagem permite que a mesma função seja reutilizada para gerar diferentes formatos de tabela com base na ação selecionada pelo usuário. 

        // Cabeçalhos para consulta por fornecedor — mostra produtos ordenados alfabeticamente
        html += '<th>Produto</th><th>Preço de compra</th><th>Estoque atual</th></tr></thead><tbody>';
        rows.forEach(r => {
            // Cria um badge para indicar a quantidade em estoque, usando verde para indicar que há estoque disponível e vermelho para indicar que o produto está esgotado. O badge é criado usando uma estrutura condicional que verifica se a quantidade em estoque (r.estoque) é maior que zero. Se for, o badge exibe a quantidade disponível com um estilo verde; caso contrário, o badge exibe "Esgotado" com um estilo vermelho. Essa abordagem visual ajuda os usuários a identificar rapidamente quais produtos estão disponíveis e quais estão esgotados na lista de produtos fornecidos por um fornecedor específico.
            const badge = r.estoque > 0
                ? `<span class="badge badge-verde">${r.estoque} un.</span>`
                : `<span class="badge badge-vermelho">Esgotado</span>`;
            html += `<tr>
                <td><strong>${r.nome}</strong></td>
                <td>R$ ${parseFloat(r.preco_compra).toFixed(2).replace('.', ',')}</td>
                <td>${badge}</td>
            </tr>`;
        });
    } else { // Consulta por produto 
        // Cabeçalhos para consulta por produto — mostra fornecedores ordenados do mais barato para o mais caro, com destaque para o mais barato
        html += '<th>Fornecedor</th><th>Cidade / Estado</th><th>Preço de compra</th></tr></thead><tbody>';
        rows.forEach((r, i) => {
            // Destaque para o fornecedor mais barato, que é o primeiro da lista ordenada por preço. O destaque é aplicado usando um estilo de fundo diferente (background:#f0fdf4) para a linha correspondente ao fornecedor mais barato (i === 0). Isso ajuda os usuários a identificar rapidamente qual fornecedor oferece o melhor preço para o produto selecionado. Além disso, um badge verde é adicionado ao lado do nome do fornecedor mais barato para reforçar visualmente essa informação, tornando-a ainda mais evidente na tabela de resultados da consulta por produto.
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
    div.innerHTML = html; // Insere a tabela montada dinamicamente na div de resultado correspondente para exibição ao usuário
}
</script> <!-- Script para controlar as abas e realizar as consultas AJAX, além de formatar os resultados em tabelas HTML dinamicamente com base na ação selecionada pelo usuário. -->

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script> <!-- Script para incluir a biblioteca Tom Select, que é usada para melhorar a experiência do usuário nos campos de seleção (select) na interface de consulta. O Tom Select oferece recursos avançados como busca, seleção múltipla e melhor estilização para os campos de seleção, tornando-os mais intuitivos e fáceis de usar. A versão completa (tom-select.complete.min.js) inclui todos os recursos da biblioteca, garantindo que as funcionalidades de busca e seleção sejam aplicadas corretamente aos campos de seleção de fornecedores e produtos na página de consulta. -->
<script>
  const tsBase = { placeholder: '— Digite para buscar —', allowEmptyOption: true }; // Configurações base para os campos de seleção do Tom Select, definindo um placeholder para orientar o usuário a digitar para buscar opções e permitindo uma opção vazia para que o usuário possa limpar a seleção se desejar. Essas configurações são aplicadas a ambos os campos de seleção (fornecedor e produto) para garantir uma experiência de usuário consistente e intuitiva ao usar os recursos avançados do Tom Select.

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
