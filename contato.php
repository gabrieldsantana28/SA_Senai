<?php
    // Inicia a sessão para manter os dados do usuário durante a navegação
    session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres para UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Torna a página responsiva -->
    <meta http-equiv="X-UA-Compatible" content="IE=7"> <!-- Define a compatibilidade para navegadores Internet Explorer -->
    <!-- Importa os arquivos de estilo -->
    <link rel="stylesheet" href="css/contato.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"> <!-- Ícones -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap"> <!-- Fontes personalizadas -->
    <title>Contato</title> <!-- Título da página -->
</head>
<body>
<header>
    <div class="hdr">
        <!-- Logo que redireciona para o menu ao ser clicado -->
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <!-- Links de navegação -->
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php 
        // Exibe links específicos apenas para usuários de nível 1 (administradores)
        if ($_SESSION['nivel'] == 1): ?>
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>

<!-- Botão de voltar -->
<div class="botao--voltar">
    <i class="fa-solid fa-arrow-left" onclick="trocarPagina('suporte.php')"></i>
</div>   

<!-- Seção do título principal -->
<section id="Titulo-Principal">
    <center>
    <h1>Contato</h1>
</section>

<!-- Container de opções de contato -->
<section class="contact-container">
    <p>Escolha uma das opções abaixo para entrar em contato:</p>
    <div class="contact-buttons">
        <!-- Botão de contato via WhatsApp -->
        <a href="https://wa.me/+5547984659021" target="_blank" class="btn-whatsapp">
            <i class="fa-brands fa-whatsapp"></i> WhatsApp
        </a>
        <!-- Botão de contato via E-mail -->
        <a href="https://mail.google.com/mail/u/1/#inbox?compose=GTvVlcSPGFkllpWBQKfzvZgqVVvZWZCjNLSXTkDHTDslzMvptQwhHFVnLJqzXGfsQRzfftghDcFDs" target="_blank" class="btn-email">
            <i class="fa-solid fa-envelope"></i> E-mail
        </a>
    </div>
</section>

<script>
    // Função para redirecionar para outra página
    function trocarPagina(url) {
        window.location.href = url;
    }

    // Função para voltar ao menu principal dependendo do nível de acesso
    function voltarMenu() {
        // Recupera o nível do usuário da sessão, se existir
        const nivel = <?php echo isset($_SESSION['nivel']) ? $_SESSION['nivel'] : 'null'; ?>;
        if (nivel !== null) {
            if (nivel == 1) {
                // Redireciona para o menu do administrador
                window.location.href = 'menuAdm.php';
            } else if (nivel == 2) {
                // Redireciona para o menu do funcionário
                window.location.href = 'menuFuncionario.php';
            }
        } else {
            // Caso a sessão esteja expirada, exibe um alerta e redireciona para a tela de login
            alert('Sessão expirada. Faça login novamente.');
            window.location.href = 'index.php';
        }
    }
</script>
</body>
</html>
