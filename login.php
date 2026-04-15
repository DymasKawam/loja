<?php
//session_start é usado para iniciar uma nova sessão ou retomar uma sessão existente.
session_start();

// !empty() é uma função que verifica se uma variável está definida e não é vazia. $_SESSION['usuario_id'] é a variável de sessão que armazena o ID do usuário logado. Se essa variável estiver definida e não for vazia, significa que o usuário já está logado, e o código dentro do if será executado para redirecionar o usuário para a página index.php, evitando que ele acesse a página de login novamente.
if (!empty($_SESSION['usuario_id'])) {
  //header é uma função que envia um cabeçalho HTTP para o navegador do usuário. Neste caso, ele redireciona o usuário para a página index.php se ele já estiver logado.
    header('Location: index.php');
    exit;
}
//require_once é uma função que inclui e avalia o arquivo especificado durante a execução do script. __DIR__ é uma constante mágica que retorna o diretório do arquivo atual. '/conexao.php' é o caminho relativo para o arquivo de conexão com o banco de dados. Este arquivo deve conter a configuração e a criação da conexão PDO, que será usada para autenticar o usuário durante o processo de login.
require_once __DIR__ . '/conexao.php';
//$erro é uma variável que será usada para armazenar mensagens de erro durante o processo de login. Ela é inicializada como uma string vazia, e se ocorrer algum erro (como campos vazios ou credenciais incorretas), essa variável será preenchida com a mensagem apropriada, que será exibida ao usuário na interface de login.
$erro = '';

