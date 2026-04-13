<?php
/*
 * acesso_negado.php — Exibido quando o usuário tenta acessar uma página sem ter permissão
 */
// session_start() inicia (ou retoma) a sessão PHP para que $_SESSION fique disponível
session_start();
?>
<!DOCTYPE html> <!-- Declara que o documento usa a versão HTML5 -->
<html lang="pt-BR"> <!-- Define o idioma da página como português do Brasil -->
<head>
  <meta charset="UTF-8"> <!-- Codificação de caracteres: suporta acentos, cedilha, etc. -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Torna a página responsiva em celulares -->
  <title>Acesso Negado — Sistema Loja</title> <!-- Texto que aparece na aba do navegador -->
  <link rel="stylesheet" href="estilo.css"> <!-- Importa o arquivo CSS do projeto (mesmo diretório) -->
  <style>
    /* display:flex com align/justify:center centraliza o conteúdo vertical e horizontalmente */
    body { display:flex; align-items:center; justify-content:center; min-height:100vh; }
    /* max-width limita a largura do card; text-align:center centraliza o texto */
    .neg-box { text-align:center; max-width:400px; padding:2rem; }
    /* font-size:4rem torna o ícone emoji bem grande */
    .neg-box .icone { font-size:4rem; margin-bottom:1rem; }
    /* margin:0 0 .5rem remove margem do topo e adiciona espaço abaixo do título */
    .neg-box h1 { margin:0 0 .5rem; }
    /* var(--text-muted) usa uma variável CSS definida em estilo.css para a cor cinza */
    .neg-box p  { color:var(--text-muted,#64748b); margin-bottom:1.5rem; }
  </style>
</head>
<body>
<!-- <div class="neg-box"> é o container centralizado com o conteúdo da mensagem de erro -->
<div class="neg-box">
  <!-- <div class="icone"> exibe o emoji de proibição em tamanho grande -->
  <div class="icone">🚫</div>
  <!-- <h1> é o título principal da página — nível mais importante semanticamente -->
  <h1>Acesso Negado</h1>
  <!-- <p> exibe a mensagem explicativa; <br> quebra a linha sem criar novo parágrafo -->
  <p>Você não tem permissão para acessar esta página.<br>
     Entre em contato com o administrador do sistema.</p>
  <!-- <a> cria um link estilizado como botão; href="index.php" leva ao painel principal -->
  <a href="index.php" class="btn btn-primario">← Voltar ao painel</a>
</div>
</body>
</html>
