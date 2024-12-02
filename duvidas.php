<?php
// Inicia a sessão para manter os dados do usuário durante a navegação
session_start();

// Verifica se a sessão contém a variável 'nivel'. Caso contrário, redireciona para a página de login
if (!isset($_SESSION['nivel'])) {
    header("Location: index.php"); // Redireciona para a página de login
    exit; // Encerra a execução do script
}

// Armazena o nível do usuário em uma variável para facilitar o uso
$nivelUsuario = $_SESSION['nivel']; 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres para UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Torna a página responsiva -->
    <!-- Importa os arquivos de estilo -->
    <link rel="stylesheet" href="css/faq.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"> <!-- Ícones -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap"> <!-- Fontes personalizadas -->

    <title>Dúvidas - Sistema de Gerenciamento de Estoque</title> <!-- Título da página -->
</head>
<body>
<header>
    <div class="hdr">
        <!-- Logo que redireciona para o menu ao ser clicado -->
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <!-- Links de navegação -->
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php 
        // Exibe links adicionais apenas para usuários de nível 1 (administradores)
        if ($_SESSION['nivel'] == 1): ?>
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>

<!-- Botão para voltar -->
<div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('suporte.php')"></i>
</div>   

<!-- Seção de perguntas frequentes -->
<section>
    <h1>Perguntas Frequentes</h1>
    <!-- Item 1 -->
    <div class="faq-item">
        <h2>Como cadastrar um produto?</h2>
        <p>Vá para o menu **Gerenciamento de Estoque** e clique no botão de "+" na barra de pesquisa. Preencha os campos obrigatórios, como nome, quantidade, preço e clique em "Salvar".</p>
    </div>
    <!-- Item 2 -->
    <div class="faq-item">
        <h2>Como editar ou excluir um produto?</h2>
        <p>No menu **Gerenciamento de Estoque**, irá ser listado todos os produtos já cadastrados. Ao lado de cada produto, há as opções de "Editar" e "Excluir". Clique na opção desejada.</p>
    </div>
    <!-- Item 3 -->
    <div class="faq-item">
        <h2>Como registrar uma venda?</h2>
        <p>Acesse o menu **Controle de Vendas** e clique no botão de "+" na barra de pesquisa. Selecione o produto, insira a quantidade vendida e confirme os dados.</p>
    </div>
    <!-- Item 4 -->
    <div class="faq-item">
        <h2>Como gerar relatórios?</h2>
        <p>Na opção **Relatórios** do menu principal, escolha o tipo de relatório que deseja: estoque, vendas ou compras. Escolha o período e clique em "Gerar Relatório".</p>
    </div>
    <!-- Item 5 -->
    <div class="faq-item">
        <h2>Quem pode acessar o sistema?</h2>
        <p>O sistema é dividido em dois níveis de acesso: **Admin** (para gerenciar usuários, fornecedores, produtos, vendas, compras e relatórios) e **Funcionário** (para gerenciar fornecedores, produtos, vendas e compras).</p>
    </div>
</section>

<script>
    // Função para redirecionar para outra página
    function trocarPagina(url) {
        window.location.href = url;
    }

    // Função para voltar ao menu principal com base no nível do usuário
    function voltarMenu() {
        // Redireciona para a página apropriada dependendo do nível do usuário
        window.location.href = '<?php echo $nivelUsuario == 1 ? "menuAdm.php" : "menuFuncionario.php"; ?>';
    }
</script>
</body>
</html>
