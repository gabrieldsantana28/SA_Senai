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

// Verifica se o ID foi passado
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Busca os dados da venda para editar
    $sql = "SELECT * FROM venda WHERE id_venda = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $venda = $result->fetch_assoc();
    } else {
        echo "Venda não encontrada.";
        exit;
    }
}

// Atualiza os dados da venda
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $produto = $_POST['produto'];
    $quantidade = $_POST['quantidade'];
    $tipo_pagamento = $_POST['tipo_pagamento'];
    $data_venda = $_POST['data_venda'];
    $hora_venda = $_POST['hora_venda'];

    $sql_update = "UPDATE venda SET produto_venda=?, quantidade=?, tipo_pagamento=?, data_venda=?, hora_venda=? WHERE id_venda=?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("siissi", $produto, $quantidade, $tipo_pagamento, $data_venda, $hora_venda, $id);
    $stmt->execute();

    header("Location: vendas.php");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/vendas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Editar Venda</title>
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
            <a href="compras.php">Compras</a>
            <a href="relatorio.php">Relatórios</a>
        </div>
    </header>

    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('vendas.php')"></i>
    </div>   

    <section id="Titulo-Principal">
        <h1>Editar Venda</h1>
    </section>

    <section class="formulario-editar">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $venda['id_venda']; ?>">
            <div class="form-group">
                <label for="produto">Produto</label>
                <input type="text" id="produto" name="produto" value="<?php echo $venda['produto_venda']; ?>" required>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" value="<?php echo $venda['quantidade_venda']; ?>" required>
            </div>
            <div class="form-group">
                <label for="tipo_pagamento">Tipo de Pagamento</label>
                <input type="text" id="tipo_pagamento" name="tipo_pagamento" value="<?php echo $venda['tipo_pagamento_venda']; ?>" required>
            </div>
            <div class="form-group">
                <label for="data_venda">Data</label>
                <input type="date" id="data_venda" name="data_venda" value="<?php echo $venda['data_venda']; ?>" required>
            </div>
            <div class="form-group">
                <label for="hora_venda">Hora</label>
                <input type="time" id="hora_venda" name="hora_venda" value="<?php echo $venda['hora_venda']; ?>" required>
            </div>
            <button type="submit" name="update" class="botao-salvar">Salvar Alterações</button>
        </form>
    </section>

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
    height: 100px;
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

.form-group input {
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
