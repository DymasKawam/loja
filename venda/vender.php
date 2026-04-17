<?php
//require_once é usado para incluir o arquivo auth.php, que contém funções relacionadas à autenticação do usuário. Isso garante que as funcionalidades de autenticação estejam disponíveis neste script, permitindo verificar se o usuário está logado antes de permitir o acesso à página de venda.
require_once '../auth.php';
exigir_login(); // exigir_login() é uma função definida em auth.php que verifica se o usuário está autenticado. Se o usuário não estiver logado, essa função redireciona para a página de login ou exibe uma mensagem de acesso negado. Isso é importante para proteger a página de venda, garantindo que apenas usuários autorizados possam acessar e realizar vendas.
?>
<!DOCTYPE html> <!-- Declaração do tipo de documento HTML5 para garantir que o navegador interprete a página corretamente como HTML -->
<html lang="pt-BR"> <!-- Define o idioma da página como português do Brasil, o que ajuda os mecanismos de busca e leitores de tela a entenderem o conteúdo da página -->
<head> <!-- A seção head contém metadados e links para recursos externos, como estilos CSS e scripts JavaScript, que são essenciais para a estrutura e o estilo da página de venda. -->
  <meta charset="UTF-8"> <!-- Define a codificação de caracteres como UTF-8, garantindo que caracteres acentuados e símbolos sejam exibidos corretamente na página -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configura a viewport para garantir que a página seja responsiva e se adapte a diferentes tamanhos de tela, especialmente em dispositivos móveis -->
  <title>Realizar Venda</title> <!-- Define o título da página, que é exibido na aba do navegador e ajuda os usuários a identificar o conteúdo da página -->
  <link rel="stylesheet" href="../estilo.css"> <!-- Link para o arquivo de estilo CSS externo que contém as regras de estilo para a página, garantindo uma aparência consistente e agradável -->
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet"> <!-- Link para o arquivo de estilo CSS do Tom Select, uma biblioteca de seleção aprimorada, que é usada para estilizar os campos de seleção (select) na página de venda, proporcionando uma melhor experiência de usuário ao escolher produtos, clientes e vendedores -->
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
    <a href="../estoque/entrada.php">Estoque</a>
    <a href="../venda/vender.php" class="ativo">Vendas</a>
  </div>
</nav>

<?php
include("../conexao.php"); // Conecta ao banco para carregar produtos, clientes e vendedores

//$sqlProdutos é uma variável que armazena a consulta SQL para selecionar os produtos disponíveis para venda. A consulta seleciona o ID, nome, preço e quantidade em estoque de cada produto, utilizando uma junção (JOIN) entre as tabelas "produto" e "estoque" com base no ID do produto. Os resultados são ordenados pelo nome do produto. Em seguida, a consulta é executada usando o método query() do objeto PDO ($pdo), e os resultados são obtidos como um array associativo usando fetchAll(), permitindo que os dados dos produtos sejam usados para preencher o select de produtos na página de venda.
$sqlProdutos = "SELECT produto.id, produto.nome, produto.preco, estoque.quantidade
                FROM produto
                JOIN estoque ON estoque.id_produto = produto.id
                ORDER BY produto.nome";
$produtos = $pdo->query($sqlProdutos)->fetchAll(); //produtos é uma variável que armazena o resultado da consulta SQL para selecionar os produtos disponíveis para venda. A consulta seleciona o ID, nome, preço e quantidade em estoque de cada produto, utilizando uma junção (JOIN) entre as tabelas "produto" e "estoque" com base no ID do produto. Os resultados são ordenados pelo nome do produto. A consulta é executada usando o método query() do objeto PDO ($pdo), e os resultados são obtidos como um array associativo usando fetchAll(), permitindo que os dados dos produtos sejam usados para preencher o select de produtos na página de venda.

