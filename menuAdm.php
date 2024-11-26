<?php
    session_start(); // Inicia uma nova sessão ou resume a sessão existente

    $nivel_usuario = $_SESSION['nivel']; // Armazena o nível de acesso do usuário na variável $nivel_usuario
    $usuario = $_SESSION['usuario']; // Armazena o nome do usuário na variável $usuario

    // Verifica se o usuário está logado
    if (!isset($_SESSION['usuario'])) {
        header("Location: login.php"); // Redireciona para a página de login caso o usuário não esteja logado
        exit(); // Interrompe a execução do script
    }

    // Define cabeçalhos para evitar o armazenamento em cache da página
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Configurações básicas do documento -->
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Ajusta a página para dispositivos móveis -->
    <meta http-equiv="X-UA-Compatible" content="IE=7"> <!-- Define compatibilidade com o Internet Explorer 7 -->
    
    <!-- Importação de estilos CSS -->
    <link rel="stylesheet" href="css/menuAdm.css"> <!-- Estilo personalizado para o menu administrativo -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"> <!-- Biblioteca Font Awesome para ícones -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap"> <!-- Fontes personalizadas do Google Fonts -->

    <title>Desenvolvimento de Sistemas</title> <!-- Título da página -->
</head>
<body>
    <header>
        <div class="hdr">
            <!-- Logo do cabeçalho -->
            <img class="logo-header" src="./images/comp.png" alt="LOGO">
        </div>
    </header>

    <!-- Botão para realizar logout -->
    <div class="botao--logout">
        <form method="POST" action="php/logout.php"> <!-- Envia o pedido de logout para logout.php -->
            <button type="submit"><i class="fa-solid fa-right-from-bracket"></i></button> <!-- Ícone do botão de logout -->
        </form>
    </div>

    <main id="container-main">
        <!-- Menu com os botões principais -->
        <section class="first-four-buttons">
            <button class="button-menu"><a href="funcionarios.php">Gerenciamento de Funcionarios</a></button> <!-- Link para gerenciamento de funcionários -->
            <button class="button-menu"><a href="estoque.php">Gerenciamento de Estoque</a></button> <!-- Link para gerenciamento de estoque -->
            <button class="button-menu"><a href="fornecedores.php">Gerenciamento de Fornecedores</a></button> <!-- Link para gerenciamento de fornecedores -->
            <button class="button-menu"><a href="compras.php">Controle de Compras</a></button> <!-- Link para controle de compras -->
            <button class="button-menu"><a href="vendas.php">Controle de Vendas</a></button> <!-- Link para controle de vendas -->
            <button class="button-menu"><a href="relatorio.php">Relatórios e Análises</a></button> <!-- Link para relatórios e análises -->
        </section>
    </main>

    <!-- Botão de suporte -->
    <form action="suporte.php">
        <div class="suporte">
            <button id="btn-suporte">Suporte</button> <!-- Botão fixo para acessar a página de suporte -->
        </div>
    </form>

    <!-- Estilo do botão de suporte -->
    <style>
        .suporte {
            position: fixed; /* Posiciona o botão de forma fixa na tela */
            bottom: 20px; /* Distância da parte inferior da tela */
            right: 20px; /* Distância da lateral direita da tela */
        }

        #btn-suporte {
            background-color: #000; /* Cor de fundo preta */
            color: #fff; /* Cor do texto branca */
            border: none; /* Remove a borda padrão */
            padding: 10px 20px; /* Adiciona preenchimento interno */
            border-radius: 5px; /* Arredonda os cantos do botão */
            cursor: pointer; /* Define o cursor como uma mão ao passar sobre o botão */
            font-size: 0.9em; /* Define o tamanho da fonte */
            transition: background-color 0.3s; /* Suaviza a transição da cor de fundo ao passar o mouse */
        }

        #btn-suporte:hover {
            background-color: #444; /* Altera a cor de fundo ao passar o mouse */
        }
    </style>
</body>
</html>
