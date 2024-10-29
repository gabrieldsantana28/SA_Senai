<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nossasa";

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $sql_compras = "SELECT id_compra, fornecedor, produto_compra, quantidade_compra, tipo_pagamento_compra, data_compra, hora_compra FROM compra";
    $result_compras = $conn->query($sql_compras);

    // RECUPERA NÍVEL DA CONTA 
    $nivel = $_SESSION['nivel'] ?? 0; // NÍVEL DA CONTA EM 0 CASO NÃO ESTEJA LOGADO
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/compras.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Controle de Compras</title>
</head>
<body>
    <header>
        <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
            <a href="estoque.php">Estoque</a>
            <a href="funcionarios.php">Funcionários</a>
            <a href="fornecedores.php">Fornecedores</a>
            <a href="cadastroprodutos.php">CadasProdutos</a>
            <a href="vendas.php">Vendas</a>
            <a href="relatorio.php">Relatórios</a>
        </div>
    </header>

    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('menuAdm.php')"></i>
    </div>   
    
    <section id="Titulo-Principal">
        <h1>Controle de Compras</h1>
    </section>

    <section style="margin-bottom: 20px;">
        <div class="elementos--itens">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="PesquisarCompra" name="PesquisarCompra" placeholder="Pesquisar Compra...">
            <button class="icon-btn" id="redirectBtn">
                <a href="cadastrocompras.php">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </button>
        </div>
    </section>

    <section id="container-elementos">
        <div class="elementos">N° COMPRA</div>
        <div class="elementos">PRODUTO</div>
        <div class="elementos">FORNECEDOR</div>
        <div class="elementos">PREÇO</div>
        <div class="elementos">TIPO</div>
        <div class="elementos">DATA</div>
        <div class="elementos">HORA</div>
    </section>

<?php

    // DELETA VENDA
    if (isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        $sql_delete = "DELETE FROM compra WHERE id_compra = $id";
        $conn->query($sql_delete);
        // VOLTAR
        header("Location: compras.php"); 
        exit;
    }

    if ($result_compras->num_rows > 0) {
        while ($linha = $result_compras->fetch_assoc()) {
            echo '<section id="lista-elementos">';
            echo '<div class="elementos-lista">' . $linha["id_compra"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["fornecedor"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["produto_compra"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["tipo_pagamento_compra"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["data_compra"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["hora_compra"] . '</div>';
            echo '<div class="icons">';
            
            // EXCLUIR
            echo '<form method="POST" style="display:inline-block;" onsubmit="return confirmarExclusao();">';
            echo '<input type="hidden" name="delete_id" value="' . $linha["id_compra"] . '">';
            echo '<button type="submit" style="background:none; border:none;">';
            echo '<i class="fa-solid fa-trash" style="color: red;"></i>';
            echo '</button>';
            echo '</form>';
            
            // EDITAR
            echo '<a href="editar.php?id=' . $linha["id_compra"] . '"><i class="fa-solid fa-pen-to-square"></i></a>';
            echo '</div>';
            echo '</section>';
        }
    } else {
        echo "Sem resultados";
    }

?> 
<script>
function confirmarExclusao() {
    return confirm("Você realmente deseja apagar este item?");
}
</script>
    <script>
        function trocarPagina(url) {
            window.location.href = url;
        }
        function voltarMenu() {
            <?php if ($nivel == 1): ?>
                window.location.href = 'menuAdm.php';
            <?php elseif ($nivel == 2): ?>
                window.location.href = 'menuFuncionario.php';
            <?php else: ?>
                alert('Nível de conta não identificado. Faça login novamente.');
                window.location.href = 'login.php'; 
            <?php endif; ?>
        }
    </script>
</body>
</html>