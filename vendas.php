<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gerenciador_estoque";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        $sql_delete = "DELETE FROM venda WHERE id_venda = ?";
        
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $delete_id);
        
        if ($stmt_delete->execute()) {
            echo "<script>alert('Venda excluída com sucesso!'); window.location.href='vendas.php';</script>";
        } else {
            echo "<script>alert('Erro ao excluir a venda. Tente novamente.');</script>";
        }
        $stmt_delete->close();
    }

    $nivel = $_SESSION['nivel'] ?? 0; 
    $pesquisa = $_GET['PesquisarVenda'] ?? ''; 

    $sql_vendas = "
        SELECT 
            v.id_venda, 
            v.quantidade_venda, 
            v.tipo_pagamento_venda, 
            DATE_FORMAT(v.data_venda, '%d/%m/%Y') AS data_venda,
            v.hora_venda, 
            p.nome_produto,
            p.preco_produto, 
            (v.quantidade_venda * p.preco_produto) AS total_preco 
        FROM 
            venda v 
        INNER JOIN 
            produto p ON v.fk_id_produto = p.id_produto
        WHERE 
            p.nome_produto LIKE ? OR v.tipo_pagamento_venda LIKE ? OR v.id_venda LIKE ?;
    ";
    $stmt = $conn->prepare($sql_vendas);
    $likePesquisa = "%" . $pesquisa . "%";
    $stmt->bind_param("sss", $likePesquisa, $likePesquisa, $likePesquisa);
    $stmt->execute();
    $result_vendas = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/vendas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Gerenciamento de Vendas</title>
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
    </div>
</header>

<div class="botao--voltar">
    <i class="fa-solid fa-arrow-left" onclick="voltarMenu()"></i>
</div>

<section id="Titulo-Principal">
    <h1>Controle de Vendas</h1>
</section>

<section style="margin-bottom: 30px;">
    <div style="margin: auto;" class="elementos--itens">
        <i class="fa-solid fa-magnifying-glass"></i>
        <form method="GET" action="">
            <input type="text" id="PesquisarVenda" name="PesquisarVenda" placeholder="Pesquisar Venda..." value="<?php echo htmlspecialchars($pesquisa); ?>" onkeypress="if(event.key === 'Enter') { this.form.submit(); }">
        </form>
        <a href="/GitHub/SA_Senai/cadastrovendas.php" class="icon-btn">
            <i class="fa-solid fa-plus"></i>
        </a>
    </div>
</section>

<section id="container-elementos">
    <div class="elementos">N° VENDA</div>
    <div class="elementos">PRODUTO</div>
    <div class="elementos">QUANTIDADE</div>
    <div class="elementos">TIPO - PAGAMENTO</div>
    <div class="elementos">DATA/HORA</div>
    <div class="elementos">PREÇO TOTAL</div>
</section>

<?php
if ($result_vendas->num_rows > 0) {
    while ($linha = $result_vendas->fetch_assoc()) {
        echo '<section id="lista-elementos">';
        echo '<div class="elementos-lista">' . $linha["id_venda"] . '</div>';
        echo '<div class="elementos-lista">' . $linha["nome_produto"] . '</div>';
        echo '<div class="elementos-lista">' . $linha["quantidade_venda"] . '</div>';
        echo '<div class="elementos-lista">' . $linha["tipo_pagamento_venda"] . '</div>';
        echo '<div class="elementos-lista">' . $linha["data_venda"] . " - " . $linha["hora_venda"] . '</div>';
        echo '<div class="elementos-lista">' . number_format($linha["total_preco"], 2, ',', '.') . '</div>';
        echo '<div class="icons">';
        // EXCLUIR
        echo '<form method="POST" style="display:inline-block;" onsubmit="return confirmarExclusao();">';
        echo '<input type="hidden" name="delete_id" value="' . $linha["id_venda"] . '">';
        echo '<button type="submit"><i class="fa-solid fa-trash" style="color: red;"></i></button>';
        echo '</form>';
        // EDITAR
        echo '<a href="editarvendas.php?id=' . $linha["id_venda"] . '"><i class="fa-solid fa-pen-to-square"></i></a>';
        echo '</div>';
        echo '</section>';
    }
} else {
    echo '<div style="text-align:center">Nenhuma venda encontrada.</div>';
}
?>

<script>
function confirmarExclusao() {
    return confirm("Você realmente deseja apagar este item?");
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
