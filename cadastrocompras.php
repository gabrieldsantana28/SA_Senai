<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nossasa";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$preco = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fornecedor = $_POST['fornecedor'];
    $produto_compra = $_POST['produto_compra'];
    $quantidade_compra = $_POST['quantidade_compra'];
    $preco_compra = $_POST['preco_compra'];
    $tipo_pagamento_compra = $_POST['tipo_pagamento_compra'];
    $data_compra = $_POST['data_compra'];
    $hora_compra = $_POST['hora_compra'];

    // Processamento do preço
    $preco = str_replace('.', '', $_POST['preco_compra']); // Remove o ponto
    $preco = str_replace(',', '.', $preco); // Troca a vírgula pelo ponto para conversão
    $quantidade = $_POST['quantidade_compra'];

    $sql = "INSERT INTO compra (fk_id_fornecedor, produto_compra, quantidade_compra, preco_compra, tipo_pagamento_compra, data_compra, hora_compra) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isissss", $fornecedor, $produto_compra, $quantidade_compra, $preco_compra, $tipo_pagamento_compra, $data_compra, $hora_compra);

    if ($stmt->execute()) {
        $message = "Compra cadastrada com sucesso!";
    } else {
        $message = "Erro ao cadastrar compra: " . $stmt->error;
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
    <link rel="stylesheet" href="css/cadastrocompras.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Cadastro de Compras</title>
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
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('compras.php')"></i>
    </div>    
    
    <main id="container-main">
        <section id="Titulo-Principal"><h1>Cadastro de Compras</h1></section>
        
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <section id="container-elementos">
                <div class="elementos--itens">
                    <i class="fa-solid fa-truck"></i>
                    <select id="Fornecedor" name="fornecedor" required>
                        <option value="" disabled selected>Selecione o Fornecedor...</option>
                        <?php
                            // Carregar fornecedores
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            $sql_fornecedores = "SELECT id_fornecedor, nome_fornecedor FROM fornecedor";
                            $result_fornecedores = $conn->query($sql_fornecedores);
                            while ($linha = $result_fornecedores->fetch_assoc()) {
                                echo "<option value='" . $linha["id_fornecedor"] . "'>" . $linha["nome_fornecedor"] . "</option>";
                            }
                            $conn->close();
                        ?>
                    </select>
                </div>

                <div class="elementos--itens">
                    <i class="fa-solid fa-cube"></i>
                    <input type="text" id="ProdutoCompra" name="produto_compra" placeholder="Produto comprado..." required>
                </div>

                <div class="elementos--itens">
                    <i class="fa-solid fa-box"></i>
                    <input type="number" id="QuantidadeCompra" name="quantidade_compra" placeholder="Quantidade..." required>
                </div>

                <div class="elementos--itens">
                    <i class="fa-solid fa-hand-holding-dollar"></i>R$
                    <input type="text" id="PrecoCompra" onkeypress="mascara(this, mreais)" oninput="validarPreco();" name="preco_compra" placeholder="Preço..." step="0.01" value="<?php echo htmlspecialchars($preco); ?>" required>
                </div>

                <div class="elementos--itens">
                    <i class="fa-solid fa-credit-card"></i>
                    <select id="TipoPagamentoCompra" name="tipo_pagamento_compra" required>
                        <option value="" disabled selected>Selecione o Tipo de Pagamento...</option>
                        <option value="À vista">Dinheiro/A vista</option>
                        <option value="PIX">PIX</option>
                        <option value="Parcelado">Parcelado</option>
                        <option value="Débito">Débito</option>
                    </select>
                </div>

                <div class="elementos--itens">
                    <i class="fa-solid fa-calendar-day"></i>
                    <input type="date" id="DataCompra" name="data_compra" required>
                </div>

                <div class="elementos--itens">
                    <i class="fa-solid fa-clock"></i>
                    <input type="time" id="HoraCompra" name="hora_compra" required>
                </div>

                <div class="button">
                    <button type="submit">Cadastrar</button>
                </div>
            </section>
        </form>
    </main>

    <script>
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
                window.location.href = 'login.php';
            }
        }
    </script>
</body>
</html>
