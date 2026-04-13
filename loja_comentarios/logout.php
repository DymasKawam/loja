<?php
/*
 * logout.php — Encerra a sessão do usuário com segurança
 *
 * Uso: basta criar um link para este arquivo em qualquer página:
 *   <a href="../logout.php">Sair</a>  ← dentro de subpastas
 *   <a href="logout.php">Sair</a>     ← na raiz do projeto
 */

// session_start() precisa ser chamado antes de qualquer operação com a sessão
session_start();

// $_SESSION = [] apaga todos os dados da sessão de uma vez (nome, papel, id, etc.)
// Isso é mais seguro do que destruir a sessão diretamente sem limpar os dados
$_SESSION = [];

// ini_get() lê a configuração "session.use_cookies" do php.ini
// Se cookies de sessão estiverem ativos, o cookie precisa ser apagado manualmente no navegador
if (ini_get('session.use_cookies')) {
    // session_get_cookie_params() retorna as configurações do cookie de sessão (caminho, domínio, segurança)
    $params = session_get_cookie_params();
    // setcookie() envia um cookie ao navegador — aqui estamos enviando com tempo expirado (time() - 42000)
    // para instruir o navegador a apagar o cookie de sessão imediatamente
    setcookie(
        session_name(),   // session_name() retorna o nome do cookie de sessão (padrão: PHPSESSID)
        '',               // valor vazio — o cookie não terá conteúdo
        time() - 42000,   // data de expiração no passado — força o navegador a deletar o cookie
        $params['path'],   // mesmo caminho do cookie original
        $params['domain'], // mesmo domínio do cookie original
        $params['secure'], // mantém a flag "secure" (só HTTPS) se estava ativa
        $params['httponly'] // mantém a flag "httponly" (sem acesso por JavaScript) se estava ativa
    );
}

// session_destroy() apaga o arquivo de sessão no servidor (os dados do $_SESSION não ficam mais no disco)
// Isso precisa ser feito DEPOIS de limpar $_SESSION e apagar o cookie
session_destroy();

// header() redireciona o navegador para a página de login
header('Location: login.php');
// exit interrompe o script para garantir que nenhum conteúdo seja enviado após o redirecionamento
exit;
?>
