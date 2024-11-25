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

$nivel = $_SESSION['nivel'] ?? 0;
$pesquisa = $_GET['PesquisarCompra'] ?? ''; 

// Alterando a consulta SQL para pesquisar por ID da compra, produto_compra ou nome do fornecedor
$sql_compras = "
    SELECT 
        c.id_compra, 
        c.quantidade_compra, 
        c.tipo_pagamento_compra, 
        DATE_FORMAT(c.data_compra, '%d/%m/%Y') AS data_compra,
        c.hora_compra, 
        f.nome_fornecedor, 
        c.produto_compra, 
        c.preco_compra, 
        (c.quantidade_compra * c.preco_compra) AS total_preco 
    FROM 
        compra c
    INNER JOIN 
        fornecedor f ON c.fk_id_fornecedor = f.id_fornecedor
    WHERE 
        c.id_compra LIKE ? OR c.produto_compra LIKE ? OR f.nome_fornecedor LIKE ?;
";

$stmt = $conn->prepare($sql_compras);
$likePesquisa = "%" . $pesquisa . "%";
$stmt->bind_param("sss", $likePesquisa, $likePesquisa, $likePesquisa);
$stmt->execute();
$result_compras = $stmt->get_result();
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
    <title>Gerenciamento de Compras</title>
</head>
<body>
<header>
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($_SESSION['nivel'] == 1): ?>
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="vendas.php">Vendas</a>
    </div>
</header>

<div class="botao--voltar">
    <i class="fa-solid fa-arrow-left" onclick="voltarMenu()"></i>
</div>

<section id="Titulo-Principal">
    <h1>Controle de Compras</h1>
</section>

<section style="margin-bottom: 30px;">
    <div style="margin: auto;" class="elementos--itens">
        <form method="GET" action="">
            <button type="submit" style="background: none; border: none; cursor: pointer;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            <input type="text" id="PesquisarCompra" name="PesquisarCompra" placeholder="Pesquisar Compra..." value="<?php echo htmlspecialchars($pesquisa); ?>" onkeypress="if(event.key === 'Enter') { this.form.submit(); }">
        </form>
        <a href="/GitHub/SA_Senai/cadastrocompras.php" class="icon-btn">
            <i class="fa-solid fa-plus"></i>
        </a>
    </div>
</section>

<section id="container-elementos">
    <div class="elementos">N° COMPRA</div>
    <div class="elementos">FORNECEDOR</div>
    <div class="elementos">PRODUTO</div>
    <div class="elementos">QUANTIDADE</div>
    <div class="elementos">TIPO - PAGAMENTO</div>
    <div class="elementos">DATA/HORA</div>
    <div class="elementos">PREÇO TOTAL</div>
</section>

<?php
if ($result_compras->num_rows > 0) {
    while ($linha = $result_compras->fetch_assoc()) {
        echo '<section id="lista-elementos">';
        echo '<div class="elementos-lista">' . $linha["id_compra"] . '</div>';
        echo '<div class="elementos-lista">' . $linha["nome_fornecedor"] . '</div>';
        echo '<div class="elementos-lista">' . $linha["produto_compra"] . '</div>';
        echo '<div class="elementos-lista">' . $linha["quantidade_compra"] . '</div>';
        echo '<div class="elementos-lista">' . $linha["tipo_pagamento_compra"] . '</div>';
        echo '<div class="elementos-lista">' . $linha["data_compra"] . " - " . $linha["hora_compra"] . '</div>';
        echo '<div class="elementos-lista">' . number_format($linha["total_preco"], 2, ',', '.') . '</div>';
        echo '<div class="icons">';
        // EXCLUIR
        echo '<form method="POST" style="display:inline-block;" onsubmit="return confirmarExclusao();">';
        echo '<input type="hidden" name="delete_id" value="' . $linha["id_compra"] . '">';
        echo '<button type="submit"><i class="fa-solid fa-trash" style="color: red;"></i></button>';
        echo '</form>';
        // EDITAR
        echo '<a href="editarcompras.php?id=' . $linha["id_compra"] . '"><i class="fa-solid fa-pen-to-square"></i></a>';
        echo '</div>';
        echo '</section>';
    }
} else {
    echo '<div style="text-align:center">Nenhuma compra encontrada.</div>';
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
