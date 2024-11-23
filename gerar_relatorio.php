<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nossasa";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Recupera o tipo de relatório e o período
$tipo = $_GET['tipo'] ?? '';
$periodo = $_GET['periodo'] ?? 'total';
$mes = $_GET['mes'] ?? date('m');  // Pega o mês atual por padrão

// Filtrar período
$dataAtual = date('Y-m-d');
$filtroData = '';
$dataInicio = '';

if ($periodo === 'semanal') {
    $dataInicio = date('Y-m-d', strtotime('-7 days', strtotime($dataAtual)));
    $filtroData = "AND c.data_compra >= '$dataInicio' AND c.data_compra <= '$dataAtual'"; // Alteração para usar data_compra
} elseif ($periodo === 'mensal') {
    // Filtra pelo mês selecionado, se for o período mensal
    $dataInicio = date('Y-' . $mes . '-01'); // Primeiro dia do mês escolhido
    $dataFim = date('Y-' . $mes . '-t'); // Último dia do mês
    $filtroData = "AND c.data_compra >= '$dataInicio' AND c.data_compra <= '$dataFim'"; // Alteração para usar data_compra
}

// Gera relatório com base no tipo e período
if ($tipo == 'estoque') {
    // Consulta para o relatório de estoque
    $sql = "SELECT nome_produto, quantidade_produto FROM produto";
    $result = $conn->query($sql);

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="relatorio_estoque.xls"');
    header('Cache-Control: max-age=0');

    echo "Produto\tQuantidade\n";
    while ($row = $result->fetch_assoc()) {
        echo "{$row['nome_produto']}\t{$row['quantidade_produto']}\n";
    }

} elseif ($tipo == 'vendas') {
    // Relatório de vendas
    $sql = "SELECT p.nome_produto, SUM(v.quantidade_venda) AS total_vendido, v.data_venda
            FROM venda v
            JOIN produto p ON v.fk_id_produto = p.id_produto
            WHERE MONTH(v.data_venda) = $mes
            GROUP BY p.nome_produto, v.data_venda";
    $result = $conn->query($sql);

    header('Content-Type: application/vnd.ms-excel'); 
    header('Content-Disposition: attachment;filename="relatorio_vendas.xls"'); 
    header('Cache-Control: max-age=0');

    echo "Produto\tQuantidade Vendida\tData\n";

    while ($row = $result->fetch_assoc()) {
        echo "{$row['nome_produto']}\t{$row['total_vendido']}\t{$row['data_venda']}\n";
    }
}


elseif ($tipo == 'compras') {
    // Relatório de compras
    $sql = "SELECT f.nome_fornecedor, c.produto_compra, c.quantidade_compra, c.data_compra
            FROM compra c
            JOIN fornecedor f ON c.fk_id_fornecedor = f.id_fornecedor
            WHERE MONTH(c.data_compra) = $mes";
    $result = $conn->query($sql);

    // Cabeçalho do arquivo
    echo "Fornecedor\tProduto\tQuantidade\tData\n";

    header('Content-Type: application/vnd.ms-excel'); 
    header('Content-Disposition: attachment;filename="relatorio_compras.xls"'); 
    header('Cache-Control: max-age=0');

    // Exibe os dados de compras
    while ($row = $result->fetch_assoc()) {
        echo "{$row['nome_fornecedor']}\t{$row['produto_compra']}\t{$row['quantidade_compra']}\t{$row['data_compra']}\n";
    }
}

$conn->close();
?>
