<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    // Redireciona para a página de login se a sessão não existir
    header("Location: login.php");
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
    <link rel="stylesheet" href="css/menuAdm.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Desenvolvimento de Sistemas</title>
</head>
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
            <button class="button-menu"><a href="cadastroprodutos.php">Cadastro de Produtos</a></button>
            <button class="button-menu"><a href="vendas.php">Controle de Vendas</a></button>
            <button class="button-menu"><a href="relatorio.php">Relatórios e Análises</a></button>
            <button class="button-menu"><a href="funcionarios.php">Gerenciamento de Funcionarios</a></button>
            <button class="button-menu"><a href="estoque.php">Gerenciamento de Estoque</a></button>
            <button class="button-menu"><a href="fornecedores.php">Gerenciamento de Fornecedores</a></button>
        </section>
    </main>
</body>
</html>
