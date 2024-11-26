<?php
    session_start();
    // Inicia a sessão para gerenciar variáveis de sessão
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gerenciador_estoque";

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Verifica se o ID foi passado
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Busca os dados da compra para editar
        $sql = "SELECT * FROM compra WHERE id_compra = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se a compra foi encontrada
        if ($result->num_rows > 0) {
            $compra = $result->fetch_assoc();
        } else {
            echo "Compra não encontrada.";
            exit;
        }
    }

    // Atualiza os dados da compra
    if (isset($_POST['update'])) {
        // Recupera os valores enviados pelo formulário
        $id = $_POST['id'];
        $produto_compra = $_POST['produto'];
        $quantidade_compra = $_POST['quantidade'];
        $preco_compra = $_POST['preco'];
        $tipo_pagamento = $_POST['tipo_pagamento'];
        $data_compra = $_POST['data_compra'];
        $hora_compra = $_POST['hora_compra'];

        // Atualiza a compra no banco de dados
        $sql_update = "UPDATE compra SET produto_compra=?, quantidade_compra=?, preco_compra=?, tipo_pagamento_compra=?, data_compra=?, hora_compra=? WHERE id_compra=?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("sidsssi", $produto_compra, $quantidade_compra, $preco_compra, $tipo_pagamento, $data_compra, $hora_compra, $id);
        $stmt->execute();

        // Redireciona de volta para a página de compras
        header("Location: compras.php");
        exit;
    }

    $conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Metadados e links para CSS e fontes -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/compras.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Editar Compra</title>
</head>
<body>
<header>
    <!-- Cabeçalho com links de navegação -->
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($_SESSION['nivel'] == 1): // Exibe apenas se o nível for de administrador. ?>
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>

<!-- Botão de voltar -->
<div class="botao--voltar">
    <i class="fa-solid fa-arrow-left" onclick="trocarPagina('compras.php')"></i>
</div>

<!-- Título da página -->
<section id="Titulo-Principal">
    <h1>Editar Compra</h1>
</section>

<!-- Formulário de edição -->
<section class="formulario-editar">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $compra['id_compra']; ?>">
            <div class="form-group">
                <label for="produto">Produto</label>
                <input type="text" id="produto" name="produto" value="<?php echo $compra['produto_compra']; ?>" required>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" value="<?php echo $compra['quantidade_compra']; ?>" required>
            </div>
            <div class="form-group">
                <label for="preco">Preço</label>
                <input type="number" step="0.01" id="preco" name="preco" value="<?php echo $compra['preco_compra']; ?>" required>
            </div>
            <div class="form-group">
                <label for="tipo_pagamento">Tipo de Pagamento</label>
                <input type="text" id="tipo_pagamento" name="tipo_pagamento" value="<?php echo $compra['tipo_pagamento_compra']; ?>" required>
            </div>
            <div class="form-group">
                <label for="data_compra">Data</label>
                <input type="date" id="data_compra" name="data_compra" value="<?php echo $compra['data_compra']; ?>" required>
            </div>
            <div class="form-group">
                <label for="hora_compra">Hora</label>
                <input type="time" id="hora_compra" name="hora_compra" value="<?php echo $compra['hora_compra']; ?>" required>
            </div>
            <button type="submit" name="update" class="botao-salvar">Salvar Alterações</button>
        </form>
    </section>


<script>
    // Função para trocar de página.
    function trocarPagina(url) {
        window.location.href = url;
    }

    // Função para retornar ao menu com base no nível do usuário.
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
    width: 95%;
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
