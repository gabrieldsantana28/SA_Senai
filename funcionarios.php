<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/fornecedores.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Desenvolvimento de Sistemas</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap');
    </style>
</head>
<body>
    <header>
        <div class="hdr">
            <img class="logo-header" src="./images/comp.png" alt="LOGO">
            <a href="menuAdm.php">Menu ADM</a>
            <a href="menuFuncionario.php">Menu Funcionário</a>
            <a href="fornecedores.php">Gerenciamento de Fornecedores</a>
            <a href="cadastroprodutos.php">Cadastro de Produtos</a>
            <a href="estoque.php">Gerenciamento de Estoque</a>
        </div>
    </header>
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('menuAdm.php')"></i>
    </div>      
    
    <section id="Titulo-Principal"><h1>Gerenciamento de Funcionários</h1></section>

    <main id="container-main">
        <section>
            <br>
            <div style="margin: auto;" class="elementos--itens">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="PesquisarFuncionario" name="PesquisarFuncionario" placeholder="Pesquisar Funcionário...">
                <button class="icon-btn" id="redirectBtn">
                    <a class="fa-solid fa-plus" href="cadastrofuncionarios.php"></a>
                </button>
            </div>
            <br>
        </section>

        <section>
            <div style="margin: auto;" class="fornecedor--item">
                <div class="elementos--itens--dois">
                    Gabriel Luis Santana
                </div>
                <i class="fa-solid fa-trash" style="color: red;"></i>
                <i class="fa-solid fa-pen-to-square"></i> <br>
            </div>
            <br>
            <div style="margin: auto;" class="fornecedor--item">
                <div class="elementos--itens--dois">
                    Thauã Brandão da Silva
                </div>
                <i class="fa-solid fa-trash" style="color: red;"></i>
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <br>
            
            <div style="margin: auto;" class="fornecedor--item">
                <div class="elementos--itens--dois">
                    Pedrão
                </div>
                <i class="fa-solid fa-trash" style="color: red;"></i>
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <br>
            
            <div style="margin: auto;" class="fornecedor--item">
                <div class="elementos--itens--dois">
                    Carlão
                </div>
                <i class="fa-solid fa-trash" style="color: red;"></i>
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <br>
            

            
        </section>
    </main>
    <script>
        function trocarPagina(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>