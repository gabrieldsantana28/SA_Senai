<?php
    // Inicia a sessão, permitindo o uso de variáveis globais de sessão
    session_start();

    // Definindo as informações para conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gerenciador_estoque";

    // Cria uma nova conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica se a conexão com o banco de dados falhou
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error); // Exibe mensagem de erro e encerra o script
    }

    // Verifica se a sessão do usuário está definida. Se não estiver, redireciona para a página de login (index.php)
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['nivel'])) {
        header("Location: index.php"); // Redireciona para a página de login
        exit(); // Encerra o script para garantir que o redirecionamento aconteça
    }

    // Armazena os valores de usuário e nível de acesso na sessão
    $nivel_usuario = $_SESSION['nivel']; 
    $usuario = $_SESSION['usuario'];

    // Verifica se o nível de usuário não é válido (nem 1 nem 2). Se for o caso, redireciona para uma página de acesso restrito.
    if ($nivel_usuario != 1 && $nivel_usuario != 2) {
        header("Location: acesso_restrito.php"); // Redireciona para a página de acesso restrito
        exit(); // Encerra o script
    }

    // Fecha a conexão com o banco de dados (não é necessário manter a conexão aberta depois da verificação inicial)
    $conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres para UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define a responsividade da página -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Garante compatibilidade com o IE -->
    <!-- Importa os arquivos CSS e fontes -->
    <link rel="stylesheet" href="css/suporte.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"> <!-- Ícones FontAwesome -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap"> <!-- Fontes externas -->
    <title>Suporte</title>
</head>
<body>
<header>
    <!-- Header com a navegação principal -->
    <div class="hdr">
        <!-- Logo clicável que chama a função voltarMenu() -->
        <img class="logo-header" src="./images/comp.png" alt="Logo" onclick="voltarMenu()">
        <!-- Links de navegação (Estoque, Fornecedores, etc.) -->
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($nivel_usuario == 1): ?> <!-- Exibe opções adicionais se o usuário for administrador (nivel 1) -->
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>

<!-- Botão de voltar (com ícone) que chama a função voltarMenu() -->
<div class="botao--voltar">
    <i class="fa-solid fa-arrow-left" onclick="voltarMenu()"></i>
</div>

<main>
    <section id="Titulo-Principal">
        <!-- Título da página -->
        <h1>Central de Suporte</h1>
    </section>

    <section class="conteudo">
        <!-- Instruções para o usuário -->
        <p>Bem-vindo(a) à central de suporte. Escolha uma das opções para prosseguir:</p>
        <div id="container-elementos">
            <!-- Links para as páginas de Dúvidas e Contato -->
            <div class="elementos"><a href="duvidas.php">Dúvidas</a></div>
            <div class="elementos"><a href="contato.php">Contato</a></div>
        </div>
    </section>
</main>

<script>
    // Função que redireciona o usuário para o menu adequado dependendo do nível
    function voltarMenu() {
        // Obtém o nível do usuário da variável de sessão
        const nivel = <?php echo isset($_SESSION['nivel']) ? $_SESSION['nivel'] : 'null'; ?>;
        // Verifica se o nível do usuário é válido
        if (nivel !== null) {
            if (nivel == 1) {
                // Se o usuário for administrador (nível 1), redireciona para o menu de administrador
                window.location.href = 'menuAdm.php';
            } else if (nivel == 2) {
                // Se o usuário for funcionário (nível 2), redireciona para o menu de funcionário
                window.location.href = 'menuFuncionario.php';
            }
        } else {
            // Caso a sessão tenha expirado, exibe um alerta e redireciona para o login
            alert('Sessão expirada. Faça login novamente.');
            window.location.href = 'index.php';
        }
    }
</script>
</body>
</html>