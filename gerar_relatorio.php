<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nossasa";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica o tipo de relatório que deve ser gerado
$tipo_relatorio = isset($_GET['tipo']) ? $_GET['tipo'] : 'estoque';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="relatorio_' . $tipo_relatorio . '.csv"');

// Define o delimitador do CSV
$output = fopen('php://output', 'w');

// Adiciona o cabeçalho do CSV
if ($tipo_relatorio === 'semanal') {
    fputcsv($output, ['Produto', 'Quantidade', 'Data']);
    // Aqui você deve fazer a consulta para os dados semanais
    $sql = "SELECT nome_produto, quantidade, DATE(data_cadastro) AS data FROM produto WHERE DATE(data_cadastro) >= CURDATE() - INTERVAL 7 DAY";
} elseif ($tipo_relatorio === 'mensal') {
    fputcsv($output, ['Produto', 'Quantidade', 'Data']);
    // Aqui você deve fazer a consulta para os dados mensais
    $sql = "SELECT nome_produto, quantidade, DATE(data_cadastro) AS data FROM produto WHERE MONTH(data_cadastro) = MONTH(CURRENT_DATE())";
} else {
    fputcsv($output, ['Produto', 'Quantidade']);
    // Aqui você deve fazer a consulta para todos os dados do estoque
    $sql = "SELECT nome_produto, quantidade FROM produto";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
} else {
    fputcsv($output, ['Nenhum resultado encontrado']);
}

fclose($output);
$conn->close();
exit();
?>
