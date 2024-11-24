<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nossasa";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if (!isset($_SESSION['usuario']) || !isset($_SESSION['nivel'])) {
    header("Location: index.php");
    exit();
}

$nivel_usuario = $_SESSION['nivel']; 
$usuario = $_SESSION['usuario'];

// Aqui você pode adicionar uma verificação para restringir o acesso com base no nível do usuário, se necessário:
if ($nivel_usuario != 1 && $nivel_usuario != 2) {
    // Se o usuário não for administrador ou funcionário, redireciona para uma página de acesso restrito
    header("Location: acesso_restrito.php");
    exit();
}

// Fecha a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/suporte.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Suporte</title>
</head>
<body>
<header>
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="Logo" onclick="voltarMenu()">
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($nivel_usuario == 1): ?>
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>

<div class="botao--voltar">
    <i class="fa-solid fa-arrow-left" onclick="voltarMenu()"></i>
</div>

<main>
    <section id="Titulo-Principal">
        <h1>Central de Suporte</h1>
    </section>

    <section class="conteudo">
        <p>Bem-vindo(a) à central de suporte. Escolha uma das opções para prosseguir:</p>
        <div id="container-elementos">
            <div class="elementos"><a href="duvidas.php">Dúvidas</a></div>
            <div class="elementos"><a href="contato.php">Contato</a></div>
        </div>
    </section>
</main>

<script>
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
