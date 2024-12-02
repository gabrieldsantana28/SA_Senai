<?php
// Conexão com o banco de dados MySQL
$conexao = new mysqli("localhost", "root", "", "gerenciador_estoque");

// Verifica se houve algum erro na conexão
if ($conexao->connect_errno) {
    echo "Ocorreu um erro de conexão com o banco de dados";
    exit; // Encerra o script caso haja erro na conexão
}

// Define o conjunto de caracteres para evitar problemas com acentuação
$conexao->set_charset("utf8");

// Verifica se um ID foi passado pela URL (para editar um fornecedor específico)
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Captura o ID da URL

    // Consulta para buscar os dados do fornecedor baseado no ID
    $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = $id";
    $result = $conexao->query($sql);

    // Verifica se o fornecedor existe
    if ($result->num_rows > 0) {
        $fornecedor = $result->fetch_assoc(); // Armazena os dados do fornecedor em um array
    }
}

// Verifica se o formulário foi submetido para atualizar os dados
if (isset($_POST['update'])) {
    $id = $_POST['id']; // ID do fornecedor a ser atualizado
    $nome = $_POST['nome']; // Novo nome do fornecedor
    $material = $_POST['material']; // Novo material fornecido
    $telefone = $_POST['telefone']; // Novo telefone
    $endereco = $_POST['endereco']; // Novo endereço

    // Atualiza os dados do fornecedor no banco de dados
    $sql_update = "UPDATE fornecedor SET nome_fornecedor='$nome', material_fornecedor='$material', telefone_fornecedor='$telefone', endereco_fornecedor='$endereco' WHERE id_fornecedor=$id";
    $conexao->query($sql_update); // Executa a query de atualização

    // Redireciona para a página de listagem de fornecedores após a atualização
    header("Location: fornecedores.php");
    exit; // Encerra o script para evitar execução adicional
}

// Fecha a conexão com o banco de dados
$conexao->close();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Estilos externos e internos -->
    <link rel="stylesheet" href="css/cadastroprodutos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Editar Fornecedor</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            font-family: 'Poppins', sans-serif;
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
</head>
<body>
<header>
    <!-- Cabeçalho com links de navegação -->
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($_SESSION['nivel'] == 1): // Apenas admin pode ver estas opções ?>
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>

<!-- Botão para voltar -->
<div class="botao--voltar">
    <i class="fa-solid fa-arrow-left" onclick="trocarPagina('fornecedores.php')" aria-label="Voltar"></i>
</div>   

<main id="container-main">
    <!-- Título principal -->
    <section id="Titulo-Principal"><h1>Editar Fornecedor</h1></section>

    <!-- Formulário de edição -->
    <form method="POST" class="formulario-editar">
        <!-- Campo oculto para passar o ID -->
        <input type="hidden" name="id" value="<?php echo $fornecedor['id_fornecedor']; ?>">
        <div class="form-group">
            <label>Nome do Fornecedor:</label>
            <input type="text" name="nome" value="<?php echo $fornecedor['nome_fornecedor']; ?>" required>
        </div>
        <div class="form-group">
            <label>Material Fornecido:</label>
            <input type="text" name="material" value="<?php echo $fornecedor['material_fornecedor']; ?>" required>
        </div>
        <div class="form-group">
            <label>Telefone:</label>
            <input type="text" name="telefone" value="<?php echo $fornecedor['telefone_fornecedor']; ?>" required>
        </div>
        <div class="form-group">
            <label>Endereço:</label>
            <input type="text" name="endereco" value="<?php echo $fornecedor['endereco_fornecedor']; ?>" required>
        </div>
        <!-- Botão para atualizar -->
        <button type="submit" name="update" class="botao-salvar">Atualizar</button>
    </form>
</main>

<script>
    // Função para trocar de página
    function trocarPagina(url) {
        window.location.href = url;
    }

    // Função para retornar ao menu com base no nível do usuário
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
            window.location.href = 'index.php';
        }
    }
</script>
</body>
</html>