// clientes é uma variável que armazena o resultado da consulta SQL para selecionar os clientes cadastrados no banco de dados. A consulta seleciona o ID e o nome de cada cliente da tabela "cliente", ordenando os resultados pelo nome. A consulta é executada usando o método query() do objeto PDO ($pdo), e os resultados são obtidos como um array associativo usando fetchAll(), permitindo que os dados dos clientes sejam usados para preencher o select de clientes na página de venda.
$clientes  = $pdo->query("SELECT id, nome FROM cliente ORDER BY nome")->fetchAll();

// vendedores é uma variável que armazena o resultado da consulta SQL para selecionar os vendedores cadastrados no banco de dados. A consulta seleciona o ID e o nome de cada vendedor da tabela "vendedor", ordenando os resultados pelo nome. A consulta é executada usando o método query() do objeto PDO ($pdo), e os resultados são obtidos como um array associativo usando fetchAll(), permitindo que os dados dos vendedores sejam usados para preencher o select de vendedores na página de venda.
$vendedores = $pdo->query("SELECT id, nome FROM vendedor ORDER BY nome")->fetchAll();
?>

<div class="container">

  <a href="historico.php" class="link-voltar">📜 Ver histórico de vendas</a>

  <div class="card">
    <div class="card-topo">
      <h2>🛍️ Realizar Venda</h2>
      <p>Preencha os dados da venda abaixo</p>
    </div>

    <!-- Formulário de venda — envia para processar.php via POST -->
    <form action="processar.php" method="POST">

      <!-- Select de produto — mostra nome, preço e quantidade disponível -->
      <div class="form-grupo">
        <label class="form-label" for="produto">Produto</label>
        <select class="form-control" id="produto" name="produto" required
                onchange="atualizarEstoque(this)">
          <option value="">— Selecione o produto —</option>
          <?php foreach ($produtos as $p): ?>
            <?php if ($p['quantidade'] > 0): ?>
              <!-- Produto com estoque: mostra o preço e a quantidade disponível -->
              <option value="<?= $p['id'] ?>"
                      data-estoque="<?= $p['quantidade'] ?>"
                      data-preco="<?= $p['preco'] ?>">
                <?= htmlspecialchars($p['nome']) ?> —
                R$ <?= number_format($p['preco'], 2, ',', '.') ?>
                (<?= $p['quantidade'] ?> disponíveis)
              </option>
            <?php else: ?>
              <!-- Produto esgotado: aparece desabilitado no select -->
              <option value="" disabled>
                <?= htmlspecialchars($p['nome']) ?> (Esgotado)
              </option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select>
        <!-- Área que mostra o estoque disponível ao selecionar um produto -->
        <p id="info-estoque" style="font-size:0.825rem; color:var(--text-muted); margin-top:0.375rem"></p>
      </div>

      <!-- Campo de quantidade: min e max são ajustados pelo JavaScript -->
      <div class="form-grupo">
        <label class="form-label" for="quantidade">Quantidade</label>
        <input class="form-control" type="number" id="quantidade" name="quantidade"
          min="1" placeholder="0" required>
      </div>

      <!-- Campos de cliente e vendedor lado a lado -->
      <div class="form-grade-2">

        <div class="form-grupo">
          <label class="form-label" for="cliente">Cliente</label>
          <select class="form-control" id="cliente" name="cliente" required>
            <option value="">— Selecione o cliente —</option>
            <?php foreach ($clientes as $c): ?>
              <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-grupo">
          <label class="form-label" for="vendedor">Vendedor responsável</label>
          <select class="form-control" id="vendedor" name="vendedor" required>
            <option value="">— Selecione o vendedor —</option>
            <?php foreach ($vendedores as $v): ?>
              <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

      </div>

      <div class="form-acoes">
        <button type="submit" class="btn btn-primario">🛍️ Confirmar Venda</button>
        <a href="../index.php" class="btn btn-contorno">Cancelar</a>
      </div>

    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script> <!-- script é um link para a biblioteca Tom Select, que é usada para melhorar a funcionalidade dos campos de seleção (select) na página de venda. Essa biblioteca oferece recursos avançados, como busca, seleção múltipla e melhor usabilidade, tornando mais fácil para os usuários escolherem produtos, clientes e vendedores ao realizar uma venda. -->
