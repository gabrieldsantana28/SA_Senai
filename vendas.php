<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nossasa";

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $sql_vendas = "SELECT id_venda, produto_venda, quantidade, tipo_pagamento, data_venda, hora_venda FROM venda";
    $result_vendas = $conn->query($sql_vendas);

    // RECUPERA NÍVEL DA CONTA 
    $nivel = $_SESSION['nivel'] ?? 0; // NÍVEL DA CONTA EM 0 CASO NÃO ESTEJA LOGADO

    $pesquisa = $_GET['PesquisarVenda'] ?? ''; // Obtém o termo de pesquisa do input
    $sql_vendas = "SELECT id_venda, produto_venda, quantidade, tipo_pagamento, data_venda, hora_venda FROM venda WHERE produto_venda LIKE ? OR tipo_pagamento LIKE ?";
    $stmt = $conn->prepare($sql_vendas);
    $likePesquisa = "%" . $pesquisa . "%";
    $stmt->bind_param("ss", $likePesquisa, $likePesquisa);
    $stmt->execute();
    $result_vendas = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/vendas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Gerenciamento de Vendas</title>
    <style>
        .teste:hover{
            outline: none;
        }
    </style>
</head>
<body>
    <header>
        <div class="hdr">
            <img class="logo-header" src="./images/comp.png" alt="LOGO">
            <a href="#" onclick="voltarMenu()">Menu</a>
            <a href="funcionarios.php">Gerenciamento de Funcionários</a>
            <a href="fornecedores.php">Gerenciamento de Fornecedores</a>
            <a href="estoque.php">Gerenciamento de Estoque</a>
            <a href="cadastroprodutos.php">Cadastro de Produtos</a>
            <a href="relatorio.php">Relatórios</a>
        </div>
    </header>

    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('menuAdm.php')"></i>
    </div>   
    
    <section id="Titulo-Principal">
        <h1>Controle de Vendas</h1>
    </section>

    <section style="margin-bottom: 20px;">
        <div class="elementos--itens">
            <i class="fa-solid fa-magnifying-glass"></i>
            <form method="GET" action="">
            <input type="text" id="PesquisarVenda" class="teste" name="PesquisarVenda" placeholder="Pesquisar Venda..." value="<?php echo htmlspecialchars($pesquisa); ?>" onkeypress="if(event.key === 'Enter') { this.form.submit(); }">
            </form>
            <button class="icon-btn" id="redirectBtn">
                <a href="cadastrovendas.php">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </button>
        </div>

    </section>

    <section id="container-elementos">
        <div class="elementos">N° VENDA</div>
        <div class="elementos">PRODUTO</div>
        <div class="elementos">PREÇO - PAGAMENTO</div>
        <div class="elementos">TIPO - PAGAMENTO</div>
        <div class="elementos">DATA</div>
        <div class="elementos">HORA</div>
    </section>

<?php

    // DELETA VENDA
    if (isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        $sql_delete = "DELETE FROM venda WHERE id_venda = $id";
        $conn->query($sql_delete);
        // VOLTAR
        header("Location: vendas.php"); 
        exit;
    }

    if ($result_vendas->num_rows > 0) {
        while ($linha = $result_vendas->fetch_assoc()) {
            echo '<section id="lista-elementos">';
            echo '<div class="elementos-lista">' . $linha["id_venda"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["produto_venda"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["id_venda"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["tipo_pagamento"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["data_venda"] . '</div>';
            echo '<div class="elementos-lista">' . $linha["hora_venda"] . '</div>';
            echo '<div class="icons">';
            
            // EXCLUIR
            echo '<form method="POST" style="display:inline-block;" onsubmit="return confirmarExclusao();">';
            echo '<input type="hidden" name="delete_id" value="' . $linha["id_venda"] . '">';
            echo '<button type="submit" style="background:none; border:none;">';
            echo '<i class="fa-solid fa-trash" style="color: red;"></i>';
            echo '</button>';
            echo '</form>';
            
            // EDITAR
            echo '<a href="editarvendas.php?id=' . $linha["id_venda"] . '"><i class="fa-solid fa-pen-to-square"></i></a>';
            echo '</div>';
            echo '</section>';
        }
    } else {
        echo '<br>';
        echo '<div style="text-align:center">Nenhum fornecedor encontrado.</div>';
    }

?> 
<script>
function confirmarExclusao() {
    return confirm("Você realmente deseja apagar este item?");
}
</script>
    <script>
        function trocarPagina(url) {
            window.location.href = url;
        }
        function voltarMenu() {
            <?php if ($nivel == 1): ?>
                window.location.href = 'menuAdm.php';
            <?php elseif ($nivel == 2): ?>
                window.location.href = 'menuFuncionario.php';
            <?php else: ?>
                alert('Nível de conta não identificado. Faça login novamente.');
                window.location.href = 'login.php'; 
            <?php endif; ?>
        }
    </script>
</body>
</html>