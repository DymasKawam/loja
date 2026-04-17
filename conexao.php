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

// Lê as configurações do banco de dados a partir do arquivo .env
// O parse_ini_file interpreta o arquivo no formato CHAVE = VALOR
$env = parse_ini_file(__DIR__ . '/.env');

// Endereço do servidor onde o banco de dados está rodando
$host = $env['ENDERECO_DB'];

// Nome de usuário para acessar o banco de dados
$user = trim($env['USUARIO_DB'], "'\"");

// Senha do banco de dados
$pass = trim($env['SENHA_DB'], "'\"");

// $db é o nome do banco de dados que o sistema vai usar
$db = "loja";

try { // O bloco try/catch é usado para tentar estabelecer a conexão e capturar qualquer erro que possa ocorrer durante esse processo. Se a conexão falhar, o código dentro do catch será executado, mostrando uma mensagem de erro.
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
    //$user é o nome do usuário do banco de dados, e $pass é a senha correspondente. Esses valores são passados como argumentos para o construtor do PDO, que tenta estabelecer a conexão com o banco de dados usando as informações fornecidas no DSN.
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);

    //$pdo é o objeto de conexão criado pelo PDO. setAttribute() é um método do PDO que permite configurar opções para a conexão. A opção PDO::ATTR_ERRMODE define o modo de tratamento de erros. Ao usar PDO::ERRMODE_EXCEPTION, o PDO lançará uma exceção (PDOException) sempre que ocorrer um erro na execução de uma consulta ou operação no banco de dados. Isso facilita a identificação e tratamento de erros, permitindo que o código capture a exceção e tome as medidas apropriadas, como exibir uma mensagem de erro amigável ou registrar o erro para análise posterior.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // $pdo é o objeto de conexão criado pelo PDO. setAttribute() é um método do PDO que permite configurar opções para a conexão. A opção PDO::ATTR_DEFAULT_FETCH_MODE define o modo de busca padrão para as consultas. Ao usar PDO::FETCH_ASSOC, as consultas retornarão os resultados como arrays associativos, onde as chaves do array correspondem aos nomes das colunas do banco de dados. Isso torna o código mais legível e fácil de trabalhar, pois você pode acessar os valores usando os nomes das colunas em vez de índices numéricos.
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) { // cath é usado para capturar a exceção lançada pelo PDO em caso de erro de conexão. A variável $e contém informações sobre o erro ocorrido, e getMessage() é um método que retorna uma mensagem descritiva do erro. Nesse caso, se a conexão falhar, a mensagem de erro será exibida para o usuário, indicando que houve um problema ao tentar conectar ao banco de dados.
    die("Erro de conexão com o banco de dados: " . $e->getMessage()); // die é uma função do PHP que encerra a execução do script e exibe uma mensagem. $e é a variável que contém a exceção capturada, e getMessage() é um método que retorna uma mensagem descritiva do erro.
}
?>
