<?php
/*
 * auth.php — Funções de autenticação e controle de acesso
 *
 * Inclua este arquivo no topo de qualquer página que precise de login.
 * Fornece três funções principais: exigir_login(), exigir_papel() e e_admin().
 */

// session_status() retorna o estado atual da sessão PHP
// PHP_SESSION_NONE significa que a sessão ainda não foi iniciada nesta requisição
// Essa verificação evita o erro "Cannot start session" caso session_start() já tenha sido chamado
if (session_status() === PHP_SESSION_NONE) {
    // session_start() cria ou retoma a sessão — torna $_SESSION disponível com os dados do usuário
    session_start();
}

/**
 * exigir_login() verifica se o usuário está autenticado.
 * Se não estiver logado, salva a URL atual e redireciona para o login.
 *
 * @param string $base_path  Caminho relativo até a raiz do projeto.
 *                           Use '' na raiz e '../' dentro de subpastas.
 */
function exigir_login(string $base_path = '../') {
    // empty() retorna true quando $_SESSION['usuario_id'] não existe ou está vazio
    if (empty($_SESSION['usuario_id'])) {
        // Guarda a URL que o usuário tentou acessar; após o login ele volta para cá
        // $_SERVER['REQUEST_URI'] contém o caminho completo da URL atual (ex: /loja/venda/vender.php)
        $_SESSION['redirecionamento'] = $_SERVER['REQUEST_URI'];
        // header('Location:') envia um cabeçalho HTTP que instrui o navegador a ir para outra URL
        header('Location: ' . $base_path . 'login.php');
        // exit interrompe a execução do script — sem isso o código abaixo ainda rodaria após o redirect
        exit;
    }
}

/**
 * exigir_papel() verifica se o usuário logado tem o papel (perfil) exigido.
 * Primeiro garante que o usuário está logado, depois confere o perfil.
 *
 * @param string $papel      Perfil exigido: 'admin' ou 'vendedor'
 * @param string $base_path  Caminho relativo até a raiz do projeto
 */
function exigir_papel(string $papel, string $base_path = '../') {
    // Chama exigir_login() primeiro — se não estiver logado, para aqui e redireciona
    exigir_login($base_path);
    // !== é comparação estrita: verifica valor E tipo, sem conversão automática
    if ($_SESSION['usuario_papel'] !== $papel) {
        // Redireciona para a página de acesso negado se o papel não corresponder
        header('Location: ' . $base_path . 'acesso_negado.php');
        exit;
    }
}

/**
 * e_admin() retorna true se o usuário logado for administrador.
 * Útil para exibir/esconder seções da interface condicionalmente.
 *
 * @return bool  true = admin | false = não admin ou não logado
 */
function e_admin(): bool {
    // isset() verifica se a chave existe no array $_SESSION antes de acessar
    // && encadeia as condições: ambas precisam ser verdadeiras para retornar true
    return isset($_SESSION['usuario_papel']) && $_SESSION['usuario_papel'] === 'admin';
}

/**
 * nome_usuario() retorna o nome do usuário logado.
 * Retorna string vazia se não houver sessão ativa.
 *
 * @return string  Nome do usuário ou ''
 */
function nome_usuario(): string {
    // ?? é o operador "null coalescing": retorna o lado esquerdo se existir, ou o direito se não
    return $_SESSION['usuario_nome'] ?? '';
}

/**
 * papel_usuario() retorna o papel (perfil) do usuário logado.
 * Retorna string vazia se não houver sessão ativa.
 *
 * @return string  'admin', 'vendedor' ou ''
 */
function papel_usuario(): string {
    return $_SESSION['usuario_papel'] ?? '';
}
?>
