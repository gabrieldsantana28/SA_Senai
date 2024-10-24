<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/estoque.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Gerenciamento de estoque</title>
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
            <a href="funcionarios.php">Gerenciamento de Funcionários</a>
        </div>
    </header>
    
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('menuAdm.php')"></i>
    </div>   
    
    <section id="Titulo-Principal"><h1>Gerenciamento de Estoque</h1></section>

    <section style="margin-bottom: 30px;">
        <div style="margin: auto;" class="elementos--itens">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="PesquisarProduto" name="PesquisarProduto" placeholder="Pesquisar Produto...">
        </div>
    </section>

    <section id="container-elementos">
        <div class="elementos">ID</div>
        <div class="elementos">NOME DO PRODUTO</div>
        <div class="elementos">QUANTIDADE</div>
        <div class="elementos">CUSTO</div>
        <div class="elementos">DESCRIÇÃO DO PRODUTO</div>
    </section>

<?php
    $conexao = new mysqli("localhost", "root", "", "nossasa");

    if ($conexao->connect_errno) {
        echo "Ocorreu um erro de conexão com o banco de dados";
        exit;
    }

    $conexao->set_charset("utf8");

    if (isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        $sql_delete = "DELETE FROM produto WHERE id_produto = $id";
        $conexao->query($sql_delete);
        header("Location: estoque.php");
        exit;
    }

    $sql = "SELECT id_produto, nome_produto, quantidade, preco, descricao_produto FROM produto;";
    $result = $conexao->query($sql);

    if ($result->num_rows > 0) {
        while ($linha = $result->fetch_assoc()) {
            echo '<section id="lista-elementos">';
            echo '<div class="elementos-lista">' . $linha["id_produto"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["nome_produto"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["quantidade"] . '</div>';
            echo '<div class="elementos-lista">' . "R$ " . number_format($linha["preco"], 2, ',', '.') . '</div>';
            echo '<div class="elementos-lista">' . $linha["descricao_produto"] . '</div>';
            echo '<div class="icons">';

            // EXCLUIR
            echo '<form method="POST" style="display:inline-block;" onsubmit="return confirmarExclusao();">';
            echo '<input type="hidden" name="delete_id" value="' . $linha["id_produto"] . '">';
            echo '<button type="submit" style="background:none; border:none;">';
            echo '<i class="fa-solid fa-trash" style="color: red;"></i>';
            echo '</button>';
            echo '</form>';
            // EDITAR
            echo '<a href="editar.php?id=' . $linha["id_produto"] . '"><i class="fa-solid fa-pen-to-square"></i></a>';
            echo '</div>';
            echo '</section>';
        }
    } else {
        echo "Sem resultados";
    }

    $conexao->close();
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
