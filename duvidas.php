<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['nivel'])) {
    header("Location: login.php");
    exit;
}

$nivelUsuario = $_SESSION['nivel']; // 1 = Admin, 2 = Funcionário
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/faq.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">

    <title>Dúvidas - Sistema de Gerenciamento de Estoque</title>
</head>
<body>
<header>
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <a href="duvidas.php">Dúvidas</a>
        <a href="contato.php">Contato</a>
    </div>
</header>

<div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('suporte.php')"></i>
    </div>   


<section>
    <h1>Perguntas Frequentes</h1>
    <div class="faq-item">
        <h2>Como cadastrar um produto?</h2>
        <p>Vá para o menu **Gerenciamento de Estoque** e clique no botão de "+" na barra de pesquisa. Preencha os campos obrigatórios, como nome, quantidade, preço e clique em "Salvar".</p>
    </div>
    <div class="faq-item">
        <h2>Como editar ou excluir um produto?</h2>
        <p>No menu **Gerenciamento de Estoque**, irá ser listado todos os produtos já cadastrados. Ao lado de cada produto, há as opções de "Editar" e "Excluir". Clique na opção desejada.</p>
    </div>
    <div class="faq-item">
        <h2>Como registrar uma venda?</h2>
        <p>Acesse o menu **Controle de Vendas** e clique no botão de "+" na barra de pesquisa. Selecione o produto, insira a quantidade vendida e confirme os dados.</p>
    </div>
    <div class="faq-item">
        <h2>Como gerar relatórios?</h2>
        <p>Na opção **Relatórios** do menu principal, escolha o tipo de relatório que deseja: estoque, vendas ou compras. Escolha o período e clique em "Gerar Relatório".</p>
    </div>
    <div class="faq-item">
        <h2>Quem pode acessar o sistema?</h2>
        <p>O sistema é dividido em dois níveis de acesso: **Admin** (para gerenciar usuários, fornecedores, produtos, vendas, compras e relatórios) e **Funcionário** (para gerenciar fornecedores, produtos, vendas e compras).</p>
    </div>
</section>

<script>
    function trocarPagina(url) {
        window.location.href = url;
    }

    function voltarMenu() {
        window.location.href = '<?php echo $nivelUsuario == 1 ? "menuAdm.php" : "menuFuncionario.php"; ?>';
    }
</script>
</body>
</html>
