<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/cadastrofuncionarios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Cadastro de Produtos</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap');
    </style>
</head>
<body>
    <header>
        <div class="hdr">
            <img class="logo-header" src="./images/comp.png" alt="LOGO">
            <a href="menuAdm.php">Menu</a>
            <a href="estoque.php">Gerenciamento de Estoque</a>
            <a href="fornecedores.php">Consultar Fornecedores</a>
            <a href="vendas.php">Consultar Vendas</a>
            <a href="cadastrofuncionarios.php">Cadastro de Funcionários</a>
        </div>
    </header>
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('funcionarios.php')"></i>
    </div>    
    
    <main id="container-main">
        <section id="Titulo-Principal"><h1>Cadastro de Funcionários</h1></section>
        
        
        <form action="php/insertFuncionario.php" method="POST">
            <section id="container-elementos">
                <div class="elementos--itens">
                    <i class="fas fa-user-tag"></i>
                    <input type="text" id="NomeFuncionario" name="nome" placeholder="Nome do Funcionário..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="Usuario" name="usuario" placeholder="Nome de usuário..." required>
                </div>
                <div class="elementos--itens">
                    <i class='fa-solid fa-envelope'></i>
                    <input type="text" id="Email" name="email" placeholder="Email..." required>
                </div>
                <div class="elementos--itens">
                    <i class='fa-solid fa-lock'></i>
                   <input type="password" id="Senha" name="senha" placeholder="Senha(Mín. 6 Caracteres)..." maxlength="6" required>
                </div>
                <div class="button">
                    <button type="submit">Cadastrar</button>
                </div>
            </section>
        </form>
    </main>
    <script>
        function trocarPagina(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>