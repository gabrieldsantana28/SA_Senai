<?php 
session_start();

// VERIFICAÇÃO CONEXÃO COM O BANCO DE DADOS
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nossasa";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// RECUPERA NÍVEL DA CONTA 
$nivel = $_SESSION['nivel'] ?? 0; // NÍVEL DA CONTA EM 0 CASO NÃO ESTEJA LOGADO

$pesquisa = $_GET['PesquisarProduto'] ?? ''; // Obtém o termo de pesquisa

// Preparar a consulta de busca
$sql = "SELECT id_produto, nome_produto, quantidade_produto, preco_produto, descricao_produto FROM produto WHERE nome_produto LIKE ? OR id_produto LIKE ?";
$stmt = $conn->prepare($sql);
$likePesquisa = "%" . $conn->real_escape_string($pesquisa) . "%";
$stmt->bind_param("ss", $likePesquisa, $likePesquisa);
$stmt->execute();
$result = $stmt->get_result();

if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];

    // Excluir as vendas relacionadas ao produto
    $sql_delete_venda = "DELETE FROM venda WHERE fk_id_produto = ?";
    $stmt_delete_venda = $conn->prepare($sql_delete_venda);
    $stmt_delete_venda->bind_param("i", $id);
    $stmt_delete_venda->execute();

    // Agora excluir o produto
    $sql_delete_produto = "DELETE FROM produto WHERE id_produto = ?";
    $stmt_delete_produto = $conn->prepare($sql_delete_produto);
    $stmt_delete_produto->bind_param("i", $id);
    $stmt_delete_produto->execute();

    header("Location: estoque.php"); // Redireciona após exclusão
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/estoque.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Gerenciamento de Estoque</title>
</head>
<body>
<header>
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
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
        <i class="fa-solid fa-arrow-left" onclick="voltarMenu()"></i>
    </div>   
    
    <section id="Titulo-Principal"><h1>Gerenciamento de Estoque</h1></section>

    <section style="margin-bottom: 30px;">
        <div style="margin: auto;" class="elementos--itens">
            <i class="fa-solid fa-magnifying-glass"></i>
            <form method="GET" action="">
                <input type="text" id="PesquisarProduto" name="PesquisarProduto" placeholder="Pesquisar Produto..." value="<?php echo htmlspecialchars($pesquisa); ?>">
            </form>
            <a href="/GitHub/SA_Senai/cadastroprodutos.php" class="icon-btn">
                <i class="fa-solid fa-plus"></i>
            </a>
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
    if ($result->num_rows > 0) {
        while ($linha = $result->fetch_assoc()) {
            echo '<section id="lista-elementos">';
            echo '<div class="elementos-lista">' . $linha["id_produto"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["nome_produto"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["quantidade_produto"] . '</div>';
            echo '<div class="elementos-lista">' . "R$ " . number_format($linha["preco_produto"], 2, ',', '.') . '</div>';
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
        echo '<div style="text-align:center">Nenhum produto encontrado.</div>';
    }

    $stmt->close();
    $conn->close();
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
