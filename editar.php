<?php
    // Inicia a sessão para acessar variáveis globais do usuário
    session_start();

    // Conexão com o banco de dados
    $conexao = new mysqli("localhost", "root", "", "gerenciador_estoque");

    // Verifica se houve erro na conexão com o banco de dados
    if ($conexao->connect_errno) {
        echo "Ocorreu um erro de conexão com o banco de dados";
        exit; // Finaliza o script em caso de erro
    }

    // Define o conjunto de caracteres para UTF-8
    $conexao->set_charset("utf8");

    // Verifica se um ID foi enviado via GET para buscar os dados do produto
    if (isset($_GET['id'])) {
        $id = $_GET['id']; // Obtém o ID do produto

        // Consulta para buscar o produto correspondente
        $sql = "SELECT * FROM produto WHERE id_produto = $id";
        $result = $conexao->query($sql);

        // Verifica se o produto foi encontrado
        if ($result->num_rows > 0) {
            $produto = $result->fetch_assoc(); // Obtém os dados do produto
        } else {
            echo "Produto não encontrado."; // Mensagem de erro se o produto não existir
            exit; // Finaliza o script
        }
    }

    // Atualiza os dados do produto quando o formulário é enviado
    if (isset($_POST['update'])) {
        // Coleta os dados enviados via POST
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $quantidade = $_POST['quantidade'];
        $preco = $_POST['preco'];
        $descricao = $_POST['descricao'];
        $tamanho = $_POST['tamanho'];
        $cor = $_POST['cor'];

        // Consulta para atualizar o produto no banco de dados
        $sql_update = "UPDATE produto SET 
            nome_produto='$nome', 
            quantidade_produto='$quantidade', 
            preco_produto='$preco', 
            descricao_produto='$descricao', 
            tamanho_produto='$tamanho', 
            cor_produto='$cor' 
            WHERE id_produto=$id";

        // Executa a consulta de atualização
        $conexao->query($sql_update);

        // Redireciona para a página de estoque após a atualização
        header("Location: estoque.php"); 
        exit; // Finaliza o script
    }

    // Fecha a conexão com o banco de dados
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Editar Produto</title>
</head>
<body>
<header>
    <!-- Cabeçalho com links para navegação -->
    <div class="hdr">
        <!-- Logo que redireciona para o menu principal -->
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($_SESSION['nivel'] == 1): // Verifica se o usuário é admin ?>
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>

    <!-- Botão para voltar à página anterior -->
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('estoque.php')"></i>
    </div>   

    <!-- Título principal -->
    <section id="Titulo-Principal"><h1>Editar Produto</h1></section>

    <!-- Formulário de edição do produto -->
    <section class="formulario-editar">
        <form method="POST">
            <!-- Campo oculto para armazenar o ID do produto -->
            <input type="hidden" name="id" value="<?php echo $produto['id_produto']; ?>">
            
            <!-- Campo para o nome do produto -->
            <div class="form-group">
                <label for="nome_produto">Nome do Produto</label>
                <input type="text" id="nome_produto" name="nome" value="<?php echo $produto['nome_produto']; ?>" required>
            </div>
            
            <!-- Campo para a quantidade -->
            <div class="form-group">
                <label for="quantidade_produto">Quantidade</label>
                <input type="number" id="quantidade_produto" name="quantidade" value="<?php echo $produto['quantidade_produto']; ?>" required>
            </div>

            <!-- Campo para o tamanho -->
            <div class="form-group">
                <label for="tamanho_produto">Tamanho</label>
                <input type="text" id="tamanho_produto" name="tamanho" value="<?php echo $produto['tamanho_produto']; ?>" required>
            </div>

            <!-- Campo para a cor -->
            <div class="form-group">
                <label for="cor_produto">Cor</label>
                <input type="text" id="cor_produto" name="cor" value="<?php echo $produto['cor_produto']; ?>" required>
            </div>

            <!-- Campo para o preço -->
            <div class="form-group">
                <label for="preco_produto">Preço</label>
                <input type="text" id="preco_produto" name="preco" value="<?php echo $produto['preco_produto']; ?>" required>
            </div>

            <!-- Campo para a descrição -->
            <div class="form-group">
                <label for="descricao_produto">Descrição</label>
                <textarea id="descricao_produto" name="descricao" rows="4" required><?php echo $produto['descricao_produto']; ?></textarea>
            </div>

            <!-- Botão para salvar as alterações -->
            <button type="submit" name="update" class="botao-salvar">Salvar Alterações</button>
        </form>
    </section>

    <script>
        // Função para redirecionar o usuário para outra página
        function trocarPagina(url) {
            window.location.href = url;
        }

        // Função para voltar ao menu principal com base no nível do usuário
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
