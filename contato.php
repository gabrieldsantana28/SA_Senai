<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/contato.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Contato</title>
</head>
<body>
<header>
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($_SESSION['nivel'] == 1): ?>
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>

<div class="botao--voltar">
    <i class="fa-solid fa-arrow-left" onclick="trocarPagina('suporte.php')"></i>
</div>   

<section id="Titulo-Principal">
    <center>
    <h1>Contato</h1>
</section>

<section class="contact-container">
    <p>Escolha uma das opções abaixo para entrar em contato:</p>
    <div class="contact-buttons">
        <a href="https://wa.me/+5547984659021" target="_blank" class="btn-whatsapp">
            <i class="fa-brands fa-whatsapp"></i> WhatsApp
        </a>
        <a href="mailto:compsupplysa@gmail.com" target="_blank" class="btn-email">
            <i class="fa-solid fa-envelope"></i> E-mail
        </a>
    </div>
</section>

<script>
    function trocarPagina(url) {
        window.location.href = url;
    }

    function voltarMenu() {
        const nivel = <?php echo isset($_SESSION['nivel']) ? $_SESSION['nivel'] : 'null'; ?>;
        if (nivel !== null) {
            if (nivel == 1) {
                window.location.href = 'menuAdm.php';
            } else if (nivel == 2) {
                window.location.href = 'menuFuncionario.php';
            }
        } else {
            alert('Sessão expirada. Faça login novamente.');
            window.location.href = 'index.php';
        }
    }
</script>
</body>
</html>
