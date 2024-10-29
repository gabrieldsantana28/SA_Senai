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
            <button class="button-menu"><a href="cadastroprodutos.php">Cadastro de Produtos</a></button>
            <button class="button-menu"><a href="vendas.php">Controle de Vendas</button>
            <button class="button-menu"><a href="estoque.php">Gerenciamento de Estoque</button>
            <button class="button-menu"><a href="fornecedores.php">Gerenciamento de Fornecedores</button>
        </section>
    </main>
</body>
</html>
