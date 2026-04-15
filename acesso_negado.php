<?php
//session_start() é uma função do PHP que inicia uma nova sessão ou retoma uma sessão existente. 
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acesso Negado — Sistema Loja</title>
  <link rel="stylesheet" href="estilo.css">
  <style>
    body { display:flex; align-items:center; justify-content:center; min-height:100vh; }
    .neg-box { text-align:center; max-width:400px; padding:2rem; }
    .neg-box .icone { font-size:4rem; margin-bottom:1rem; }
    .neg-box h1 { margin:0 0 .5rem; }
    .neg-box p  { color:var(--text-muted,#64748b); margin-bottom:1.5rem; }
  </style>
</head>
<body>
<div class="neg-box">
  <div class="icone">🚫</div>
  <h1>Acesso Negado</h1>
  <p>Você não tem permissão para acessar esta página.<br>
     Entre em contato com o administrador do sistema.</p>
  <a href="index.php" class="btn btn-primario">← Voltar ao painel</a>
</div>
</body>
</html>
