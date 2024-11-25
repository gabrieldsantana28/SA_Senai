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
        <!-- Campo oculto para armazenar o ID da compra -->
        <input type="hidden" name="id" value="<?php echo $compra['id_compra']; ?>">
        <!-- Campos do formulário -->
        <div class="form-group">
            <label for="produto">Produto</label>
            <input type="text" id="produto" name="produto" value="<?php echo $compra['produto_compra']; ?>" required>
        </div>
        <!-- Outros campos seguem o mesmo padrão -->
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
