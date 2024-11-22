<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nossasa";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $message = "";
    $nome = "";
    $descricao = "";
    $tamanho = "";
    $cor = "";
    $preco = "";
    $quantidade = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $tamanho = $_POST['tamanho'];
        $cor = $_POST['cor'];

        // Processamento do preço
        $preco = str_replace('.', '', $_POST['preco']); // Remove o ponto
        $preco = str_replace(',', '.', $preco); // Troca a vírgula pelo ponto para conversão
        $quantidade = $_POST['quantidade'];

        if ($preco > 1000) {
            $message = "Erro: O preço não pode exceder R$1000.";
        } else {
            $sql = "INSERT INTO produto (nome_produto, descricao_produto, tamanho_produto, cor_produto, preco_produto, quantidade_produto) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            $stmt->bind_param("sssddi", $nome, $descricao, $tamanho, $cor, $preco, $quantidade);

            if ($stmt->execute()) {
                $message = "Produto cadastrado com sucesso!";
                // Limpa os campos após o sucesso
                $nome = "";
                $descricao = "";
                $tamanho = "";
                $cor = "";
                $preco = "";
                $quantidade = "";
            } else {
                $message = "Erro ao cadastrar produto: " . $stmt->error;
            }

            $stmt->close();
        }
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Cadastro de Produtos</title>
</head>
<body>
<header>
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($_SESSION['nivel'] == 1): // Apenas admin pode ver estas opções ?>
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('estoque.php')"></i>
    </div>

    <main id="container-main">
        <section id="Titulo-Principal"><h1>Cadastro de Produtos</h1></section>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="cadastroprodutos.php" method="POST">
            <section id="container-elementos">
                <div class="elementos--itens">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <input type="text" id="NomeProduto" name="nome" placeholder="Nome do Produto..." value="<?php echo htmlspecialchars($nome); ?>" required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-comment-dots"></i>
                    <input type="text" id="DescProduto" name="descricao" placeholder="Descrição do Produto..." value="<?php echo htmlspecialchars($descricao); ?>" required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-maximize"></i>
                    <input type="text" id="TamProduto" name="tamanho" placeholder="Tamanho do Produto..." value="<?php echo htmlspecialchars($tamanho); ?>" required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-palette"></i>
                    <input type="text" id="CorProduto" name="cor" placeholder="Cor do Produto..." value="<?php echo htmlspecialchars($cor); ?>" required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-hand-holding-dollar"></i>R$
                    <input type="text" id="PrecoProduto" onkeypress="mascara(this, mreais)" oninput="validarPreco();" name="preco" placeholder="Preço do Produto..." step="0.01" value="<?php echo htmlspecialchars($preco); ?>" required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-arrow-up-short-wide"></i>
                    <input type="number" id="QuantProduto" name="quantidade" placeholder="Quantidade do Produto..." max="9999" value="<?php echo htmlspecialchars($quantidade); ?>" required>
                </div>
                <div class="button">
                    <button type="submit">Confirmar</button>
                </div>
            </section>
        </form>
    </main>
</body>
</html>
<script>
        function trocarPagina(url) {
            console.log("Tentando navegar para:", url);
            window.location.href = url;
        }

        function mascara(o, f) {
            v_obj = o // ARMAZENAR INPUT
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

        function validarPreco() {
            const precoInput = document.getElementById("PrecoProduto");
            const maxPreco = 1000;

            let preco = precoInput.value.replace(",", "."); // Converte vírgula para ponto no valor

            if (parseFloat(preco) > maxPreco) {
                alert("O preço não pode ser superior a R$1000.");
                precoInput.value = maxPreco.toFixed(2).replace(".", ",");
            }
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
                window.location.href = 'login.php';
            }
        }
    </script>
