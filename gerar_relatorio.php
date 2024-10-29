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

// Verifica o tipo de relatório que deve ser gerado
$tipo_relatorio = isset($_GET['tipo']) ? $_GET['tipo'] : 'estoque';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="relatorio_' . $tipo_relatorio . '.csv"');

// Define o delimitador do CSV
$output = fopen('php://output', 'w');

// Define as colunas do CSV
if ($tipo_relatorio === 'semanal') {
    fputcsv($output, ['Produto', 'Quantidade Vendida', 'Data Venda'], ';');
    // Consulta de produtos vendidos na semana atual
    $sql = "SELECT p.nome_produto, v.quantidade_venda, DATE_FORMAT(v.data_venda, '%Y-%m-%d') AS data_venda
            FROM venda v
            JOIN produto p ON v.fk_produto_id = p.id_produto
            WHERE v.data_venda >= CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY
            AND v.data_venda < CURDATE() + INTERVAL 1 DAY";
} elseif ($tipo_relatorio === 'mensal') {
    fputcsv($output, ['Produto', 'Quantidade Vendida', 'Data Venda'], ';');
    // Consulta de produtos vendidos no mês atual
    $sql = "SELECT p.nome_produto, v.quantidade_venda, DATE_FORMAT(v.data_venda, '%Y-%m-%d') AS data_venda
            FROM venda v
            JOIN produto p ON v.fk_produto_id = p.id_produto
            WHERE MONTH(v.data_venda) = MONTH(CURRENT_DATE()) 
            AND YEAR(v.data_venda) = YEAR(CURRENT_DATE())";
} else {
    fputcsv($output, ['Produto', 'Quantidade'], ';');
    // Consulta de produtos no estoque
    $sql = "SELECT nome_produto, quantidade FROM produto";
}

$result = $conn->query($sql);

// Verificação de erros
if (!$result) {
    fputcsv($output, ['Erro na consulta: ' . $conn->error], ';');
} else if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row, ';'); // Usando ponto e vírgula como delimitador
    }
} else {
    fputcsv($output, ['Nenhum resultado encontrado'], ';');
}

fclose($output);
$conn->close();
exit();
?>
