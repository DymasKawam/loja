<?php
/*
 * login.php — Página de autenticação do sistema
 *
 * Fluxo:
 *  1. Usuário preenche e-mail + senha
 *  2. O PHP busca o usuário no banco pelo e-mail
 *  3. password_verify() compara a senha digitada com o hash gravado
 *  4. Se válido, cria a sessão e redireciona para o painel correto
 */

session_start();

// Se já está logado, vai direto para a página inicial
if (!empty($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/conexao.php';

$erro = '';

// ── Processamento do formulário (POST) ───────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Validação básica
    if (empty($email) || empty($senha)) {
        $erro = 'Preencha o e-mail e a senha.';
    } else {
        // Busca o usuário pelo e-mail
        $stmt = $pdo->prepare('SELECT id, nome, senha, papel FROM usuarios WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
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
