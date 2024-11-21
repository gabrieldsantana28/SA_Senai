<?php
// Conexão com o banco de dados
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'nossasa';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar o tipo de relatório
$tipoRelatorio = $_GET['tipo'] ?? '';

if ($tipoRelatorio === 'semanal') {
    // Relatório semanal: produtos vendidos na última semana
    $query = "SELECT 
        p.nome_produto, 
        SUM(v.quantidade_venda) AS quantidade_vendida, 
        SUM(v.quantidade_venda * v.preco_venda) AS total_vendido
    FROM 
        venda v
    JOIN 
        produto p ON v.fk_id_produto = p.id_produto
    WHERE 
        v.data_venda BETWEEN :data_inicio AND :data_fim
    GROUP BY 
        p.id_produto
";
    $header = ["Produto", "Quantidade Vendida"];
} elseif ($tipoRelatorio === 'mensal') {
    // Relatório mensal: produtos vendidos no mês atual
    $query = "SELECT p.nome_produto, SUM(v.quantidade_venda) AS total_vendido 
              FROM venda v
              JOIN produto p ON v.fk_id_produto = p.id_produto
              WHERE MONTH(v.data_venda) = MONTH(CURDATE()) AND YEAR(v.data_venda) = YEAR(CURDATE())
              GROUP BY p.nome_produto";
    $header = ["Produto", "Quantidade Vendida"];
} elseif ($tipoRelatorio === 'estoque') {
    // Relatório de estoque: quantidade atual no estoque
    $query = "SELECT nome_produto, quantidade_produto 
              FROM produto";
    $header = ["Produto", "Quantidade em Estoque"];
} else {
    die("Tipo de relatório inválido");
}

// Executar a consulta
$result = $conn->query($query);

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}

// Verificar se há resultados
if ($result->num_rows === 0) {
    die("Nenhum dado encontrado para o relatório.");
}

// Preparar o cabeçalho do CSV
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=$tipoRelatorio-relatorio.csv");

// Abrir a saída padrão como arquivo para escrita
$output = fopen('php://output', 'w');

// Escrever o cabeçalho no CSV
fputcsv($output, $header);

// Escrever os dados no CSV
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

// Fechar o arquivo CSV
fclose($output);

// Fechar a conexão com o banco de dados
$conn->close();
?>
