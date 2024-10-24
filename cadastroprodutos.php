<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nossasa";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
    $message = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $tamanho = $_POST['tamanho'];
        $cor = $_POST['cor'];
        $preco = $_POST['preco'];
        $quantidade = $_POST['quantidade'];

        $sql = "INSERT INTO produto (nome_produto, descricao_produto, tamanho, cor, preco, quantidade) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("sssddi", $nome, $descricao, $tamanho, $cor, $preco, $quantidade);

        if ($stmt->execute()) {
            $message = "Produto cadastrado com sucesso!";
        } else {
            $message = "Erro ao cadastrar produto: " . $stmt->error;
        }

        $stmt->close();
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/cadastroprodutos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Cadastro de Produtos</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap');
    </style>
    <script>
            function mascara(o, f) {
                v_obj = o // ARMANENA INPUT
                v_fun = f // ARMAZENA MÁSCARA
                setTimeout("execmascara()", 1) // DELAY MÁSCARA
            }

            function execmascara() {
                v_obj.value = v_fun(v_obj.value) // ATUALIZA VALOR INPUT COM MÁSCARA
            }

            function mreais(v) {
                v = v.replace(/\D/g, "") // REMOVE OQ NÃO É DÍGITO
                v = v.replace(/(\d{2})$/, ",$1") // PÕE VIRGULA
                v = v.replace(/(\d+)(\d{3},\d{2})$/g, "$1.$2") // PÕE PRIMEIRO PONTO 
                return v
            }
        </script>
</head>
<body>
    <header>
        <div class="hdr">
            <img class="logo-header" src="images/comp.png" alt="LOGO">
            <a href="menuAdm.php">Menu ADM</a>
            <a href="menuFuncionario.php">Menu Funcionário</a>
            <a href="fornecedores.php">Gerenciamento de Fornecedores</a>
            <a href="funcionarios.php">Gerenciamento de Funcionários</a>
            <a href="estoque.php">Gerenciamento de Estoque</a>
        </div>
    </header>
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('menuAdm.php')"></i>
    </div>     
    
    <main id="container-main">
        <section id="Titulo-Principal"><h1>Cadastro de Produtos</h1></section>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <!-- Formulário de Cadastro de Produtos -->
        <form action="cadastroprodutos.php" method="POST">
            <section id="container-elementos">
                <div class="elementos--itens">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <input type="text" id="NomeProduto" name="nome" placeholder="Nome do Produto..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-comment-dots"></i>
                    <input type="text" id="DescProduto" name="descricao" placeholder="Descrição do Produto..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-maximize"></i>
                    <input type="text" id="TamProduto" name="tamanho" placeholder="Tamanho do Produto..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-palette"></i>
                    <input type="text" id="CorProduto" name="cor" placeholder="Cor do Produto..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-hand-holding-dollar"></i>R$
                    <input type="text" id="PrecoProduto" onkeypress="mascara(this,mreais)" name="preco" placeholder="Preço do Produto..." step = 0.01 min='1' max ='800'>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-arrow-up-short-wide"></i>
                    <input type="number" id="QuantProduto" name="quantidade" placeholder="Quantidade do Produto..." max="9999" maxlength="5" required>
                </div>
                <div class="button">
                    <button type="submit">Confirmar</button>
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
