<?php
// Inicia ou recupera a sessão do usuário.
session_start();

// Configurações do banco de dados.
$servername = "localhost"; // Endereço do servidor.
$username = "root";        // Usuário do banco de dados.
$password = "";            // Senha do banco de dados.
$dbname = "gerenciador_estoque"; // Nome do banco de dados.

// Cria a conexão com o banco de dados.
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão foi bem-sucedida.
if ($conn->connect_error) {
    // Encerra o script e exibe uma mensagem de erro caso a conexão falhe.
    die("Conexão falhou: " . $conn->connect_error);
}

// Variável para armazenar mensagens de feedback ao usuário.
$message = "";

// Verifica se o formulário foi enviado via método POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coleta os dados enviados pelo formulário.
    $nome = $_POST['nome'];
    $material = $_POST['material'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    // Consulta SQL para inserir um novo fornecedor no banco de dados.
    $sql = "INSERT INTO fornecedor (nome_fornecedor, material_fornecedor, telefone_fornecedor, endereco_fornecedor) VALUES (?, ?, ?, ?)";
    
    // Prepara a consulta para evitar SQL Injection.
    $stmt = $conn->prepare($sql);
    
    // Vincula os parâmetros à consulta.
    $stmt->bind_param("ssss", $nome, $material, $telefone, $endereco);

    // Executa a consulta e verifica se foi bem-sucedida.
    if ($stmt->execute()) {
        $message = "Fornecedor cadastrado com sucesso!"; // Mensagem de sucesso.
    } else {
        $message = "Erro ao cadastrar fornecedor: " . $stmt->error; // Mensagem de erro.
    }

    // Fecha a declaração preparada.
    $stmt->close();
}

// Recupera o nível do usuário da sessão. Define como 0 caso não esteja logado.
$nivel = $_SESSION['nivel'] ?? 0;

// Fecha a conexão com o banco de dados.
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Importa os estilos CSS e fontes -->
    <link rel="stylesheet" href="css/cadastrofornecedor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Cadastro de Fornecedores</title>
    <script>
        // Função para aplicar a máscara ao campo de telefone.
        function mascara(telefone){ 
            if(telefone.value.length == 0)
                telefone.value = '(' + telefone.value; // Adiciona o parêntese inicial.
            if(telefone.value.length == 3)
                telefone.value = telefone.value + ') '; // Fecha o código de área.
            if(telefone.value.length == 10)
                telefone.value = telefone.value + '-'; // Adiciona o traço no número.
        }   
    </script>
</head>
<body>
<header>
    <!-- Cabeçalho com menu de navegação -->
    <div class="hdr">
        <!-- Logo que retorna ao menu principal -->
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($_SESSION['nivel'] == 1): // Verifica se o usuário é administrador ?>
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>

<!-- Botão para voltar à página anterior -->
<div class="botao--voltar">
    <i class="fa-solid fa-arrow-left" onclick="trocarPagina('fornecedores.php')" aria-label="Voltar"></i>
</div>   

<main id="container-main">
    <!-- Título da página -->
    <section id="Titulo-Principal"><h1>Cadastro de Fornecedores</h1></section>

    <!-- Exibe mensagem de feedback ao usuário -->
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Formulário de cadastro de fornecedor -->
    <form action="cadastrofornecedor.php" method="POST">
        <section id="container-elementos">
            <!-- Campo para o nome do fornecedor -->
            <div class="elementos--itens">
                <i class="fas fa-id-badge" aria-label="Ícone de ID"></i>
                <input type="text" id="NomeFornecedor" name="nome" placeholder="Nome do Fornecedor..." required>
            </div>
            <!-- Campo para o material fornecido -->
            <div class="elementos--itens">
                <i class="fa-solid fa-boxes-stacked" aria-label="Ícone de Material"></i>
                <input type="text" id="MatFornecido" name="material" placeholder="Material Fornecido..." required>
            </div>
            <!-- Campo para o telefone -->
            <div class="elementos--itens">
                <i class="fa-solid fa-phone" aria-label="Ícone de Telefone"></i>
                <input type="text" id="telefone" name="telefone" placeholder="Telefone..." required maxlength="15" onkeypress="mascara(this)">
            </div>
            <!-- Campo para o endereço -->
            <div class="elementos--itens">
                <i class="fas fa-map-marker-alt" aria-label="Ícone de Endereço"></i>
                <input type="text" id="Endereco" name="endereco" placeholder="Endereço..." required>
            </div>
            <!-- Botão para enviar o formulário -->
            <div class="button">
                <button type="submit">Cadastrar</button>
            </div>
        </section>
    </form>
</main>

<script>
    // Redireciona o usuário para outra página.
    function trocarPagina(url) {
        window.location.href = url;
    }

    // Redireciona o usuário para o menu principal com base no nível de acesso.
    function voltarMenu() {
        const nivel = <?php echo isset($_SESSION['nivel']) ? $_SESSION['nivel'] : 'null'; ?>;
        if (nivel !== null) {
            if (nivel == 1) {
                window.location.href = 'menuAdm.php'; // Menu para administradores.
            } else if (nivel == 2) {
                window.location.href = 'menuFuncionario.php'; // Menu para funcionários.
            }
        } else {
            alert('Sessão expirada. Faça login novamente.'); // Alerta de sessão expirada.
            window.location.href = 'login.php';
        }
    }
</script>

</body>
</html>
