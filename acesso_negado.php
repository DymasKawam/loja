<?php
//session_start() é uma função do PHP que inicia uma nova sessão ou retoma uma sessão existente. 
session_start();
?>
<!DOCTYPE html> <!-- é a declaração do tipo de documento HTML5, que informa ao navegador que o conteúdo da página está escrito em HTML5. -->
<html lang="pt-BR"> <!-- é a tag de abertura do documento HTML, lang: define o idioma da página como português do Brasil. -->
<head> <!-- é a tag de abertura do cabeçalho do documento HTML. -->
  <meta charset="UTF-8"> <!-- é a tag que define a codificação de caracteres da página como UTF-8 que é uma codificação de caracteres que suporta a maioria dos caracteres usados em diferentes idiomas. -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- é a tag que define as configurações de exibição para dispositivos móveis. width=device-width: define a largura da página para ser igual à largura da tela do dispositivo. initial-scale=1.0: define o nível de zoom inicial da página como 100%. -->
  <title>Acesso Negado — Sistema Loja</title> <!-- é a tag que define o título da página, que é exibido na aba do navegador e em resultados de pesquisa. -->
  <link rel="stylesheet" href="estilo.css"> 
  <style>
    body { display:flex; align-items:center; justify-content:center; min-height:100vh; }
    .neg-box { text-align:center; max-width:400px; padding:2rem; }
    .neg-box .icone { font-size:4rem; margin-bottom:1rem; }
    .neg-box h1 { margin:0 0 .5rem; }
    .neg-box p  { color:var(--text-muted,#64748b); margin-bottom:1.5rem; }
  </style>
</head> <!-- é a tag de fechamento do cabeçalho do documento HTML. -->
<body> <!-- é a tag de abertura do corpo do documento HTML, onde o conteúdo visível da página é colocado. -->
<div class="neg-box"> <!-- div é um elemento de contêiner genérico usado para agrupar outros elementos. A classe "neg-box" é usada para aplicar estilos específicos a este contêiner -->
  <div class="icone">🚫</div>
  <h1>Acesso Negado</h1>
  <p>Você não tem permissão para acessar esta página.<br>
     Entre em contato com o administrador do sistema.</p>
  <a href="index.php" class="btn btn-primario">← Voltar ao painel</a>
</div>
</body>
</html>
