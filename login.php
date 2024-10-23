<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap');
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="hdr">
            <img class="logo-header" src="./images/comp.png" alt="LOGO">
        </div>
    </header>
    
    <main id="container-main">
        <section id="Titulo-Principal"><h1>Login</h1></section>
        <br>
        <br>
        <section id="Descricao-login"><h3>Acesse sua conta</h3></section>
        <section id="Descricao-login"><p>Insira seu usuário e senha para continuar</p></section>

        <!-- Exibe a mensagem de erro se houver -->
        <?php
            session_start();
            if (isset($_SESSION['login_error'])) {
                echo '<div class="error">' . $_SESSION['login_error'] . '</div>';
                unset($_SESSION['login_error']); 
            }
        ?>

        <form method="POST" action="php/backLogin.php">
            <section id="container-elementos">
                <div class="elementos--itens">
                    <i class="fas fa-id-badge"></i>
                    <input type="text" id="NomeUsuario" name="usuario" placeholder="Insira seu nome de usuário" required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="SenhaUsuario" name="senha" placeholder="Insira sua senha" required>
                </div>
                <br>
                <div class="button">
                    <button type="submit">Entrar</button>
                </div>
            </section>
        </form>
    </main>
</body>
</html>
