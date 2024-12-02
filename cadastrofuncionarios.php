<?php
session_start(); // Inicia a sessão para acessar as variáveis de sessão

// Configurações de conexão ao banco de dados
$servername = "localhost"; // Nome do servidor do banco de dados
$username = "root"; // Nome de usuário do banco de dados
$password = ""; // Senha do banco de dados
$dbname = "gerenciador_estoque"; // Nome do banco de dados

// Cria uma nova conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão falhou
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error); // Exibe erro e encerra o script
}

$conn->set_charset("utf8");

$message = ""; // Inicializa a variável de mensagem

// Verifica se o método de requisição é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $nivel = $_POST['nivel']; 

    // Comando SQL para inserir um novo usuário
    $sql = "INSERT INTO usuario (nome_usuario, user_usuario, email_usuario, senha_usuario, nivel_usuario) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql); // Prepara a instrução SQL

    // Liga os parâmetros à instrução preparada
    $stmt->bind_param("ssssi", $nome, $usuario, $email, $senha, $nivel); 

    // Executa a instrução e verifica se a inserção foi bem-sucedida
    if ($stmt->execute()) {
        $message = "Funcionário cadastrado com sucesso!"; // Mensagem de sucesso
    } else {
        $message = "Erro ao cadastrar funcionário: " . $stmt->error; // Mensagem de erro
    }

    $stmt->close(); // Fecha a instrução preparada
}

// Recupera o nível da conta do usuário da sessão ou define como 0 se não estiver definido
$nivel = $_SESSION['nivel'] ?? 0; 

$conn->close(); // Fecha a conexão com o banco de dados
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres para UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define a responsividade da página -->
    <meta http-equiv="X-UA-Compatible" content="IE=7"> <!-- Compatibilidade com Internet Explorer -->
    <link rel="stylesheet" href="css/cadastrofuncionarios.css"> <!-- Link para o CSS da página de cadastro de funcionários -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"> <!-- Link para Font Awesome -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap"> <!-- Link para Google Fonts -->
    <title>Cadastro de Funcionários</title> <!-- Título da página -->
</head>
<body>
<header>
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()"> <!-- Logo da empresa que volta ao menu ao ser clicado -->
        <a href="estoque.php">Estoque</a> <!-- Link para a página de estoque -->
        <a href="fornecedores.php">Fornecedores</a> <!-- Link para a página de fornecedores -->
        <?php if ($_SESSION['nivel'] == 1): // Apenas admin pode ver estas opções ?>
            <a href="funcionarios.php">Funcionários</a> <!-- Link para a página de funcionários -->
            <a href="relatorio.php">Relatórios</a> <!-- Link para a página de relatórios -->
        <?php endif; ?>
        <a href="compras.php">Compras</a> <!-- Link para a página de compras -->
        <a href="vendas.php">Vendas</a> <!-- Link para a página de vendas -->
    </div>
</header>
    <div class="botao--voltar"> <!-- Botão para voltar à página de funcionários -->
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('funcionarios.php')"></i> <!-- Ícone de voltar -->
    </div>    
    
    <main id="container-main"> <!-- Início do conteúdo principal -->
        <section id="Titulo -Principal"><h1>Cadastro de Funcionários</h1></section> <!-- Título da seção de cadastro -->

        <?php if ($message): ?> <!-- Verifica se há uma mensagem para exibir -->
            <p><?php echo $message; ?></p> <!-- Exibe a mensagem -->
        <?php endif; ?>

        <form method="POST"> <!-- Formulário para cadastro de funcionários -->
            <section id="container-elementos"> <!-- Container para os elementos do formulário -->
                <div class="elementos--itens"> <!-- Seção para inserir o nome do funcionário -->
                    <i class="fas fa-user-tag"></i> <!-- Ícone de nome -->
                    <input type="text" id="NomeFuncionario" name="nome" placeholder="Nome do Funcionário..." required> <!-- Campo para o nome do funcionário -->
                </div>
                <div class="elementos--itens"> <!-- Seção para inserir o nome de usuário -->
                    <i class="fa-solid fa-user"></i> <!-- Ícone de usuário -->
                    <input type="text" id="Usuario" name="usuario" placeholder="Nome de usuário..." required> <!-- Campo para o nome de usuário -->
                </div>
                <div class="elementos--itens"> <!-- Seção para inserir o email -->
                    <i class='fa-solid fa-envelope'></i> <!-- Ícone de email -->
                    <input type="email" id="Email" name="email" placeholder="Email..." required> <!-- Campo para o email -->
                </div>
                <div class="elementos--itens"> <!-- Seção para inserir a senha -->
                    <i class='fa-solid fa-lock'></i> <!-- Ícone de senha -->
                    <input type="password" id="Senha" name="senha" placeholder="Senha (Mín. 6 Caracteres)..." minlength="6" required> <!-- Campo para a senha -->
                </div>
                <div class="elementos--itens"> <!-- Seção para selecionar o nível do usuário -->
                    <i class="fa-solid fa-user-tie" aria-label="Ícone de Nível"></i> <!-- Ícone de nível -->
                    <select id="Nivel" name="nivel" required> <!-- Seleção de nível -->
                        <option value="">Selecione o Nível...</option> <!-- Opção padrão -->
                        <option value="1">1 - Admin</option> <!-- Opção de nível administrador -->
                        <option value="2">2 - Funcionário</option> <!-- Opção de nível funcionário -->
                    </select>
                </div>
                <div class="button"> <!-- Seção para o botão de cadastro -->
                    <button type="submit">Cadastrar</button> <!-- Botão para enviar o formulário -->
                </div>
            </section>
        </form>
    </main>
    <script>
        function trocarPagina(url) { // Função para trocar de página
            window.location.href = url; // Redireciona para a URL especificada
        }

        function voltarMenu() { // Função para voltar ao menu
            const nivel = <?php echo isset($_SESSION['nivel']) ? $_SESSION['nivel'] : 'null'; ?>; // Obtém o nível do usuário da sessão
            if (nivel !== null) { // Verifica se o nível não é nulo
                if (nivel == 1) { // Se o nível for 1 (administrador)
                    window.location.href = 'menuAdm.php'; // Redireciona para o menu do administrador
                } else if (nivel == 2) { // Se o nível for 2 (funcionário)
                    window.location.href = 'menuFuncionario.php'; // Redireciona para o menu do funcionário
                }
            } else { // Se o nível for nulo
                alert('Sessão expirada. Faça login novamente.'); // Alerta de sessão expirada
                window.location.href = 'index.php'; // Redireciona para a página de login
            }
        }
    </script>
</body>
</html>