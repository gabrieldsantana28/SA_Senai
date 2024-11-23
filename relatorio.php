<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nossasa";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$sql = "SELECT nome_produto, quantidade_produto FROM produto";
$result = $conn->query($sql);

$produtos = [];
$quantidades = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $produtos[] = $row['nome_produto'];
        $quantidades[] = $row['quantidade_produto'];
    }
} else {
    echo "0 resultados";
}

// RECUPERA NÍVEL DA CONTA 
$nivel = $_SESSION['nivel'] ?? 0; // NÍVEL DA CONTA EM 0 CASO NÃO ESTEJA LOGADO

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/relatorio.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Desenvolvimento de Sistemas</title>
</head>
<body>
<header>
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($_SESSION['nivel'] == 1): // Apenas admin pode ver estas opções ?>
            <a href="funcionarios.php">Funcionários</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('menuAdm.php')"></i>
    </div>

    <main id="container-main">
        <section class="first-five-buttons">
            <canvas id="relatorioEstoque" width="400" height="200"></canvas>
        </section>
        <section class="first-four-buttons">
            <button class="button-menu" onclick="window.location.href='gerar_relatorio.php?tipo=estoque'">
                Baixar relatório de estoque
                <div><i class="fa-solid fa-cloud-arrow-down"></i></div>
            </button>
            <button class="button-menu" onclick="window.location.href='gerar_relatorio.php?tipo=vendas'">
                Baixar relatório de vendas
                <div><i class="fa-solid fa-cloud-arrow-down"></i></div>
            </button>
            <button class="button-menu" onclick="window.location.href='gerar_relatorio.php?tipo=compras'">
                Baixar relatório de Compras
                <div><i class="fa-solid fa-cloud-arrow-down"></i></div>
            </button>
        </section>
    </main>

    <script>
        function trocarPagina(url) {
            window.location.href = url;
        }

        // Obter os dados do PHP
        const produtos = <?php echo json_encode($produtos); ?>;
        const quantidades = <?php echo json_encode($quantidades); ?>;

        // Logs para depuração
        console.log('Produtos:', produtos);
        console.log('Quantidades:', quantidades);

        // Configuração do gráfico
        const ctx = document.getElementById('relatorioEstoque').getContext('2d');
        const relatorioEstoque = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: produtos,
                datasets: [{
                    label: 'Quantidade de Produtos',
                    data: quantidades,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

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
