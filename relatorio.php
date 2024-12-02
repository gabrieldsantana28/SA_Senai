<?php
    session_start(); // Inicia a sessão para permitir o uso de variáveis globais de sessão

    // Configuração de conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gerenciador_estoque";

    // Criação de uma nova conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica se houve erro na conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error); // Termina a execução em caso de falha na conexão
    }

    $conn->set_charset("utf8"); // Define o conjunto de caracteres para UTF-8

    // Consulta para obter o nome e a quantidade dos produtos no banco de dados
    $sql = "SELECT nome_produto, quantidade_produto FROM produto";
    $result = $conn->query($sql);

    // Inicialização de arrays para armazenar os resultados da consulta
    $produtos = [];
    $quantidades = [];

    // Se houver resultados, percorre cada linha e armazena nos arrays
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $produtos[] = $row['nome_produto'];
            $quantidades[] = $row['quantidade_produto'];
        }
    } else {
        echo "0 resultados"; // Mensagem exibida se não houver resultados
    }

    // Recupera o nível da conta a partir da sessão; define como 0 se não estiver logado
    $nivel = $_SESSION['nivel'] ?? 0;

    $conn->close(); // Fecha a conexão com o banco de dados
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/relatorio.css"> <!-- Estilo CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"> <!-- Ícones FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Biblioteca Chart.js -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Desenvolvimento de Sistemas</title>
</head>
<body>
<header>
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()"> <!-- Logo com link para voltar ao menu -->
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($_SESSION['nivel'] == 1): ?> <!-- Verificação de nível para exibir o link -->
            <a href="funcionarios.php">Funcionários</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('menuAdm.php')"></i> <!-- Botão para voltar ao menu -->
    </div>

    <section id="Titulo-Principal">
        <h1>Relatório e Análises</h1>
    </section>

    <main id="container-main">
        <section class="first-five-buttons">
            <canvas id="relatorioEstoque" width="400" height="200"></canvas> <!-- Gráfico de estoque -->
        </section>
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
                <option value="">Escolha o tipo primeiro</option>
            </select><br><br>

            <div id="mes-select" style="display:none;">
                <label for="mes">Escolha o mês:</label>
                <select name="mes" id="mes">
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
            <button type="submit">Gerar Relatório</button> <!-- Botão de envio do formulário -->
        </form>

        <script>
            document.getElementById("tipo").addEventListener("change", function() {
                var tipo = this.value;
                var periodoSelect = document.getElementById("periodo");
                while (periodoSelect.firstChild) {
                    periodoSelect.removeChild(periodoSelect.firstChild);
                }
                if (tipo === "vendas") {
                    periodoSelect.appendChild(new Option("Total", "total"));
                    periodoSelect.appendChild(new Option("Semanal", "semanal"));
                    periodoSelect.appendChild(new Option("Mensal", "mensal"));
                } else if (tipo === "compras" || tipo === "estoque") {
                    periodoSelect.appendChild(new Option("Total", "total"));
                }
                document.getElementById("mes-select").style.display = "none";
            });

            document.getElementById("periodo").addEventListener("change", function() {
                var mesSelect = document.getElementById("mes-select");
                mesSelect.style.display = (this.value === "mensal") ? "block" : "none";
            });
        </script>
    </section>
    </main>

    <script>
        function trocarPagina(url) {
            window.location.href = url;
        }
        const produtos = <?php echo json_encode($produtos); ?>;
        const quantidades = <?php echo json_encode($quantidades); ?>;

        const ctx = document.getElementById('relatorioEstoque').getContext('2d');
        const relatorioEstoque = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: produtos,
                datasets: [{
                    label: 'Quantidade de Produtos',
                    data: quantidades,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
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
                window.location.href = 'index.php';
            }
        }
    </script>
</body>
</html>