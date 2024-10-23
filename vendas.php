<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/vendas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Gerenciamento de Vendas</title>
</head>
<body>
    <header>
        <div class="hdr">
            <img class="logo-header" src="./images/comp.png" alt="LOGO">
            <a href="menuAdm.php">Menu</a>
            <a href="estoque.php">Gerenciamento de Estoque</a>
            <a href="fornecedores.php">Consultar Fornecedores</a>
            <a href="cadastrofuncionarios.php">Cadastro de Funcionários</a>
            <a href="cadastroprodutos.php">Cadastro de Produtos</a>
        </div>
    </header>

    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('menuAdm.php')"></i>
    </div>   
    
    <section id="Titulo-Principal">
        <h1>Controle de Vendas</h1>
    </section>

    <section style="margin-bottom: 20px;">
        <div class="elementos--itens">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="PesquisarVenda" name="PesquisarVenda" placeholder="Pesquisar Venda...">
            <button class="icon-btn" id="redirectBtn">
                <a href="cadastrovendas.php">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </button>
        </div>
    </section>

    <section id="container-elementos">
        <div class="elementos">ID</div>
        <div class="elementos">PRODUTO</div>
        <div class="elementos">PREÇO - PAGAMENTO</div>
        <div class="elementos">TIPO - PAGAMENTO</div>
        <div class="elementos">DATA</div>
        <div class="elementos">HORA</div>
    </section>

    <section id="lista-elementos">
        <div class="elementos-lista">x</div>
        <div class="elementos-lista">x</div>
        <div class="elementos-lista">x</div>
        <div class="elementos-lista">x</div>
        <div class="elementos-lista">x</div>
        <div class="elementos-lista">x</div>
        <div class="icons">
            <i class="fa-solid fa-trash" style="color: red;"></i>
            <i class="fa-solid fa-pen-to-square"></i>
        </div>
    </section>
    <script>
        function trocarPagina(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
