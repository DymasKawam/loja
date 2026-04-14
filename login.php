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

            // Regenera o ID de sessão para prevenir session fixation
            session_regenerate_id(true);

            // Grava os dados do usuário na sessão
            $_SESSION['usuario_id']    = $usuario['id'];
            $_SESSION['usuario_nome']  = $usuario['nome'];
            $_SESSION['usuario_papel'] = $usuario['papel'];

            // Redireciona para a URL guardada antes do login, ou para o índice
            $destino = $_SESSION['redirecionamento'] ?? 'index.php';
            unset($_SESSION['redirecionamento']);
            header('Location: ' . $destino);
            exit;

        } else {
            // Mensagem genérica — não revela se o e-mail existe ou não
            $erro = 'E-mail ou senha incorretos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Sistema Loja</title>
  <link rel="stylesheet" href="estilo.css">
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

<div class="login-wrapper">

  <!-- Logo / cabeçalho -->
  <div class="login-logo">
    <h1>🛒 <span class="destaque">Loja</span> Sistema</h1>
    <p>Faça login para continuar</p>
  </div>

  <!-- Card de login -->
  <div class="card">
    <div class="card-topo">
      <h2>Entrar</h2>
      <p>Informe suas credenciais de acesso</p>
    </div>

    <!-- Mensagem de erro -->
    <?php if ($erro): ?>
      <div class="alerta alerta-erro">⚠️ <?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <!-- Formulário -->
    <form action="login.php" method="POST">

      <div class="form-grupo">
        <label class="form-label" for="email">E-mail</label>
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

      <div class="form-grupo">
        <label class="form-label" for="senha">Senha</label>
        <input
          class="form-control"
          type="password"
          id="senha"
          name="senha"
          placeholder="••••••••"
          required>
      </div>

      <div class="form-acoes">
        <button type="submit" class="btn btn-primario" style="width:100%">
          🔐 Entrar
        </button>
      </div>

    </form>
  </div>

  <!-- Dica de credenciais (remova em produção!) -->
  <div style="text-align:center; margin-top:1rem; color:var(--text-muted,#64748b); font-size:0.82rem;">
    <strong>Credenciais de teste:</strong><br>
    <span class="badge-papel badge-admin">admin</span> admin@loja.com / admin123 &nbsp;|&nbsp;
    <span class="badge-papel badge-vendedor">vendedor</span> vendedor@loja.com / vend123
  </div>

</div>

</body>
</html>
