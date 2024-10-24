<?php
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

$sql_vendas = "SELECT id_venda, produto_venda, quantidade, tipo_pagamento, data_venda, hora_venda FROM venda";
$result_vendas = $conn->query($sql_vendas);
?>

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
        <div class="elementos">N° VENDA</div>
        <div class="elementos">PRODUTO</div>
        <div class="elementos">PREÇO - PAGAMENTO</div>
        <div class="elementos">TIPO - PAGAMENTO</div>
        <div class="elementos">DATA</div>
        <div class="elementos">HORA</div>
    </section>

<?php

    // DELETA VENDA
    if (isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        $sql_delete = "DELETE FROM venda WHERE id_venda = $id";
        $conn->query($sql_delete);
        // VOLTAR
        header("Location: vendas.php"); 
        exit;
    }

    if ($result_vendas->num_rows > 0) {
        while ($linha = $result_vendas->fetch_assoc()) {
            echo '<section id="lista-elementos">';
            echo '<div class="elementos-lista">' . $linha["id_venda"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["produto_venda"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["id_venda"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["tipo_pagamento"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["data_venda"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["hora_venda"] . '</div>';
            echo '<div class="icons">';
            
            // EXCLUIR
            echo '<form method="POST" style="display:inline-block;" onsubmit="return confirmarExclusao();">';
            echo '<input type="hidden" name="delete_id" value="' . $linha["id_venda"] . '">';
            echo '<button type="submit" style="background:none; border:none;">';
            echo '<i class="fa-solid fa-trash" style="color: red;"></i>';
            echo '</button>';
            echo '</form>';
            
            // EDITAR
            echo '<a href="editar.php?id=' . $linha["id_venda"] . '"><i class="fa-solid fa-pen-to-square"></i></a>';
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
    </script>
</body>
</html>