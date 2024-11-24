<?php
session_start();

if (!isset($_SESSION['usuario']) || !isset($_SESSION['nivel'])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: login.php");
    exit();
}

$nivel_usuario = $_SESSION['nivel']; 
$usuario = $_SESSION['usuario']; 

if ($nivel_usuario != 2) { 
    header("Location: acesso_restrito.php");
    exit();
}

// Desativa o cache da página para evitar que ela seja acessada pelo botão de "voltar" do navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/menuFuncionario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Desenvolvimento de Sistemas</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
<body>
    <header>
        <div class="hdr">
            <img class="logo-header" src="./images/comp.png" alt="LOGO">
        </div>
    </header>

    <div class="botao--logout">
        <form method="POST" action="php/logout.php">
            <button type="submit"><i class="fa-solid fa-right-from-bracket"></i></button>
        </form>
    </div>    
    
    <main id="container-main">
        <section class="first-four-buttons">
            <button class="button-menu"><a href="compras.php">Controle de Compras</a></button>
            <button class="button-menu"><a href="vendas.php">Controle de Vendas</button>
            <button class="button-menu"><a href="estoque.php">Gerenciamento de Estoque</button>
            <button class="button-menu"><a href="fornecedores.php">Gerenciamento de Fornecedores</button>
        </section>
    </main>
    <form action="suporte.php">
        <div class="suporte">
            <button id="btn-suporte">Suporte</button>
        </div>
    </form>
    <style>
        .suporte {
        position: fixed;
        bottom: 20px;
        right: 20px;
        }

        #btn-suporte {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.3s;
        }

        #btn-suporte:hover {
            background-color: #444;
        }
    </style>
</body>
</html>
