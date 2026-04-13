<?php
/*
 * conexao.php — Arquivo de conexão com o banco de dados MySQL
 *
 * Este arquivo é incluído no início de quase todas as páginas do sistema.
 * Ele cria a variável $pdo que é usada para todas as consultas e inserções.
 * Para mudar para outro servidor basta alterar as variáveis abaixo.
 */

// $host define o endereço do servidor de banco de dados
// "localhost" = banco rodando na mesma máquina que o PHP (padrão no XAMPP)
$host = "localhost";

// $user é o nome de usuário do banco de dados — padrão do XAMPP é "root"
$user = "root";

// $pass é a senha do banco — no XAMPP local costuma ficar em branco
$pass = "";

// $db é o nome do banco de dados que o sistema vai utilizar
$db = "loja";

try {
    /*
     * PDO (PHP Data Objects) é a interface padrão e segura para acesso a bancos de dados no PHP.
     * O primeiro argumento é o DSN (Data Source Name): uma string com todas as configurações.
     *
     * "mysql:"          → tipo de banco (MySQL/MariaDB)
     * "host=$host"      → endereço do servidor
     * "dbname=$db"      → nome do banco de dados
     * "charset=utf8mb4" → garante que acentos, emojis e caracteres especiais sejam salvos corretamente
     *
     * $user e $pass são passados como segundo e terceiro argumentos.
     */
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);

    // PDO::ATTR_ERRMODE define como o PDO lida com erros de SQL
    // PDO::ERRMODE_EXCEPTION lança uma exceção quando uma query falha
    // Sem isso o PDO falha silenciosamente, dificultando muito o debug
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // PDO::ATTR_DEFAULT_FETCH_MODE define o formato padrão dos resultados
    // PDO::FETCH_ASSOC retorna arrays com os nomes das colunas como chaves (ex: $row['nome'])
    // Sem isso os resultados viriam com índices numéricos (ex: $row[0]), menos legível
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // PDOException é lançada quando a conexão falha (senha errada, banco não existe, etc.)
    // die() exibe a mensagem e encerra o script imediatamente
    // Em produção, nunca exiba $e->getMessage() diretamente — ele revela detalhes do servidor
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}
?>