<script>
//function atualizarEstoque(select) é uma função JavaScript que é chamada sempre que o usuário seleciona um produto diferente no campo de seleção de produtos. A função recebe o elemento select como parâmetro, obtém a opção selecionada atualmente e extrai os dados de estoque e preço dos atributos data-estoque e data-preco da opção. Em seguida, a função atualiza a informação de estoque disponível exibida abaixo do select e ajusta o atributo max do campo de quantidade para impedir que o usuário peça mais unidades do que estão disponíveis em estoque. Se nenhum produto for selecionado, a função limpa a informação de estoque e remove o limite do campo de quantidade.
function atualizarEstoque(select) {
    const opcao    = select.options[select.selectedIndex]; //const opcao é uma variável que armazena a opção atualmente selecionada no campo de seleção de produtos. select.options é uma coleção de todas as opções disponíveis no select, e select.selectedIndex é o índice da opção atualmente selecionada. Ao acessar select.options[select.selectedIndex], obtemos o elemento option correspondente à escolha do usuário, permitindo acessar seus atributos personalizados (data-estoque e data-preco) para atualizar as informações de estoque e preço na interface do usuário.
    const estoque  = opcao.dataset.estoque;                // const estoque é uma variável que armazena a quantidade de estoque disponível para o produto selecionado, obtida a partir do atributo data-estoque da opção selecionada no campo de seleção de produtos. O atributo data-estoque é um atributo personalizado adicionado à tag option no HTML, que contém a quantidade de unidades disponíveis em estoque para aquele produto específico. Ao acessar opcao.dataset.estoque, obtemos o valor desse atributo, permitindo que a função atualizarEstoque use essa informação para exibir a disponibilidade do produto e ajustar o limite do campo de quantidade.
    const preco    = opcao.dataset.preco;                  // const preco é uma variável que armazena o preço do produto selecionado, obtida a partir do atributo data-preco da opção selecionada no campo de seleção de produtos. O atributo data-preco é um atributo personalizado adicionado à tag option no HTML, que contém o preço unitário do produto específico. Ao acessar opcao.dataset.preco, obtemos o valor desse atributo, permitindo que a função atualizarEstoque use essa informação para exibir o preço do produto ou realizar cálculos relacionados à venda.
    const info     = document.getElementById('info-estoque');   // const info é uma variável que armazena a referência ao elemento HTML com o ID 'info-estoque', que é um parágrafo localizado abaixo do campo de seleção de produtos na página de venda. Esse elemento é usado para exibir informações sobre o estoque disponível do produto selecionado. A função atualizarEstoque atualiza o conteúdo desse elemento para mostrar a quantidade de unidades disponíveis em estoque, proporcionando feedback visual ao usuário sobre a disponibilidade do produto escolhido.
    const campo    = document.getElementById('quantidade');// const campo é uma variável que armazena a referência ao elemento HTML com o ID 'quantidade', que é um campo de entrada do tipo número onde o usuário pode especificar a quantidade do produto que deseja comprar. A função atualizarEstoque ajusta o atributo max desse campo para limitar a quantidade máxima que o usuário pode solicitar com base no estoque disponível do produto selecionado, garantindo que o usuário não possa pedir mais unidades do que estão em estoque.

    if (estoque) { // Verifica se a variável estoque tem um valor válido (ou seja, se um produto foi selecionado e possui um valor de estoque). Se estoque tiver um valor, a função atualiza o conteúdo do elemento info para mostrar a quantidade de unidades disponíveis em estoque usando uma mensagem formatada. Além disso, a função define o atributo max do campo de quantidade para o valor de estoque, impedindo que o usuário solicite mais unidades do que estão disponíveis. Se estoque não tiver um valor (por exemplo, se nenhum produto for selecionado), a função limpa o conteúdo do elemento info e remove o atributo max do campo de quantidade, permitindo que o usuário insira qualquer quantidade sem restrições.
      // Exibe a quantidade disponível e ajusta o limite do campo de quantidade
        info.textContent = `✅ ${estoque} unidade(s) disponível(is) em estoque`;
        campo.max = estoque; // Define o atributo max do campo de quantidade para limitar a compra à quantidade disponível em estoque, garantindo que o usuário não possa solicitar mais unidades do que estão disponíveis. Isso é importante para evitar vendas que não podem ser atendidas devido à falta de estoque, proporcionando uma melhor experiência ao usuário e mantendo a integridade do processo de venda.
    } else { // Limpa a informação de estoque e remove o limite do campo de quantidade se nenhum produto for selecionado
        info.textContent = ''; // Limpa o conteúdo do elemento info, removendo qualquer mensagem sobre a disponibilidade de estoque, caso nenhum produto esteja selecionado ou se o produto selecionado não tiver um valor de estoque válido. Isso garante que a interface do usuário não exiba informações incorretas ou confusas quando não houver um produto selecionado.
        campo.removeAttribute('max'); // Remove o limite se nenhum produto foi selecionado ou se o produto selecionado não tiver um valor de estoque válido, permitindo que o usuário insira qualquer quantidade sem restrições. Isso é importante para garantir que o campo de quantidade funcione corretamente mesmo quando não há um produto selecionado, evitando erros ou limitações desnecessárias na interface do usuário.
    }
}

