<?php
// Inicia a sessão para armazenar e acessar informações entre páginas
session_start();

// Configurações de conexão com o banco de dados
$servername = "localhost"; // Nome do servidor
$username = "root";        // Usuário do banco de dados
$password = "";            // Senha do banco de dados
$dbname = "gerenciador_estoque"; // Nome do banco de dados

// Cria a conexão com o banco de dados usando mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error); // Encerra o script caso a conexão falhe
}

// Consulta SQL para buscar os produtos e suas quantidades
$sql = "SELECT nome_produto, quantidade_produto FROM produto";
$result = $conn->query($sql);

// Inicializa arrays para armazenar os produtos e quantidades
$produtos = [];
$quantidades = [];

// Verifica se há resultados na consulta
if ($result->num_rows > 0) {
    // Loop para percorrer os resultados da consulta e armazenar nos arrays
    while($row = $result->fetch_assoc()) {
        $produtos[] = $row['nome_produto'];        // Adiciona o nome do produto
        $quantidades[] = $row['quantidade_produto']; // Adiciona a quantidade do produto
    }
} else {
    // Exibe mensagem caso não existam resultados na tabela
    echo "0 resultados";
}

// Recupera o nível da conta do usuário, definindo como 0 caso não esteja logado
$nivel = $_SESSION['nivel'] ?? 0;

// Fecha a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Configuração da codificação de caracteres e meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    
    <!-- Links para arquivos CSS e fontes -->
    <link rel="stylesheet" href="css/relatorio.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    
    <title>Desenvolvimento de Sistemas</title>
</head>
<body>
<!-- Cabeçalho do site -->
<header>
    <div class="hdr">
        <!-- Logotipo com redirecionamento -->
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        
        <!-- Links de navegação -->
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        
        <!-- Opções visíveis apenas para administradores -->
        <?php if ($_SESSION['nivel'] == 1): ?>
            <a href="funcionarios.php">Funcionários</a>
        <?php endif; ?>
        
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>
<!-- Botão para voltar ao menu -->
<div class="botao--voltar">
    <i class="fa-solid fa-arrow-left" onclick="trocarPagina('menuAdm.php')"></i>
</div>

<section id="Titulo-Principal">
    <h1>Relatório e Análises</h1>
</section>

<main id="container-main">
    <!-- Área para exibir o gráfico -->
    <section class="first-five-buttons">
        <canvas id="relatorioEstoque" width="400" height="200"></canvas>
    </section>

    <!-- Formulário para geração de relatórios -->
    <section class="first-four-buttons">
        <form method="get" action="php/gerar_relatorio.php">
            <label for="tipo">Tipo de relatório:</label>
            <select name="tipo" id="tipo">
                <option value="estoque">Estoque</option>
                <option value="vendas">Vendas</option>
                <option value="compras">Compras</option>
            </select><br><br>
            
            <label for="periodo">Período:</label>
            <select name="periodo" id="periodo">
                <option value="total">Total</option>
                <option value="semanal">Semanal</option>
                <option value="mensal">Mensal</option>
            </select><br><br>
            
            <!-- Campo adicional para selecionar o mês -->
            <div id="mes-select" style="display:none;">
                <label for="mes">Escolha o mês:</label>
                <select name="mes" id="mes">
                    <!-- Opções de meses -->
                    <option value="01">Janeiro</option>
                    <option value="02">Fevereiro</option>
                    <option value="03">Março</option>
                    <option value="04">Abril</option>
                    <option value="05">Maio</option>
                    <option value="06">Junho</option>
                    <option value="07">Julho</option>
                    <option value="08">Agosto</option>
                    <option value="09">Setembro</option>
                    <option value="10">Outubro</option>
                    <option value="11">Novembro</option>
                    <option value="12">Dezembro</option>
                </select><br>
            </div>
            <br>
            
            <button type="submit">Gerar Relatório</button>
        </form>
    </section>
</main>
