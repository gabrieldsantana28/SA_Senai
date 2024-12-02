<?php
// Inicia a sessão para manter o controle de usuário e permissões
session_start();

// Configurações de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gerenciador_estoque";

// Estabelece a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se houve erro na conexão e encerra o script em caso de falha
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Define o charset da conexão para UTF-8
$conn->set_charset("utf8");

// Verifica se foi enviada a solicitação de exclusão de um fornecedor
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id']; // Obtém o ID do fornecedor a ser excluído
    $sql_delete = "DELETE FROM fornecedor WHERE id_fornecedor = ?"; // Comando SQL para excluir o fornecedor
    $stmt = $conn->prepare($sql_delete); // Prepara a consulta para evitar injeção de SQL
    $stmt->bind_param("i", $delete_id); // Vincula o ID do fornecedor à consulta
    $stmt->execute(); // Executa a consulta
    $stmt->close(); // Fecha a declaração preparada
    header("Location: fornecedores.php"); // Redireciona para a página de fornecedores
    exit;
}

// Verifica se o formulário de atualização foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_fornecedor'])) {
    // Obtém os dados do formulário
    $id_fornecedor = $_POST['id_fornecedor'];
    $nome_fornecedor = $_POST['nome_fornecedor'];
    $endereco_fornecedor = $_POST['endereco_fornecedor'];
    $material_fornecedor = $_POST['material_fornecedor'];
    $telefone_fornecedor = $_POST['telefone_fornecedor'];

    // Comando SQL para atualizar os dados do fornecedor
    $sql_update = "UPDATE fornecedor SET nome_fornecedor = ?, endereco_fornecedor = ?, material_fornecedor = ?, telefone_fornecedor = ? WHERE id_fornecedor = ?";
    $stmt = $conn->prepare($sql_update); // Prepara a consulta
    $stmt->bind_param("ssssi", $nome_fornecedor, $endereco_fornecedor, $material_fornecedor, $telefone_fornecedor, $id_fornecedor); // Vincula os parâmetros
    $stmt->execute(); // Executa a consulta
    $stmt->close(); // Fecha a declaração preparada
    header("Location: fornecedores.php"); // Redireciona para a página de fornecedores
    exit;
}

// Recupera o nível de acesso do usuário
$nivel = $_SESSION['nivel'] ?? 0;

// Realiza a pesquisa de fornecedores com base no nome ou material fornecido
$pesquisa = $_GET['PesquisarFornecedor'] ?? ''; // Obtém o termo de pesquisa do input
$sql = "SELECT id_fornecedor, nome_fornecedor, endereco_fornecedor, material_fornecedor, telefone_fornecedor FROM fornecedor WHERE nome_fornecedor LIKE ? OR id_fornecedor LIKE ? OR material_fornecedor LIKE ?";
$stmt = $conn->prepare($sql); // Prepara a consulta SQL
$likePesquisa = "%" . $pesquisa . "%"; // Adiciona o curinga "%" para pesquisa parcial
$stmt->bind_param("sss", $likePesquisa, $likePesquisa, $likePesquisa); // Vincula os parâmetros
$stmt->execute(); // Executa a consulta
$result = $stmt->get_result(); // Obtém o resultado da consulta
?>

<!-- HTML para exibição da página de fornecedores -->
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
        /* Estilos CSS para os elementos da página */
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
            border-radius: 10px;
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
        <?php if ($_SESSION['nivel'] == 1): // Apenas admin pode ver estas opções ?>
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>

<!-- Seção de navegação para voltar -->
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="voltarMenu()"></i>
    </div>   

    <!-- Título da página -->
    <section id="Titulo-Principal"><h1>Gerenciamento de Fornecedores</h1></section>

    <main id="container-main">
    <section id="teste">
    <br>
    <div style="margin: auto; display: flex; align-items: center;" class="elementos--itens">
        <!-- Formulário de pesquisa -->
        <form method="GET" action="fornecedores.php" id="formPesquisa" style="display: flex; align-items: center;">
            <i class="fa-solid fa-magnifying-glass" style="margin-right: 5px;"></i>
            <input type="text" id="PesquisarFornecedor" name="PesquisarFornecedor" placeholder="Pesquisar Fornecedor..." onkeypress="if(event.key === 'Enter') document.getElementById('formPesquisa').submit();">
        </form>
        <!-- Botão de adicionar fornecedor -->
        <button class="icon-btn" id="redirectBtn" style="margin-left: 10px;">
            <a class="fa-solid fa-plus" href="cadastrofornecedor.php" style="color: black; text-decoration: none;"></a>
        </button>
    </div>
    <br>
</section>

        <!-- Exibição dos fornecedores encontrados -->
        <section>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="fornecedor--item">
                        <span onclick="toggleEditForm(<?php echo $row['id_fornecedor']; ?>)" style="cursor: pointer;">
                            <strong>&gt;</strong> <?php echo $row['id_fornecedor']." - "; echo htmlspecialchars($row['nome_fornecedor']); ?> (<?php echo htmlspecialchars($row['material_fornecedor']); ?>)
                        </span>
                        <i class="fa-solid fa-trash" style="color: red;" onclick="confirmarExclusao(<?php echo $row['id_fornecedor']; ?>)"></i>
                    </div>
                    <!-- Formulário de edição do fornecedor -->
                    <div class="detalhes" id="edit-form-<?php echo $row['id_fornecedor']; ?>" style="display: none; margin-top: 10px;">
                        <form method="POST">
                            <input type="hidden" name="id_fornecedor" value="<?php echo $row['id_fornecedor']; ?>">
                            <label for="nome_fornecedor"><strong>Nome:</strong></label>
                            <input type="text" name="nome_fornecedor" class="inputs" value="<?php echo htmlspecialchars($row['nome_fornecedor']); ?>"><br>

                            <label for="endereco_fornecedor"><strong>Endereço:</strong></label>
                            <input type="text" name="endereco_fornecedor" class="inputs" value="<?php echo htmlspecialchars($row['endereco_fornecedor']); ?>"><br>

                            <label for="material_fornecedor"><strong>Material:</strong></label>
                            <input type="text" name="material_fornecedor" class="inputs" value="<?php echo htmlspecialchars($row['material_fornecedor']); ?>"><br>

                            <label for="telefone_fornecedor"><strong>Telefone:</strong></label>
                            <input type="text" name="telefone_fornecedor" class="inputs" value="<?php echo htmlspecialchars($row['telefone_fornecedor']); ?>"><br>

                            <button type="submit">Atualizar</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Não há fornecedores cadastrados.</p>
            <?php endif; ?>
        </section>
    </main>
    
    <!-- Função para confirmar a exclusão -->
    <script>
        function trocarPagina(url) {
                window.location.href = url;
            }
            
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

        function confirmarExclusao(id_fornecedor) {
            if (confirm("Você tem certeza que deseja excluir este fornecedor?")) {
                window.location.href = "fornecedores.php?delete_id=" + id_fornecedor;
            }
        }
        // Função para alternar a exibição do formulário de edição
        function toggleEditForm(id_fornecedor) {
            var form = document.getElementById("edit-form-" + id_fornecedor);
            form.style.display = (form.style.display === "none") ? "block" : "none";
        }
    </script>
</body>
</html>
