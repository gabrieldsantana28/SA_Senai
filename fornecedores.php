<?php
    session_start();

    // Verificar conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nossasa";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // EXCLUIR FORNECEDOR
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $sql_delete = "DELETE FROM fornecedor WHERE id_fornecedor = ?";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
        header("Location: fornecedores.php");
        exit;
    }

    // RECUPERA NÍVEL DA CONTA 
    $nivel = $_SESSION['nivel'] ?? 0; // NÍVEL DA CONTA EM 0 CASO NÃO ESTEJA LOGADO
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/fornecedores.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Gerenciamento de Fornecedores</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap');
    </style>
</head>
<body>
    <header>
        <div class="hdr">
            <img class="logo-header" src="./images/comp.png" alt="LOGO">
            <a href="#" onclick="voltarMenu()">Menu</a>
            <a href="funcionarios.php">Gerenciamento de Funcionários</a>
            <a href="estoque.php">Gerenciamento de Estoque</a>
            <a href="vendas.php">Controle de Vendas</a>
            <a href="cadastroprodutos.php">Cadastro de Produtos</a>
            <a href="relatorio.php">Relatórios</a>
        </div>
    </header>

    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="voltarMenu()"></i>
    </div>

    <section id="Titulo-Principal"><h1>Gerenciamento de Fornecedores</h1></section>

    <main id="container-main">
        <section>
            <br>
            <div style="margin: auto;" class="elementos--itens">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="PesquisarFornecedor" name="PesquisarFornecedor" placeholder="Pesquisar Fornecedor...">
                <button class="icon-btn" id="redirectBtn"><a class="fa-solid fa-plus" href="cadastrofornecedor.php"></a></button>
            </div>
            <br>
        </section>

        <section>
            <?php
            // Exibir fornecedores cadastrados
            $sql = "SELECT * FROM fornecedor";
            $result = $conn->query($sql);

            if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div style="margin: auto;" class="fornecedor--item">
                        <div class="elementos--itens--dois">
                            <?php echo $row['nome_fornecedor']; ?> (<?php echo $row['materialFornecido']; ?>)
                        </div>
                        <a href="?delete_id=<?php echo $row['id_fornecedor']; ?>" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?');">
                            <i class="fa-solid fa-trash" style="color: red;"></i>
                        </a>
                        <a href="editarfornecedor.php?id=<?php echo $row['id_fornecedor']; ?>">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <br>
                    </div>
                    <br>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nenhum fornecedor encontrado.</p>
            <?php endif; ?>
        </section>
    </main>

    <script>
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

<?php
    $conn->close();
?>
