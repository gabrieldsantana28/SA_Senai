<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nossasa";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$message = "";

$sql_produtos = "SELECT id_produto, nome_produto, quantidade_produto FROM produto";
$result_produtos = $conn->query($sql_produtos);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipoPagamento = $_POST['tipo'];
    $quantidade = $_POST['quantidade'];
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $produto_id = $_POST['produto'];

    // Verifica a quantidade disponível do produto
    $sql_estoque = "SELECT quantidade_produto FROM produto WHERE id_produto = ?";
    $stmt_estoque = $conn->prepare($sql_estoque);
    $stmt_estoque->bind_param("i", $produto_id);
    $stmt_estoque->execute();
    $stmt_estoque->bind_result($quantidade_estoque);
    $stmt_estoque->fetch();
    $stmt_estoque->close();

    if ($quantidade > $quantidade_estoque) {
        $message = "Erro: A quantidade solicitada excede o estoque disponível.";
    } else {
        $sql_venda = "INSERT INTO venda (tipo_pagamento_venda, quantidade_venda, data_venda, hora_venda, fk_id_produto) VALUES (?, ?, ?, ?, ?)";
        $stmt_venda = $conn->prepare($sql_venda);
        $stmt_venda->bind_param("ssssi", $tipoPagamento, $quantidade, $data, $horario, $produto_id);

        if ($stmt_venda->execute()) {
            $nova_quantidade = $quantidade_estoque - $quantidade;
            $sql_atualiza_estoque = "UPDATE produto SET quantidade_produto = ? WHERE id_produto = ?";
            $stmt_atualiza = $conn->prepare($sql_atualiza_estoque);
            $stmt_atualiza->bind_param("ii", $nova_quantidade, $produto_id);
            $stmt_atualiza->execute();
            $stmt_atualiza->close();
            $message = "Venda cadastrada com sucesso!";
        } else {
            $message = "Erro ao cadastrar a venda.";
        }
        $stmt_venda->close();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cadastrovendas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Cadastrar Venda</title>
</head>
<body>
<header>
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <a href="estoque.php">Estoque</a>
        <a href="funcionarios.php">Funcionários</a>
        <a href="fornecedores.php">Fornecedores</a>
        <a href="cadastroprodutos.php">CadasProdutos</a>
        <a href="compras.php">Compras</a>
        <a href="relatorio.php">Relatórios</a>
    </div>
</header>

<div class="botao--voltar">
    <i class="fa-solid fa-arrow-left" onclick="trocarPagina('vendas.php')"></i>
</div>

<section id="Titulo-Principal">
    <h1>Cadastrar Venda</h1>
</section>

<?php if ($message): ?>
    <div class="message">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div id="container-main">
    <form method="POST" action="">
        <div class="form-group elementos--itens">
            <label for="produto">Produto:</label>
            <select id="produto" name="produto" required>
                <option value="">Selecione um produto</option>
                <?php while ($linha = $result_produtos->fetch_assoc()): ?>
                    <option value="<?php echo $linha['id_produto']; ?>">
                        <?php echo htmlspecialchars($linha['nome_produto']) . " - Estoque: " . $linha['quantidade_produto']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group elementos--itens">
            <label for="quantidade">Quantidade:</label>
            <input type="number" id="quantidade" name="quantidade" min="1" required>
        </div>
        <div class="form-group elementos--itens">
            <label for="tipo">Tipo de Pagamento:</label>
            <input type="text" id="tipo" name="tipo" required>
        </div>
        <div class="form-group elementos--itens">
            <label for="data">Data:</label>
            <input type="date" id="data" name="data" required>
        </div>
        <div class="form-group elementos--itens">
            <label for="horario">Horário:</label>
            <input type="time" id="horario" name="horario" required>
        </div>
        <div class="button">
            <button type="submit">Cadastrar Venda</button>
        </div>
    </form>
</div>

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
