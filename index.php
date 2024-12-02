<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Configurações básicas do documento -->
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Ajusta a página para dispositivos móveis -->
    <meta http-equiv="X-UA-Compatible" content="IE=7"> <!-- Define compatibilidade com o Internet Explorer 7 -->
    
    <!-- Importação de estilos CSS -->
    <link rel="stylesheet" href="css/login.css"> <!-- Estilo personalizado para a página -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"> <!-- Biblioteca Font Awesome para ícones -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap"> <!-- Fontes personalizadas do Google Fonts -->

    <title>Login</title> <!-- Título da página -->
</head>
<body>
    <header>
        <div class="hdr">
            <!-- Logo do cabeçalho -->
            <img class="logo-header" src="./images/comp.png" alt="LOGO">
        </div>
    </header>
    
    <main id="container-main">
        <!-- Título principal da página -->
        <section id="Titulo-Principal"><h1>Login</h1></section>
        <br>
        <br>
        <!-- Descrição sobre o login -->
        <section id="Descricao-login"><h3>Acesse sua conta</h3></section>
        <section id="Descricao-login"><p>Insira seu usuário e senha para continuar</p></section>

        <?php
            // Inicia uma sessão PHP
            session_start();
            
            // Verifica se existe uma mensagem de erro armazenada na sessão
            if (isset($_SESSION['login_error'])) {
                // Exibe a mensagem de erro em um elemento div
                echo '<div class="error">' . $_SESSION['login_error'] . '</div>';
                unset($_SESSION['login_error']); // Remove a mensagem de erro da sessão
            }
        ?>

        <!-- Formulário de login -->
        <form method="POST" action="php/backLogin.php"> <!-- Envia os dados via método POST para o arquivo backLogin.php -->
            <section id="container-elementos">
                <!-- Campo para o nome de usuário -->
                <div class="elementos--itens">
                    <i class="fas fa-id-badge"></i> <!-- Ícone representando identificação -->
                    <input type="text" id="NomeUsuario" name="usuario" placeholder="Insira seu nome de usuário" required> <!-- Input obrigatório para o nome de usuário -->
                </div> 
                <!-- Campo para a senha -->
                <div class="elementos--itens"> 
                    <i class="fa-solid fa-lock"></i> <!-- Ícone representando uma fechadura -->
                    <input type="password" id="SenhaUsuario" name="senha" placeholder="Insira sua senha" required> <!-- Input obrigatório para a senha -->
                </div>
                <!-- Botão para enviar o formulário -->
                <div class="button">
                    <button type="submit">Entrar</button> <!-- Envia os dados do formulário -->
                </div>
            </section>
        </form>
    </main>
</body>
</html>