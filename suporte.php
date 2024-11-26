<?php
    session_start(); // Inicia a sessão para gerenciar autenticação e dados do usuário.

    // Configuração da conexão com o banco de dados.
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gerenciador_estoque";

    // Criação da conexão com o banco de dados.
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica se houve erro na conexão.
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Verifica se o usuário está logado. Caso contrário, redireciona para a página de login.
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['nivel'])) {
        header("Location: index.php");
        exit();
    }

    // Recupera o nível de acesso e o nome de usuário da sessão.
    $nivel_usuario = $_SESSION['nivel']; 
    $usuario = $_SESSION['usuario'];

    // Restringe o acesso para usuários que não sejam administradores ou funcionários.
    if ($nivel_usuario != 1 && $nivel_usuario != 2) {
        header("Location: acesso_restrito.php");
        exit();
    }

    // Fecha a conexão com o banco de dados.
    $conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Configurações de meta tags para responsividade e compatibilidade -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Links para arquivos de estilo externos -->
    <link rel="stylesheet" href="css/suporte.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">

    <!-- Título da página -->
    <title>Suporte</title>
</head>
<body>
<header>
    <!-- Cabeçalho com navegação -->
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="Logo" onclick="voltarMenu()"> <!-- Logotipo clicável para retornar ao menu -->
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($nivel_usuario == 1): ?> <!-- Exibe opções adicionais para administradores -->
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>

<!-- Botão para voltar ao menu -->
<div class="botao--voltar">
    <i class="fa-solid fa-arrow-left" onclick="voltarMenu()"></i>
</div>

<main>
    <section id="Titulo-Principal">
        <!-- Título principal da página -->
        <h1>Central de Suporte</h1>
    </section>

    <section class="conteudo">
        <!-- Descrição e opções de suporte -->
        <p>Bem-vindo(a) à central de suporte. Escolha uma das opções para prosseguir:</p>
        <div id="container-elementos">
            <!-- Links para páginas relacionadas ao suporte -->
            <div class="elementos"><a href="duvidas.php">Dúvidas</a></div>
            <div class="elementos"><a href="contato.php">Contato</a></div>
        </div>
    </section>
</main>

<script>
    // Função para retornar ao menu com base no nível de acesso do usuário.
    function voltarMenu() {
        const nivel = <?php echo isset($_SESSION['nivel']) ? $_SESSION['nivel'] : 'null'; ?>;
        if (nivel !== null) {
            if (nivel == 1) {
                window.location.href = 'menuAdm.php'; // Redireciona para o menu do administrador.
            } else if (nivel == 2) {
                window.location.href = 'menuFuncionario.php'; // Redireciona para o menu do funcionário.
            }
        } else {
            alert('Sessão expirada. Faça login novamente.'); // Mensagem de alerta em caso de sessão expirada.
            window.location.href = 'index.php'; // Redireciona para a página de login.
        }
    }
</script>
</body>
</html>
