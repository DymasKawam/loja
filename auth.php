<?php
// Inicia a sessão se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica se o usuário está logado.
 * Redireciona para o login se não estiver.
 *
 * @param string $base_path  Caminho relativo até a raiz do projeto
 *                           (ex: '../' para páginas em subpastas)
 */
function exigir_login(string $base_path = '../') {
    if (empty($_SESSION['usuario_id'])) {
        // Guarda a URL que o usuário tentou acessar para redirecionar depois do login
        $_SESSION['redirecionamento'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . $base_path . 'login.php');
        exit;
    }
}

/**
 * Verifica se o usuário logado tem o papel exigido.
 * Redireciona para página de acesso negado se não tiver.
 *
 * @param string $papel      'admin' ou 'vendedor'
 * @param string $base_path  Caminho relativo até a raiz do projeto
 */
function exigir_papel(string $papel, string $base_path = '../') {
    exigir_login($base_path);
    if ($_SESSION['usuario_papel'] !== $papel) {
        header('Location: ' . $base_path . 'acesso_negado.php');
        exit;
    }
}

/**
 * Retorna verdadeiro se o usuário logado for admin.
 */
function e_admin(): bool {
    return isset($_SESSION['usuario_papel']) && $_SESSION['usuario_papel'] === 'admin';
}

/**
 * Retorna o nome do usuário logado (ou string vazia se não logado).
 */
function nome_usuario(): string {
    return $_SESSION['usuario_nome'] ?? '';
}

/**
 * Retorna o papel do usuário logado (ou string vazia se não logado).
 */
function papel_usuario(): string {
    return $_SESSION['usuario_papel'] ?? '';
}
?>
