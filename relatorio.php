<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/relatorio.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Desenvolvimento de Sistemas</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap');
        </style>
<body>
    <header>
        <div class="hdr">
            <img class="logo-header" src="./images/comp.png" alt="LOGO">
            <a href="menuAdm.php">Menu ADM</a>
            <a href="menuFuncionario.php">Menu Funcionário</a>
            <a href="funcionarios.php">Gerenciamento de Funcionários</a>
            <a href="cadastroprodutos.php">Cadastro de Produtos</a>
            <a href="estoque.php">Gerenciamento de Estoque</a>
        </div>
    </header>
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('menuAdm.php')"></i>
    </div> 

    <main id="container-main">
        <section class="first-five-buttons">
            GRÁFICO
        </section>
        <section class="first-four-buttons">
            <button class="button-menu"><a href="#">Baixar relatório semanal</a>
                <div><i class="fa-solid fa-cloud-arrow-down"></i></div>
            </button>
            <button class="button-menu"><a href="#">Baixar relatório mensal
                <div><i class="fa-solid fa-cloud-arrow-down"></i></div>
            </button>
            <button class="button-menu"><a href="#">Baixar relatório de estoque
                <div><i class="fa-solid fa-cloud-arrow-down"></i></div>
            </button>
        </section>
    </main>
    <script>
        function trocarPagina(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
