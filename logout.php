<?php
// session_start é usado para iniciar uma nova sessão ou retomar uma sessão existente.
session_start();

//$_session é uma variavel que é usada para armazenar informações sobre o usuario logado, como nome e papel.
$_SESSION = [];

// ini_get é uma funçao que retorna o valor de uma diretiva de configuração do PHP. 'session.use_cookies' é a diretiva que indica se o PHP deve usar cookies para armazenar o ID da sessão. Se essa diretiva estiver habilitada, o código dentro do if será executado para limpar o cookie da sessão.
if (ini_get('session.use_cookies')) {
    //$params = session_get_cookie_params() é uma função que retorna um array (é uma estrutura de dados fundamental que armazena uma coleção ordenada de elementos em posições contíguas na memória) com os parâmetros atuais do cookie de sessão, como caminho, domínio, segurança e HTTP-only. Esses parâmetros são usados para garantir que o cookie seja removido corretamente, usando as mesmas configurações que foram usadas para criá-lo.
    $params = session_get_cookie_params();
    //seetcookie é uma função que define um cookie no navegador do usuário.
    setcookie(
        //session_name() é uma função que retorna o nome do cookie de sessão atual. O segundo parâmetro é uma string vazia, o que indica que o valor do cookie deve ser removido. O terceiro parâmetro é o tempo atual menos 42000 segundos, o que efetivamente expira o cookie imediatamente. Os outros parâmetros são os mesmos usados para criar o cookie, garantindo que ele seja removido corretamente.
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

// header é uma função que envia um cabeçalho HTTP para o navegador do usuário. Neste caso, ele redireciona o usuário para a página de login.php após a sessão ser destruída. 
header('Location: login.php');
//exit é usado para garantir que o script seja encerrado imediatamente após o redirecionamento, evitando que qualquer código adicional seja executado.
exit;
?>