const tsOpts = { placeholder: '— Digite para buscar —', allowEmptyOption: true }; 
// tsOpts é uma variável que armazena as opções de configuração para a biblioteca Tom Select, que é usada para melhorar a funcionalidade dos campos de seleção (select) na página de venda. O objeto tsOpts contém duas propriedades: placeholder, que define o texto de espaço reservado exibido no campo de seleção quando nenhum item está selecionado, e allowEmptyOption, que permite que uma opção vazia seja selecionada. Essas opções são passadas para a instância do Tom Select ao inicializar os campos de seleção, proporcionando uma melhor experiência de usuário ao escolher produtos, clientes e vendedores.

// A seguir, são criadas instâncias do Tom Select para os campos de seleção de produto, cliente e vendedor, usando as opções definidas em tsOpts. Para o campo de produto, é adicionado um evento onChange que chama a função atualizarEstoque sempre que o usuário seleciona um produto diferente, garantindo que as informações de estoque sejam atualizadas dinamicamente com base na escolha do usuário.
new TomSelect('#produto', { // Inicializa o Tom Select para o campo de seleção de produtos, passando as opções definidas em
  ...tsOpts, // Espalha as opções definidas em tsOpts para configurar o Tom Select, incluindo o placeholder e a permissão para uma opção vazia.
  onChange: function() { // Adiciona um evento onChange que é acionado sempre que o usuário seleciona um produto diferente no campo de seleção de produtos. A função associada a esse evento chama a função atualizarEstoque, passando o elemento select como parâmetro, para garantir que as informações de estoque sejam atualizadas dinamicamente com base na escolha do usuário.
    atualizarEstoque(document.getElementById('produto')); // Chama a função atualizarEstoque, passando o elemento select de produtos como argumento, para atualizar as informações de estoque exibidas na interface do usuário sempre que um produto diferente for selecionado. Isso garante que os usuários tenham feedback visual sobre a disponibilidade do produto escolhido, melhorando a experiência de compra e evitando solicitações de produtos que não estão em estoque.
  }
});

new TomSelect('#cliente',  tsOpts); // Inicializa o Tom Select para o campo de seleção de clientes, usando as opções definidas em tsOpts para configurar o placeholder e permitir uma opção vazia, proporcionando uma melhor experiência de usuário ao escolher um cliente ao realizar uma venda.
new TomSelect('#vendedor', tsOpts); // Inicializa o Tom Select para o campo de seleção de vendedores, usando as opções definidas em tsOpts para configurar o placeholder e permitir uma opção vazia, proporcionando uma melhor experiência de usuário ao escolher um vendedor responsável ao realizar uma venda.
</script>

</body>
</html>
