<?php
/*
 * login.php — Página de autenticação do sistema
 *
 * Fluxo:
 *  1. Usuário acessa a página (GET) — exibe o formulário
 *  2. Usuário preenche e-mail + senha e envia (POST)
 *  3. PHP busca o usuário no banco pelo e-mail
 *  4. password_verify() compara a senha com o hash armazenado
 *  5. Se válido: cria sessão e redireciona para o painel
 */

// session_start() inicia a sessão PHP, tornando $_SESSION disponível
session_start();

// Se o usuário já está logado (sessão ativa), não precisa ver o login — vai direto ao painel
// empty() retorna false quando a variável existe e tem um valor não-vazio
if (!empty($_SESSION['usuario_id'])) {
    // header() envia o cabeçalho HTTP de redirecionamento para o navegador
    header('Location: index.php');
    // exit interrompe o script para que o HTML abaixo não seja enviado
    exit;
}

// require_once carrega conexao.php apenas uma vez — cria o objeto $pdo necessário para a query de login
require_once __DIR__ . '/conexao.php';

// $erro guarda a mensagem de erro para exibir no formulário; começa vazia
$erro = '';

// $_SERVER['REQUEST_METHOD'] contém o método HTTP da requisição atual
// 'POST' significa que o formulário foi enviado; 'GET' significa que é apenas uma visita à página
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // trim() remove espaços em branco no início e fim — evita " usuario@email.com " passar na validação
    // ?? '' retorna string vazia se $_POST['email'] não existir (evita aviso do PHP)
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Validação básica: ambos os campos são obrigatórios antes de consultar o banco
    if (empty($email) || empty($senha)) {
        $erro = 'Preencha o e-mail e a senha.';
    } else {
        // prepare() cria uma consulta parametrizada — o ? é substituído pelo valor de forma segura
        // LIMIT 1 garante que apenas um registro seja retornado (e-mails devem ser únicos)
        $stmt = $pdo->prepare('SELECT id, nome, senha, papel FROM usuarios WHERE email = ? LIMIT 1');
        // execute() passa o e-mail como parâmetro e executa a query
        $stmt->execute([$email]);
        // fetch() retorna o primeiro (e único) resultado como array associativo
        $usuario = $stmt->fetch();

        // password_verify() compara a senha digitada com o hash bcrypt armazenado no banco
        // Nunca compare senhas como strings diretas! O hash é diferente a cada vez que é gerado
        if ($usuario && password_verify($senha, $usuario['senha'])) {

            // session_regenerate_id(true) cria um novo ID de sessão e apaga o anterior
            // Isso previne ataques de "session fixation" (roubo de ID de sessão)
            session_regenerate_id(true);

            // Grava os dados do usuário na sessão para uso em todas as páginas
            $_SESSION['usuario_id']    = $usuario['id'];    // ID único do usuário
            $_SESSION['usuario_nome']  = $usuario['nome'];  // Nome para exibir na navbar
            $_SESSION['usuario_papel'] = $usuario['papel']; // 'admin' ou 'vendedor'

            // Se o usuário tentou acessar uma página antes do login, volta para ela
            // ?? 'index.php' usa o painel como destino padrão quando não há redirecionamento salvo
            $destino = $_SESSION['redirecionamento'] ?? 'index.php';
            // unset() remove a chave da sessão para não redirecionar nas próximas visitas
            unset($_SESSION['redirecionamento']);
            header('Location: ' . $destino);
            exit;

        } else {
            // Mensagem genérica proposital — não revela se o e-mail existe ou não no banco
            // Isso dificulta ataques de enumeração de usuários
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
    /* Centraliza o formulário de login vertical e horizontalmente na tela */
    body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:var(--bg,#f1f5f9); }
    /* max-width limita a largura do card de login em telas grandes */
    .login-wrapper { width:100%; max-width:420px; padding:1rem; }
    /* text-align:center centraliza o logotipo e o subtítulo */
    .login-logo { text-align:center; margin-bottom:1.5rem; }
    .login-logo h1 { font-size:2rem; margin:0; }
    /* var(--text-muted) usa a variável CSS de cor cinza definida em estilo.css */
    .login-logo p  { color:var(--text-muted,#64748b); margin:.25rem 0 0; }
    /* Estilos dos badges de papel (admin / vendedor) nas credenciais de teste */
    .badge-papel    { display:inline-block; padding:.15rem .55rem; border-radius:999px; font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; }
    .badge-admin    { background:#dbeafe; color:#1d4ed8; }
    .badge-vendedor { background:#dcfce7; color:#15803d; }
  </style>
</head>
<body>

<!-- <div class="login-wrapper"> centra e limita a largura de todo o conteúdo do login -->
<div class="login-wrapper">

  <!-- Logotipo e subtítulo da aplicação -->
  <div class="login-logo">
    <!-- <h1> é o nível mais alto de título; <span class="destaque"> aplica a cor de destaque -->
    <h1>🛒 <span class="destaque">Loja</span> Sistema</h1>
    <p>Faça login para continuar</p>
  </div>

  <!-- <div class="card"> é o painel branco com sombra definido em estilo.css -->
  <div class="card">
    <div class="card-topo"> <!-- Cabeçalho do card com título e descrição -->
      <h2>Entrar</h2>
      <p>Informe suas credenciais de acesso</p>
    </div>

    <!-- Exibe o alerta de erro somente quando $erro não for uma string vazia -->
    <?php if ($erro): ?>
      <!-- htmlspecialchars() converte caracteres especiais para evitar XSS na mensagem de erro -->
      <div class="alerta alerta-erro">⚠️ <?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <!-- action="login.php" envia o formulário para este mesmo arquivo processar
         method="POST" envia os dados no corpo da requisição (não na URL) -->
    <form action="login.php" method="POST">

      <!-- <div class="form-grupo"> agrupa label + input com o espaçamento correto -->
      <div class="form-grupo">
        <!-- for="email" associa o label ao input pelo id — clicar no label foca o campo -->
        <label class="form-label" for="email">E-mail</label>
        <!-- type="email" valida o formato de e-mail no navegador antes de enviar
             autofocus coloca o cursor automaticamente neste campo ao carregar a página
             value="..." mantém o e-mail preenchido ao recarregar após erro -->
        <input class="form-control" type="email" id="email" name="email"
          placeholder="seu@email.com"
          value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
          required autofocus>
      </div>

      <div class="form-grupo">
        <label class="form-label" for="senha">Senha</label>
        <!-- type="password" mascara os caracteres digitados com pontos -->
        <input class="form-control" type="password" id="senha" name="senha"
          placeholder="••••••••" required>
      </div>

      <div class="form-acoes"> <!-- Área dos botões de ação do formulário -->
        <!-- type="submit" envia o formulário ao clicar; style="width:100%" ocupa toda a largura -->
        <button type="submit" class="btn btn-primario" style="width:100%">🔐 Entrar</button>
      </div>

    </form>
  </div>

  <!-- Dica de credenciais de teste — REMOVA em produção! -->
  <div style="text-align:center; margin-top:1rem; color:var(--text-muted,#64748b); font-size:0.82rem;">
    <strong>Credenciais de teste:</strong><br>
    <span class="badge-papel badge-admin">admin</span> admin@loja.com / admin123 &nbsp;|&nbsp;
    <span class="badge-papel badge-vendedor">vendedor</span> vendedor@loja.com / vend123
  </div>

</div>
</body>
</html>
