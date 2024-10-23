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
            <a href="menuAdm.html">Menu ADM</a>
            <a href="menuFuncionario.html">Menu Funcionário</a>
            <a href="fornecedores.html">Gerenciamento de Fornecedores</a>
            <a href="cadastroprodutos.php">Cadastro de Produtos</a>
            <a href="funcionarios.html">Gerenciamento de Funcionários</a>
        </div>
    </header>
    
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('menuAdm.html')"></i>
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
// Conexão com o banco de dados
$conexao = new mysqli("localhost", "root", "", "nossasa");

// Verifica se houve erro de conexão
if ($conexao->connect_errno) {
    echo "Ocorreu um erro de conexão com o banco de dados";
    exit;
}

// Define o charset da conexão
$conexao->set_charset("utf8");

// Deletar item (se o botão de excluir for clicado)
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $sql_delete = "DELETE FROM produto WHERE id = $id";
    $conexao->query($sql_delete);
    header("Location: estoque.php"); // Redireciona para a página principal
    exit;
}

// Consulta SQL para exibir produtos
$sql = "SELECT id, nome, quantidade, preco, descricao FROM produto;";
$result = $conexao->query($sql);

// Verifica se há resultados
if ($result->num_rows > 0) {
    // Loop pelos resultados
    while ($linha = $result->fetch_assoc()) {
        echo '<section id="lista-elementos">';
        echo '<div class="elementos-lista">' . $linha["id"] . '</div>';
        echo '<div class="elementos-lista">' . $linha["nome"] . '</div>';
        echo '<div class="elementos-lista">' . $linha["quantidade"] . '</div>';
        echo '<div class="elementos-lista">' . number_format($linha["preco"], 2, ',', '.') . '</div>';
        echo '<div class="elementos-lista">' . $linha["descricao"] . '</div>';
        echo '<div class="icons">';
        // Formulário para excluir com confirmação
        echo '<form method="POST" style="display:inline-block;" onsubmit="return confirmarExclusao();">';
        echo '<input type="hidden" name="delete_id" value="' . $linha["id"] . '">';
        echo '<button type="submit" style="background:none; border:none;">';
        echo '<i class="fa-solid fa-trash" style="color: red;"></i>';
        echo '</button>';
        echo '</form>';
        // Link para editar
        echo '<a href="editar.php?id=' . $linha["id"] . '"><i class="fa-solid fa-pen-to-square"></i></a>';
        echo '</div>';
        echo '</section>';
    }
} else {
    echo "Sem resultados";
}

// Fecha a conexão
$conexao->close();
?>

<!-- Script de confirmação de exclusão -->
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
