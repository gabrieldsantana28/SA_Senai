<?php
    // Inicia a sessão para acessar variáveis de sessão
    session_start();

    // Verifica se as variáveis de sessão 'usuario' e 'nivel' estão definidas, ou seja, se o usuário está logado
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['nivel'])) {
        // Redireciona para a página de login caso não esteja logado
        header("Location: index.php");
        exit();
    }

    // Armazena o nível do usuário e o nome do usuário nas variáveis
    $nivel_usuario = $_SESSION['nivel']; 
    $usuario = $_SESSION['usuario']; 

    // Verifica se o nível do usuário é diferente de 2 (funcionário)
    if ($nivel_usuario != 2) { 
        // Se o nível não for 2, redireciona para uma página de acesso restrito
        header("Location: acesso_restrito.php");
        exit();
    }

    // Define cabeçalhos HTTP para prevenir cache das páginas
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <!-- Define a largura da página para o dispositivo, garantindo boa exibição em telas móveis -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Compatibilidade com versões mais antigas do Internet Explorer -->
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    
    <!-- Link para o CSS da página -->
    <link rel="stylesheet" href="css/menuFuncionario.css">
    
    <!-- Link para os ícones da Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <title>Desenvolvimento de Sistemas</title>
    <!-- Link para fontes externas Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
</head>

<body>
    <header>
        <div class="hdr">
            <!-- Exibe o logo da empresa no cabeçalho -->
            <img class="logo-header" src="./images/comp.png" alt="LOGO">
        </div>
    </header>

    <div class="botao--logout">
        <!-- Formulário de logout com ícone de desconexão -->
        <form method="POST" action="php/logout.php">
            <button type="submit"><i class="fa-solid fa-right-from-bracket"></i></button>
        </form>
    </div>    
    
    <main id="container-main">
        <section class="first-four-buttons">
            <!-- Botões de navegação para diferentes seções do sistema -->
            <button class="button-menu"><a href="compras.php">Controle de Compras</a></button>
            <button class="button-menu"><a href="vendas.php">Controle de Vendas</button>
            <button class="button-menu"><a href="estoque.php">Gerenciamento de Estoque</button>
            <button class="button-menu"><a href="fornecedores.php">Gerenciamento de Fornecedores</button>
        </section>
    </main>

    <!-- Botão fixo de suporte -->
    <form action="suporte.php">
        <div class="suporte">
            <button id="btn-suporte">Suporte</button>
        </div>
    </form>

    <style>
        /* Estilo para o botão de suporte fixado no canto inferior direito */
        .suporte {
            position: fixed;
            bottom: 20px;
            right: 20px;
        }

        /* Estilo do botão de suporte */
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

        /* Efeito de hover para o botão de suporte */
        #btn-suporte:hover {
            background-color: #444;
        }
    </style>
</body>
</html>

