<?php
// session_status() é uma função que retorna o status atual da sessão. PHP_SESSION_NONE indica que nenhuma sessão foi iniciada. Portanto, o código dentro do if será executado apenas se a sessão ainda não tiver sido iniciada, garantindo que session_start() seja chamado apenas uma vez durante a execução do script. Isso evita erros relacionados a sessões, como "session already started".
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // session_start() é usado para iniciar uma nova sessão ou retomar uma sessão existente. Ele deve ser chamado antes de qualquer saída ser enviada ao navegador, o que é garantido pelo check anterior.
}

// function é uma palavra-chave em PHP usada para definir uma função. A função exigir_login() é definida para verificar se um usuário está logado. Se o usuário não estiver logado (ou seja, se a variável de sessão 'usuario_id' estiver vazia), a função redireciona o usuário para a página de login e termina a execução do script com exit. O parâmetro $base_path é usado para construir o caminho correto para a página de login, permitindo que a função seja reutilizada em diferentes partes do projeto, independentemente da estrutura de diretórios.
function exigir_login(string $base_path = '../') {
    if (empty($_SESSION['usuario_id'])) { // empty() é uma função que verifica se uma variável está vazia. Neste caso, ela verifica se a variável de sessão 'usuario_id' está vazia, o que indicaria que o usuário não está logado.
        // $_SERVER['REQUEST_URI'] é uma variável superglobal que contém a URI da página atual. Ao armazenar essa URI na variável de sessão 'redirecionamento', o sistema pode redirecionar o usuário de volta para a página original após um login bem-sucedido, melhorando a experiência do usuário. $_sever é uma variável superglobal em PHP que contém informações sobre cabeçalhos, caminhos e localizações de script. 'REQUEST_URI' é um índice dessa variável que retorna a URI da página atual
        $_SESSION['redirecionamento'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . $base_path . 'login.php'); // header() é uma função do PHP que envia um cabeçalho HTTP ao navegador. Neste caso, ela é usada para redirecionar o usuário para a página de login. O caminho para a página de login é construído usando o parâmetro $base_path, que permite flexibilidade na estrutura de diretórios do projeto.
        exit; // exit é usado para terminar a execução do script imediatamente após o redirecionamento. Isso garante que nenhum código adicional seja executado após o redirecionamento, o que é importante para evitar comportamentos inesperados ou erros.
    }
}

// A função exigir_papel() é usada para verificar se o usuário logado tem um papel específico (por exemplo, 'admin'). Ela chama a função exigir_login() para garantir que o usuário esteja logado antes de verificar o papel. Se o papel do usuário não corresponder ao papel exigido, a função redireciona o usuário para uma página de acesso negado e termina a execução do script. O parâmetro $base_path é usado para construir o caminho correto para a página de acesso negado, permitindo que a função seja reutilizada em diferentes partes do projeto.
function exigir_papel(string $papel, string $base_path = '../') {
    exigir_login($base_path); // exigir_login() é chamada para garantir que o usuário esteja logado antes de verificar seu papel. Isso é importante porque a verificação do papel só faz sentido se o usuário estiver autenticado. Se o usuário não estiver logado, ele será redirecionado para a página de login, e a verificação do papel não ocorrerá.
    if ($_SESSION['usuario_papel'] !== $papel) { // Esta linha verifica se o papel do usuário logado (armazenado na variável de sessão 'usuario_papel') é diferente do papel exigido (passado como parâmetro para a função). Se os papéis não corresponderem, isso significa que o usuário não tem permissão para acessar a funcionalidade ou página protegida, e o código dentro do if será executado.
        header('Location: ' . $base_path . 'acesso_negado.php'); // header é usado para redirecionar o usuário para a página de acesso negado. O caminho para essa página é construído usando o parâmetro $base_path, que permite flexibilidade na estrutura de diretórios do projeto.
        exit;
    }
}

//function é usada para definir uma função em PHP. e_admin() é uma função que verifica se o usuário logado tem o papel de 'admin'. Ela retorna true se o papel do usuário for 'admin' e false caso contrário. A função usa a variável de sessão 'usuario_papel' para verificar o papel do usuário logado. Se a variável de sessão estiver definida e for igual a 'admin', a função retorna true; caso contrário, retorna false.
function e_admin(): bool {
    //return é usada para retornar um valor de uma função. Neste caso, a função e_admin() retorna um valor booleano (true ou false) indicando se o usuário logado tem o papel de 'admin'. A expressão isset($_SESSION['usuario_papel']) verifica se a variável de sessão 'usuario_papel' está definida, e $_SESSION['usuario_papel'] === 'admin' verifica se o valor dessa variável é igual a 'admin'. Se ambas as condições forem verdadeiras, a função retorna true; caso contrário, retorna false.
    return isset($_SESSION['usuario_papel']) && $_SESSION['usuario_papel'] === 'admin';
}

//function nome_usuario() é uma função que retorna o nome do usuário logado. Ela verifica se a variável de sessão 'usuario_nome' está definida e retorna seu valor. Se a variável de sessão não estiver definida (ou seja, se o usuário não estiver logado), a função retorna uma string vazia. O operador de coalescência nula (??) é usado para fornecer um valor padrão (string vazia) caso a variável de sessão 'usuario_nome' não esteja definida.
function nome_usuario(): string {
    return $_SESSION['usuario_nome'] ?? ''; //return é usado para retornar o valor do nome do usuário logado. A expressão $_SESSION['usuario_nome'] ?? '' verifica se a variável de sessão 'usuario_nome' está definida. Se estiver definida, seu valor é retornado; caso contrário, uma string vazia ('') é retornada. Isso garante que a função sempre retorne uma string, mesmo que o usuário não esteja logado ou se a variável de sessão não estiver configurada corretamente.
}

//function é usada para definir uma função em PHP. papel_usuario() é uma função que retorna o papel do usuário logado. Ela verifica se a variável de sessão 'usuario_papel' está definida e retorna seu valor. 
function papel_usuario(): string {
    return $_SESSION['usuario_papel'] ?? ''; //return é usado para retornar o valor do papel do usuário logado. A expressão $_SESSION['usuario_papel'] ?? '' verifica se a variável de sessão 'usuario_papel' está definida. Se estiver definida, seu valor é retornado; caso contrário, uma string vazia ('') é retornada. Isso garante que a função sempre retorne uma string, mesmo que o usuário não esteja logado ou se a variável de sessão não estiver configurada corretamente.
}
?>
