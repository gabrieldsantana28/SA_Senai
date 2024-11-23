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

// Determina o tipo de relatório
$tipo = $_GET['tipo'] ?? '';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename={$tipo}_relatorio.xls");

if ($tipo == 'estoque') {
    // Relatório de estoque
    $sql = "SELECT nome_produto, quantidade_produto FROM produto";
    $result = $conn->query($sql);

    echo "Produto\tQuantidade\n";
    while ($row = $result->fetch_assoc()) {
        echo "{$row['nome_produto']}\t{$row['quantidade_produto']}\n";
    }

} elseif ($tipo == 'vendas') {
    // Relatório de vendas
    $sql = "SELECT p.nome_produto, SUM(v.quantidade_venda) AS total_vendido
            FROM venda v
            JOIN produto p ON v.fk_id_produto = p.id_produto
            GROUP BY p.nome_produto";
    $result = $conn->query($sql);

    echo "Produto\tQuantidade Vendida\n";
    while ($row = $result->fetch_assoc()) {
        echo "{$row['nome_produto']}\t{$row['total_vendido']}\n";
    }

}  elseif ($tipo == 'compras') {
    // Relatório de compras
    $sql = "SELECT f.nome_fornecedor, c.produto_compra, c.quantidade_compra
            FROM compra c
            JOIN fornecedor f ON c.fk_id_fornecedor = f.id_fornecedor";
    $result = $conn->query($sql);

    // Cabeçalho do arquivo
    echo "Fornecedor\tProduto\tQuantidade\n";

    // Exibe os dados de compras
    while ($row = $result->fetch_assoc()) {
        echo "{$row['nome_fornecedor']}\t{$row['produto_compra']}\t{$row['quantidade_compra']}\n";
    }
}

$conn->close();
?>
