<?php
session_start();

// Configurações do banco de dados
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

// ATUALIZAR FORNECEDOR
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_fornecedor'])) {
    $id_fornecedor = $_POST['id_fornecedor'];
    $nome_fornecedor = $_POST['nome_fornecedor'];
    $endereco_fornecedor = $_POST['endereco_fornecedor'];
    $materialFornecido = $_POST['materialFornecido'];
    $telefone_fornecedor = $_POST['telefone_fornecedor'];

    $sql_update = "UPDATE fornecedor SET nome_fornecedor = ?, endereco_fornecedor = ?, materialFornecido = ?, telefone_fornecedor = ? WHERE id_fornecedor = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssssi", $nome_fornecedor, $endereco_fornecedor, $materialFornecido, $telefone_fornecedor, $id_fornecedor);
    $stmt->execute();
    $stmt->close();
    header("Location: fornecedores.php");
    exit;
}

// RECUPERA NÍVEL DA CONTA 
$nivel = $_SESSION['nivel'] ?? 0;

// Pesquisa por nome do fornecedor ou material fornecido
$pesquisa = $_GET['PesquisarFornecedor'] ?? ''; // Obtém o termo de pesquisa do input
$sql = "SELECT id_fornecedor, nome_fornecedor, endereco_fornecedor, materialFornecido, telefone_fornecedor FROM fornecedor WHERE nome_fornecedor LIKE ? OR materialFornecido LIKE ?";
$stmt = $conn->prepare($sql);
$likePesquisa = "%" . $pesquisa . "%";
$stmt->bind_param("ss", $likePesquisa, $likePesquisa);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/fornecedores.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Gerenciamento de Fornecedores</title>
    <style>
        .edit-form {
            display: none;
            margin-top: 10px;
        }
        .fornecedor--item {
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .fornecedor--item:hover {
            background-color: #f9f9f9;
        }

        .detalhes {
            display: none;
            margin-top: 10px;
            padding: 10px;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .inputs {
            width: 200px;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: grey;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: black;
        }
    </style>
</head>
<body>
    <header>
        <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
            <a href="estoque.php">Estoque</a>
            <a href="funcionarios.php">Funcionários</a>
            <a href="compras.php">Compras</a>
            <a href="cadastroprodutos.php">CadasProdutos</a>
            <a href="vendas.php">Vendas</a>
            <a href="relatorio.php">Relatórios</a>
        </div>
    </header>

    <section id="Titulo-Principal"><h1>Gerenciamento de Fornecedores</h1></section>

    <main id="container-main">
    <section>
    <br>
    <div style="margin: auto; display: flex; align-items: center;" class="elementos--itens">
        <form method="GET" action="fornecedores.php" id="formPesquisa" style="display: flex; align-items: center;">
            <i class="fa-solid fa-magnifying-glass" style="margin-right: 5px;"></i>
            <input type="text" id="PesquisarFornecedor" name="PesquisarFornecedor" placeholder="Pesquisar Fornecedor..." onkeypress="if(event.key === 'Enter') document.getElementById('formPesquisa').submit();" style="width: 650px;">
        </form>
        <button class="icon-btn" id="redirectBtn" style="margin-left: 10px;">
            <a class="fa-solid fa-plus" href="cadastrofornecedor.php" style="color: black; text-decoration: none;"></a>
        </button>
    </div>
    <br>
</section>

        <section>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="fornecedor--item">
                        <span onclick="toggleEditForm(<?php echo $row['id_fornecedor']; ?>)" style="cursor: pointer;">
                            <strong>&gt;</strong> <?php echo htmlspecialchars($row['nome_fornecedor']); ?> (<?php echo htmlspecialchars($row['materialFornecido']); ?>)
                        </span>
                        <i class="fa-solid fa-trash" style="color: red;" onclick="confirmarExclusao(<?php echo $row['id_fornecedor']; ?>)"></i>
                    </div>
                    <div class="detalhes" id="edit-form-<?php echo $row['id_fornecedor']; ?>" style="display: none; margin-top: 10px;">
                        <form method="POST">
                            <input type="hidden" name="id_fornecedor" value="<?php echo $row['id_fornecedor']; ?>">
                            <label for="nome_fornecedor"><strong>Nome:</strong></label>
                            <input type="text" name="nome_fornecedor" class="inputs" value="<?php echo htmlspecialchars($row['nome_fornecedor']); ?>"><br>

                            <label for="endereco_fornecedor"><strong>Endereço:</strong></label>
                            <input type="text" name="endereco_fornecedor" class="inputs" value="<?php echo htmlspecialchars($row['endereco_fornecedor']); ?>"><br>

                            <label for="materialFornecido"><strong>Material Fornecido:</strong></label>
                            <input type="text" name="materialFornecido" class="inputs" value="<?php echo htmlspecialchars($row['materialFornecido']); ?>"><br>

                            <label for="telefone_fornecedor"><strong>Telefone:</strong></label>
                            <input type="text" name="telefone_fornecedor" class="inputs" value="<?php echo htmlspecialchars($row['telefone_fornecedor']); ?>"><br>

                            <button type="submit">Salvar</button>
                        </form>
                    </div>
                    <br>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center">Nenhum fornecedor encontrado.</p>
            <?php endif; ?>
        </section>
    </main>

    <script>
        function toggleEditForm(id) {
            const form = document.getElementById('edit-form-' + id);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function confirmarExclusao(id) {
            if (confirm("Você realmente deseja excluir esse fornecedor?")) {
                window.location.href = "fornecedores.php?delete_id=" + id;
            }
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

<?php
$conn->close();
?>
