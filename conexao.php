<?php
/*
 * conexao.php — arquivo de conexão com o banco de dados
 *
 * Esse arquivo é incluído no começo de quase todas as páginas do sistema.
 * Ele cria a variável $pdo, que é o objeto de conexão que usamos para
 * fazer todas as consultas e inserções no banco MySQL.
 *
 * Se precisar trocar o banco de dados (por exemplo, para um servidor online),
 * basta alterar as variáveis abaixo — o resto do sistema não precisa mudar.
 */

// Endereço do servidor onde o banco de dados está rodando
// "localhost" significa que o banco está na mesma máquina que o PHP (XAMPP, por exemplo)
$host = "localhost";

// Nome de usuário do banco — no XAMPP o padrão é "root"
$user = "root";

// Senha do banco — no XAMPP normalmente fica em branco
$pass = "";

// Nome do banco de dados que o sistema vai usar
$db = "loja";

try {
    /*
     * PDO (PHP Data Objects) é a forma moderna e segura de conectar ao banco.
     * O DSN (Data Source Name) é uma string que reúne todas as informações
     * de conexão num formato padrão que o PDO entende.
     *
     * mysql: → tipo do banco de dados
     * host  → onde está o banco
     * dbname → qual banco usar
     * charset=utf8mb4 → garante que acentos e emojis sejam salvos corretamente
     */
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);

    // ERRMODE_EXCEPTION faz o PDO lançar um erro quando algo der errado na query,
    // em vez de falhar silenciosamente — isso facilita muito o debug
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // FETCH_ASSOC faz o PDO retornar os resultados como array com nome das colunas
    // em vez de arrays com índices numéricos — muito mais legível no código
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Se a conexão falhar (senha errada, banco não existe etc.), mostra o erro e para tudo
    // Em produção, seria melhor não mostrar o erro diretamente para o usuário
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}
?>
