<?php
// Conexão com o banco de dados
$conexao = new mysqli("localhost", "root", "", "nossasa");

// Verifica se houve erro de conexão
if ($conexao->connect_errno) {
    echo "Ocorreu um erro de conexão com o banco de dados";
    exit;
}

$conexao->set_charset("utf8");

// Verifica se o ID foi passado
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Busca os dados do produto para editar
    $sql = "SELECT * FROM produto WHERE id = $id";
    $result = $conexao->query($sql);

    if ($result->num_rows > 0) {
        $produto = $result->fetch_assoc();
    } else {
        echo "Produto não encontrado.";
        exit;
    }
}

// Atualiza os dados do produto
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];

    $sql_update = "UPDATE produto SET nome='$nome', quantidade='$quantidade', preco='$preco', descricao='$descricao' WHERE id=$id";
    $conexao->query($sql_update);

    header("Location: estoque.php"); // Redireciona para a página principal
    exit;
}

$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/estoque.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Editar Produto</title>
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
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('estoque.php')"></i>
    </div>   

    <section id="Titulo-Principal"><h1>Editar Produto</h1></section>

    <section class="formulario-editar">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
            <div class="form-group">
                <label for="nome_produto">Nome do Produto</label>
                <input type="text" id="nome_produto" name="nome" value="<?php echo $produto['nome']; ?>" required>
            </div>
            <div class="form-group">
                <label for="quantidade_produto">Quantidade</label>
                <input type="number" id="quantidade_produto" name="quantidade" value="<?php echo $produto['quantidade']; ?>" required>
            </div>
            <div class="form-group">
                <label for="preco_produto">Preço</label>
                <input type="text" id="preco_produto" name="preco" value="<?php echo $produto['preco']; ?>" required>
            </div>
            <div class="form-group">
                <label for="descricao_produto">Descrição</label>
                <textarea id="descricao_produto" name="descricao" rows="4" required><?php echo $produto['descricao']; ?></textarea>
            </div>
            <button type="submit" name="update" class="botao-salvar">Salvar Alterações</button>
        </form>
    </section>

    <script>
        function trocarPagina(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>

<style>
* {
    padding: 0;
    margin: 0;
    font-family: Poppins, sans-serif;
}

body {
    background-color: #f4f4f4;
}

.hdr {
    background-color: black;
    height: 110px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 10px;
}

.hdr a {
    color: white;
    text-decoration: none;
    margin: 0 5px;
}

.logo-header {
    height: 80px;
}

.botao--voltar {
    font-size: 2.1em;
    margin-left: 16px;
    cursor: pointer;
}

#Titulo-Principal {
    text-align: center;
    padding-top: 0;
    font-size: 1.4em;
    margin-bottom: 15px;
}

.formulario-editar {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.botao-salvar {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: black;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1.1em;
}

.botao-salvar:hover {
    background-color: #333;
}
</style>
