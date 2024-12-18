<?php
    // Inicia a sessão para gerenciar variáveis de sessão
    session_start();

    // Configuração da conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gerenciador_estoque";

    // Cria a conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica se a conexão foi bem-sucedida
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $conn->set_charset("utf8");

    // Verifica se o ID foi passado via URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Prepara a consulta para buscar os dados da venda
        $sql = "SELECT * FROM venda WHERE id_venda = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id); // Define o tipo do parâmetro como inteiro
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se a venda foi encontrada
        if ($result->num_rows > 0) {
            $venda = $result->fetch_assoc(); // Recupera os dados da venda
        } else {
            echo "Venda não encontrada.";
            exit; // Encerra o script caso a venda não seja encontrada
        }
    }

    // Verifica se o formulário foi enviado para atualizar os dados
    if (isset($_POST['update'])) {
        // Recupera os valores enviados pelo formulário
        $id = $_POST['id'];
        $quantidade_venda = $_POST['quantidade'];
        $tipo_pagamento = $_POST['tipo_pagamento'];
        $data_venda = $_POST['data_venda'];
        $hora_venda = $_POST['hora_venda'];

        // Recupera o ID do produto associado à venda
        $id_produto = $venda['fk_id_produto'];
        // Recupera a quantidade original da venda antes da edição
        $quantidade_original = $venda['quantidade_venda'];

        // Calcula a diferença entre a nova e a antiga quantidade vendida
        $diferenca_quantidade = $quantidade_venda - $quantidade_original;

        // Atualiza os dados da venda no banco
        $sql_update = "UPDATE venda SET quantidade_venda=?, tipo_pagamento_venda=?, data_venda=?, hora_venda=? WHERE id_venda=?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("isssi", $quantidade_venda, $tipo_pagamento, $data_venda, $hora_venda, $id);
        $stmt->execute();

        // Atualiza a quantidade do produto no estoque com base na diferença
        if ($diferenca_quantidade != 0) {
            $sql_estoque = "UPDATE produto SET quantidade_produto = quantidade_produto - ? WHERE id_produto = ?";
            $stmt_estoque = $conn->prepare($sql_estoque);
            $stmt_estoque->bind_param("ii", abs($diferenca_quantidade), $id_produto);

            // Verifica se a diferença é positiva (menos produto no estoque) ou negativa (mais produto no estoque)
            if ($diferenca_quantidade > 0) {
                $stmt_estoque->execute(); // Diminui o estoque
            } elseif ($diferenca_quantidade < 0) {
                $stmt_estoque->execute(); // Aumenta o estoque
            }
        }

        // Redireciona para a página de vendas após a atualização
        header("Location: vendas.php");
        exit;
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <!-- Folhas de estilo -->
    <link rel="stylesheet" href="css/vendas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Editar Venda</title>
</head>
<body>
<header>
    <!-- Cabeçalho com links para diferentes páginas -->
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($_SESSION['nivel'] == 1): // Apenas administradores têm acesso a estas opções ?>
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>

<!-- Botão de voltar -->
<div class="botao--voltar">
    <i class="fa-solid fa-arrow-left" onclick="trocarPagina('vendas.php')"></i>
</div>   

<!-- Título principal -->
<section id="Titulo-Principal">
    <h1>Editar Venda</h1>
</section>

<!-- Formulário para edição da venda -->
<section class="formulario-editar">
    <form method="POST">
        <!-- Campo oculto para armazenar o ID da venda -->
        <input type="hidden" name="id" value="<?php echo $venda['id_venda']; ?>">
        
        <div class="form-group">
            <label for="quantidade">Quantidade</label>
            <input type="number" id="quantidade" name="quantidade" value="<?php echo $venda['quantidade_venda']; ?>" required>
        </div>

        <div class="form-group">
            <label for="tipo_pagamento">Tipo de Pagamento</label>
            <select id="tipo_pagamento" name="tipo_pagamento" required> <!-- Seleção de tipo de pagamento -->
                <option value="" disabled selected>Selecione o Tipo de Pagamento...</option> <!-- Opção padrão -->
                <option value="Dinheiro/À vista">Dinheiro/À vista</option> <!-- Opção de pagamento à vista -->
                <option value="PIX">PIX</option> <!-- Opção de pagamento via PIX -->
                <option value="Crédito">Crédito</option> <!-- Opção de pagamento parcelado -->
                <option value="Débito">Débito</option> <!-- Opção de pagamento por débito -->
                <option value="Boleto">Boleto</option> <!-- Opção de pagamento por boleto -->
            </select>        
        </div>

        <div class="form-group">
            <label for="data_venda">Data</label>
            <input type="date" id="data_venda" name="data_venda" value="<?php echo $venda['data_venda']; ?>" required>
        </div>

        <div class="form-group">
            <label for="hora_venda">Hora</label>
            <input type="time" id="hora_venda" name="hora_venda" value="<?php echo $venda['hora_venda']; ?>" required>
        </div>

        <!-- Botão para salvar as alterações -->
        <button type="submit" name="update" class="botao-salvar">Salvar Alterações</button>
    </form>
</section>

<script>
// Função para redirecionar para uma página específica
function trocarPagina(url) {
    window.location.href = url;
}

// Função para redirecionar o usuário ao menu apropriado
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
     /* CSS Interno */
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

    .form-group input,
    .form-group select {
        width: 95%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1em;
        background-color: #fff; 
        color: #333; 
    }

    .form-group select:focus { 
        outline: none;
        border-color: #000; 
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2); 
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