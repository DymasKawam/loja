<?php
/*
 * logout.php — Encerra a sessão do usuário
 *
 * Para fazer logout, basta criar um link para este arquivo:
 *   <a href="../logout.php">Sair</a>  (dentro de subpastas)
 *   <a href="logout.php">Sair</a>     (na raiz)
 */

session_start();

// Apaga todos os dados da sessão
$_SESSION = [];

// Apaga o cookie de sessão do navegador
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Destrói a sessão no servidor
session_destroy();

// Volta para o login
header('Location: login.php');
exit;
?>
