<?php
// Iniciar ou retomar a sessão
session_start();

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Encerrar a sessão
session_destroy();

// Redirecionar para a página de login ou qualquer outra página desejada após o logout
header("Location: index.html");
exit();
?>