// ── $_server é uma variável que contém informações sobre os cabeçalhos, caminhos e localizações de script. 'request_method' é um índice que indica o método HTTP usado para acessar a página (GET, POST, etc.). Neste caso, o código verifica se o método de requisição é POST, o que indica que o formulário de login foi submetido. Se for POST, o código dentro do if será executado para processar as credenciais de login fornecidas pelo usuário.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //trim() é uma função que remove espaços em branco do início e do final de uma string. $_POST é uma variável superglobal que contém os dados enviados pelo formulário via método POST. 'email' e 'senha' são os nomes dos campos do formulário de login. O código usa o operador de coalescência nula (??) para fornecer um valor padrão (uma string vazia) caso os índices 'email' ou 'senha' não estejam definidos no array $_POST, evitando erros de acesso a índices indefinidos.
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // empty() é uma funcao que verifica se uma variável está fazia ou nao esta definida e é usada aqui para validar se os campos de email e senha foram preenchidos. Se algum dos campos estiver vazio, a variável $erro será preenchida com a mensagem 'Preencha o e-mail e a senha.', que será exibida ao usuário na interface de login.
    if (empty($email) || empty($senha)) {
        $erro = 'Preencha o e-mail e a senha.';
    } else {
       //$stmt é uma variável que armazena a declaração preparada para a consulta SQL. $pdo é a instância da conexão PDO criada no arquivo de conexão. A consulta SQL seleciona o id, nome, senha e papel do usuário na tabela 'usuarios' onde o email corresponde ao valor fornecido pelo usuário. O uso de uma declaração preparada ajuda a prevenir ataques de injeção SQL, garantindo que os dados do usuário sejam tratados de forma segura.
        $stmt = $pdo->prepare('SELECT id, nome, senha, papel FROM usuarios WHERE email = ? LIMIT 1');
        // execute() é um método que executa a declaração preparada. Ele recebe um array de valores que serão vinculados aos parâmetros da consulta SQL.
        $stmt->execute([$email]);
        // fetch() é um método que recupera a próxima linha do conjunto de resultados da consulta SQL como um array associativo.
        $usuario = $stmt->fetch();

        /*
         * password_verify() compara a senha digitada com o hash bcrypt
         * armazenado na coluna `senha`. Nunca compare strings diretamente!
         */
        if ($usuario && password_verify($senha, $usuario['senha'])) {

            // session_regenerate_id(true) é uma função que gera um novo ID de sessão para o usuário, invalidando o ID de sessão antigo. Isso é uma medida de segurança importante para prevenir ataques de fixação de sessão, onde um invasor tenta usar um ID de sessão conhecido para se passar por um usuário legítimo.
            session_regenerate_id(true);

            // _SESSION é um array superglobal que armazena informações sobre a sessão do usuário. Aqui, estamos armazenando o ID do usuário, o nome e o papel (admin ou vendedor) na sessão para que possam ser acessados em outras páginas do sistema, permitindo a personalização da experiência do usuário e o controle de acesso com base no papel.
            $_SESSION['usuario_id']    = $usuario['id'];
            $_SESSION['usuario_nome']  = $usuario['nome'];
            $_SESSION['usuario_papel'] = $usuario['papel'];

            // $destino é uma variável que armazena a URL para a qual o usuário será redirecionado após um login bem-sucedido. $_session é um array superglobal que armazena informações sobre a sessão do usuário. 'redirecionamento' é um índice que pode conter a URL para a qual o usuário deve ser redirecionado após o login. O operador de coalescência nula (??) é usado para fornecer um valor padrão ('index.php') caso o índice 'redirecionamento' não esteja definido na sessão, garantindo que o usuário seja redirecionado para a página inicial do sistema se nenhuma URL específica for fornecida.
            $destino = $_SESSION['redirecionamento'] ?? 'index.php';
            // unset() é uma função que destrói a variável especificada. Aqui, estamos removendo a variável de sessão 'redirecionamento' após usá-la para garantir que ela não seja reutilizada em futuras sessões ou redirecionamentos.
            unset($_SESSION['redirecionamento']);
            // header() é uma função que envia um cabeçalho HTTP para o navegador do usuário. Neste caso, ele redireciona o usuário para a URL armazenada na variável $destino após um login bem-sucedido.
            header('Location: ' . $destino);
            // exit() é uma função que encerra a execução do script PHP. Isso é usado aqui para garantir que o redirecionamento seja executado imediatamente.
            exit;

        } else {
            // Mensagem genérica — não revela se o e-mail existe ou não
            $erro = 'E-mail ou senha incorretos.';
        }
    }
}
?>
<!DOCTYPE html> <!-- Declaração do tipo de documento HTML5 -->
<html lang="pt-BR"><!--lang é um atributo que especifica o idioma do conteúdo da página. Neste caso, "pt-BR". -->
<head> <!-- Cabeçalho do documento HTML -->
  <meta charset="UTF-8"> <!-- Define a codificação de caracteres como UTF-8 -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define a viewport para garantir que a página seja responsiva em dispositivos móveis -->
  <title>Login — Sistema Loja</title> <!-- Título da página que aparece na aba do navegador -->
  <link rel="stylesheet" href="estilo.css"> <!-- Link para o arquivo de estilo CSS externo -->
  <style>
    /* ── Layout centralizado para a tela de login ── */
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      background: var(--bg, #f1f5f9);
    }
    .login-wrapper {
      width: 100%;
      max-width: 420px;
      padding: 1rem;
    }
    .login-logo {
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .login-logo h1 {
      font-size: 2rem;
      margin: 0;
    }
    .login-logo p {
      color: var(--text-muted, #64748b);
      margin: 0.25rem 0 0;
    }
    .badge-papel {
      display: inline-block;
      padding: 0.15rem 0.55rem;
      border-radius: 999px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .badge-admin    { background: #dbeafe; color: #1d4ed8; }
    .badge-vendedor { background: #dcfce7; color: #15803d; }
  </style>
</head>
<body>

<div class="login-wrapper"> <!--div é um elemento de contêiner genérico usado para agrupar outros elementos. A classe "login-wrapper" é usada para aplicar estilos específicos a este contêiner, como largura máxima, preenchimento e centralização do conteúdo. -->

  <!-- Logo / cabeçalho -->
  <div class="login-logo">
    <h1>🛒 <span class="destaque">Loja</span> Sistema</h1> <!-- <h1> é usado para criar um título principal na página. O span com a classe "destaque" é usado para aplicar estilos específicos ao texto "Loja". -->
    <p>Faça login para continuar</p> <!-- <p> é usado para criar um parágrafo de texto que serve como uma instrução ou mensagem para o usuário, indicando que ele deve fazer login para acessar o sistema. -->
  </div>

  <!-- Card de login -->
  <div class="card"> <!-- A classe "card" é usada para aplicar estilos específicos a este contêiner, como bordas, sombras e espaçamento, criando um visual de cartão para o formulário de login. -->
    <div class="card-topo"> <!-- A classe "card-topo" é usada para aplicar estilos específicos à seção superior do cartão, como margens, alinhamento e espaçamento. -->
      <h2>Entrar</h2> <!-- <h2> é usado para criar um subtítulo na página. -->
      <p>Informe suas credenciais de acesso</p>  <!-- <p> é usado para criar um parágrafo de texto que serve como uma instrução ou mensagem para o usuário -->
    </div>

    <!-- Mensagem de erro -->
    <?php if ($erro): ?> <!-- Verifica se a variável $erro contém uma mensagem de erro. Se sim, o código dentro deste bloco será executado para exibir a mensagem de erro ao usuário. -->
      <div class="alerta alerta-erro">⚠️ <?= htmlspecialchars($erro) ?></div> <!-- <div> é um elemento de contêiner genérico usado para agrupar outros elementos. A classe "alerta alerta-erro" é usada para aplicar estilos específicos a este contêiner; O conteúdo da mensagem de erro é exibido usando a função htmlspecialchars() para garantir que caracteres especiais sejam tratados de forma segura, prevenindo ataques de injeção de código. -->
    <?php endif; ?> <!-- endif é usado para fechar a estrutura condicional iniciada pelo if. -->

    <!-- Formulário -->
    <form action="login.php" method="POST"> <!-- <form> é um elemento HTML usado para criar um formulário de entrada de dados. O atributo "action" especifica a URL para a qual os dados do formulário serão enviados quando o usuário clicar no botão de envio. O atributo "method" especifica o método HTTP a ser usado ao enviar o formulário, neste caso, POST, que é usado para enviar dados de forma segura. -->

      <div class="form-grupo"> <!-- A classe "form-grupo" é usada para aplicar estilos específicos a este contêiner, como margens, alinhamento e espaçamento. -->
        <label class="form-label" for="email">E-mail</label> <!-- <label> é usado para criar um rótulo para o campo de entrada. O atributo "for" associa o rótulo ao campo de entrada com o id correspondente, melhorando a acessibilidade. -->
          <!-- <input> é um elemento HTML usado para criar um campo de entrada de dados. A classe "form-control" é usada para aplicar estilos específicos a este campo. O atributo "type" especifica o tipo de campo de entrada, neste caso, "email", que valida automaticamente o formato do e-mail. O atributo "id" é usado para associar o campo ao rótulo correspondente. O atributo "name" é usado para identificar o campo quando os dados do formulário são enviados. O atributo "placeholder" fornece um texto de exemplo dentro do campo para orientar o usuário. O atributo "value" pré-preenche o campo com o valor enviado anteriormente, se houver, usando a função htmlspecialchars() para garantir que caracteres especiais sejam tratados de forma segura. Os atributos "required" e "autofocus" garantem que o campo seja preenchido antes do envio e que ele receba foco automaticamente quando a página for carregada. -->
        <input
          class="form-control"
          type="email"
          id="email"
          name="email"
          placeholder="seu@email.com"
          value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
          required
          autofocus>
      </div>

      <div class="form-grupo"> <!-- A classe "form-grupo" é usada para aplicar estilos específicos a este contêiner, como margens, alinhamento e espaçamento. -->
        <label class="form-label" for="senha">Senha</label> <!-- <label> é usado para criar um rótulo para o campo de entrada. O atributo "for" associa o rótulo ao campo de entrada com o id correspondente, melhorando a acessibilidade. -->
          <!-- <input> é um elemento HTML usado para criar um campo de entrada de dados. A classe "form-control" é usada para aplicar estilos específicos a este campo. O atributo "type" especifica o tipo de campo de entrada, neste caso, "password", que oculta o valor digitado. O atributo "id" é usado para associar o campo ao rótulo correspondente. O atributo "name" é usado para identificar o campo quando os dados do formulário são enviados. O atributo "placeholder" fornece um texto de exemplo dentro do campo para orientar o usuário. O atributo "value" pré-preenche o campo com o valor enviado anteriormente, se houver, usando a função htmlspecialchars() para garantir que caracteres especiais sejam tratados de forma segura. O atributo "required" garante que o campo seja preenchido antes do envio. -->
        <input
          class="form-control"
          type="password"
          id="senha"
          name="senha"
          placeholder="••••••••"
          required>
      </div>
      <!-- div é um elemento de contêiner genérico usado para agrupar outros elementos. A classe "form-acoes" é usada para aplicar estilos específicos a este contêiner, como margens, alinhamento e espaçamento. -->
      <div class="form-acoes">
        <!-- <button> é um elemento HTML usado para criar um botão clicável. A classe "btn btn-primario" é usada para aplicar estilos específicos a este botão. O atributo "type" especifica o tipo de botão, neste caso, "submit", que envia os dados do formulário quando clicado. O estilo "width:100%" faz com que o botão ocupe toda a largura disponível dentro do contêiner pai. O conteúdo do botão inclui um emoji de cadeado e o texto "Entrar". -->
        <button type="submit" class="btn btn-primario" style="width:100%">
          🔐 Entrar
        </button> <!-- O botão de envio é usado para enviar os dados do formulário para o servidor para processamento. -->
      </div>

    </form>
  </div>

  <!-- div style é um elemento de contêiner genérico usado para agrupar outros elementos. O atributo "style" é usado para aplicar estilos CSS diretamente a este elemento. Este contêiner é usado para exibir as credenciais de teste para os usuários, indicando quais e-mails e senhas podem ser usados para acessar o sistema com diferentes papéis (admin e vendedor). -->
  <div style="text-align:center; margin-top:1rem; color:var(--text-muted,#64748b); font-size:0.82rem;">
    <strong>Credenciais de teste:</strong><br> <!-- <strong> é usado para destacar o texto "Credenciais de teste:" e <br: é usado para criar uma quebra de linha, separando o título das credenciais listadas abaixo. -->
    <span class="badge-papel badge-admin">admin</span> admin@loja.com / admin123 &nbsp;|&nbsp; <!-- <span> é um elemento de contêiner genérico usado para agrupar outros elementos. A classe "badge-papel badge-admin" é usada para aplicar estilos específicos a este elemento, indicando que se trata de um usuário com papel de administrador. O texto " -->
    <span class="badge-papel badge-vendedor">vendedor</span> vendedor@loja.com / vend123  <!-- <span> é um elemento de contêiner genérico usado para agrupar outros elementos. A classe "badge-papel badge-vendedor" é usada para aplicar estilos específicos a este elemento, indicando que se trata de um usuário com papel de vendedor. O texto " -->
  </div>

</div>

</body>
</html>
