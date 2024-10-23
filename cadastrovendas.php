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

    // Consulta para obter os produtos cadastrados
    $sql_produtos = "SELECT id_produto, nome_produto FROM produto";
    $result_produtos = $conn->query($sql_produtos);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $tipoPagamento = $_POST['tipo'];
        $quantidade = $_POST['quantidade'];
        $data = $_POST['data'];
        $horario = $_POST['horario'];
        $produto_id = $_POST['produto'];

        // Consulta o nome do produto com base no ID selecionado
        $sql_nome_produto = "SELECT nome_produto FROM produto WHERE id_produto = ?";
        $stmt_produto = $conn->prepare($sql_nome_produto);
        $stmt_produto->bind_param("i", $produto_id);
        $stmt_produto->execute();
        $stmt_produto->bind_result($nome_produto);
        $stmt_produto->fetch();
        $stmt_produto->close();

        // Atualiza a tabela de vendas, gravando o nome do produto
        $sql_venda = "INSERT INTO venda (tipo_pagamento, quantidade, data_venda, hora_venda, produto_venda) VALUES (?, ?, ?, ?, ?)";
        $stmt_venda = $conn->prepare($sql_venda);
        $stmt_venda->bind_param("sssss", $tipoPagamento, $quantidade, $data, $horario, $nome_produto);

        if ($stmt_venda->execute()) {
            $message = "Venda cadastrada com sucesso!";
        } else {
            $message = "Erro ao cadastrar venda: " . $stmt_venda->error;
        }

        $stmt_venda->close();
    }

    $conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/cadastrovendas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Cadastro de Vendas</title>
</head>
<body>
    <header>
        <div class="hdr">
            <img class="logo-header" src="./images/comp.png" alt="LOGO">
            <a href="menuAdm.php">Menu</a>
            <a href="estoque.php">Gerenciamento de Estoque</a>
            <a href="fornecedores.php">Consultar Fornecedores</a>
            <a href="cadastroprodutos.php">Cadastro de Produtos</a>
            <a href="funcionarios.php">Gerenciamento de Funcionários</a>
        </div>
    </header>
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('vendas.php')" aria-label="Voltar"></i>
    </div>   
    <main id="container-main">
        <section id="Titulo-Principal"><h1>Cadastro de Vendas</h1></section>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="cadastrovendas.php" method="POST">
            <section id="container-elementos">
                <div class="elementos--itens">
                    <i class="fa-solid fa-money-check-dollar" aria-label="Ícone de ID"></i>
                    <input type="text" id="Tipo" name="tipo" placeholder="Tipo de Pagamento..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-boxes-stacked" aria-label="Ícone de Material"></i>
                    <input type="text" id="Quantidade" name="quantidade" placeholder="Quantidade..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-regular fa-calendar-days" aria-label="Ícone de Data"></i>
                    <input type="text" id="Data" name="data" placeholder="Data da Venda..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-regular fa-clock" aria-label="Ícone de Horário"></i>
                    <input type="text" id="Horario" name="horario" placeholder="Horário da Venda..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-box-open" aria-label="Ícone de Produto"></i>
                    <select id="Produto" name="produto" required>
                        <option value="">Selecione um Produto...</option>
                        <?php if ($result_produtos->num_rows > 0): ?>
                            <?php while ($row = $result_produtos->fetch_assoc()): ?>
                                <option value="<?php echo $row['id_produto']; ?>"><?php echo $row['nome_produto']; ?></option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="">Nenhum produto disponível</option>
                        <?php endif; ?>
                    </select>
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
