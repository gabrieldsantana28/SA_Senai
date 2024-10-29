<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nossasa";

// CONEXÃO
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$message = "";

// Lógica para editar um funcionário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "UPDATE usuario SET nome=?, usuario=?, email=?, senha=? WHERE id_usuario=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nome, $usuario, $email, $senha, $id_usuario);

    if ($stmt->execute()) {
        $message = "Funcionário atualizado com sucesso!";
    } else {
        $message = "Erro ao atualizar funcionário: " . $stmt->error;
    }

    $stmt->close();

    $sql = "INSERT INTO usuario (nome, usuario, email, senha, nivel) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nome, $usuario, $email, $senha, $nivel);

    if ($stmt->execute()) {
        $message = "Funcionário cadastrado com sucesso!";
    } else {
        $message = "Erro ao cadastrar funcionário: " . $stmt->error;
    }

    $stmt->close();
}

// Lógica para excluir um funcionário
if (isset($_GET['excluir'])) {
    $id_usuario = $_GET['excluir'];
    $sql = "DELETE FROM usuario WHERE id_usuario=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);

    if ($stmt->execute()) {
        $message = "Funcionário excluído com sucesso!";
    } else {
        $message = "Erro ao excluir funcionário: " . $stmt->error;
    }

    $stmt->close();
}

// Recupera nível da conta
$nivel = $_SESSION['nivel'] ?? 0; // Defina nível da conta como 0 caso não esteja logado

$pesquisa = $_POST['PesquisarFuncionario'] ?? '';

// Pesquisa por usuário ou nome
$sql = "SELECT id_usuario, nome, usuario, email, senha, nivel FROM usuario WHERE usuario LIKE ? OR nome LIKE ?";
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
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/fornecedores.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Desenvolvimento de Sistemas</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap');
        .fornecedor--item {
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

        .inputs{
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
            <a href="compras.php">Compras</a>
            <a href="fornecedores.php">Fornecedores</a>
            <a href="cadastroprodutos.php">CadasProdutos</a>
            <a href="vendas.php">Vendas</a>
            <a href="relatorio.php">Relatórios</a>
        </div>
    </header>
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('menuAdm.php')"></i>
    </div>      

    <section id="Titulo-Principal"><h1>Gerenciamento de Funcionários</h1></section>

    <main id="container-main">
        <section>
            <br>
            <form method="POST" action="">
                <div style="margin: auto;" class="elementos--itens">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="PesquisarFuncionario" name="PesquisarFuncionario" placeholder="Pesquisar Funcionário...">
                    <button class="icon-btn" id="redirectBtn">
                        <a class="fa-solid fa-plus" href="cadastrofuncionarios.php"></a>
                    </button>
                </div>
            </form>
            <br>
        </section>

        <section>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="fornecedor--item">
                        <span onclick="toggleDetalhes(<?php echo $row['id_usuario']; ?>)" style="cursor: pointer;">
                            <strong>&gt;</strong> <?php echo htmlspecialchars($row['usuario']); ?> <?php echo htmlspecialchars($row['nome']); ?> (<?php echo $row['nivel'] == 1 ? 'Administrador' : 'Funcionário'; ?>)
                        </span>
                        <i class="fa-solid fa-trash" style="color: red;" onclick="confirmarExclusao(<?php echo $row['id_usuario']; ?>)"></i>
                    </div>
                    <div class="detalhes" id="detalhes-<?php echo $row['id_usuario']; ?>">
                        <form method="POST">
                            <input type="hidden" name="id_usuario" value="<?php echo $row['id_usuario']; ?>">
                            <p><strong>Nome:</strong> <input class="inputs" type="text" name="nome" value="<?php echo htmlspecialchars($row['nome']); ?>"></p>
                            <p><strong>Usuário:</strong> <input class="inputs" type="text" name="usuario" value="<?php echo htmlspecialchars($row['usuario']); ?>"></p>
                            <p><strong>Email:</strong> <input class="inputs" type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>"></p>
                            <p><strong>Senha:</strong> <input class="inputs" type="password" name="senha" value="<?php echo htmlspecialchars($row['senha']); ?>"></p>
                            <button type="submit">Salvar</button>
                        </form>
                    </div>
                    <br>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center">Nenhum funcionário cadastrado.</p>
            <?php endif; ?>
        </section>
    </main>
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

        function toggleDetalhes(id) {
            const detalhesDiv = document.getElementById('detalhes-' + id);
            detalhesDiv.style.display = detalhesDiv.style.display === 'block' ? 'none' : 'block';
        }

        function confirmarExclusao(id) {
            if (confirm('Tem certeza que deseja excluir este funcionário?')) {
                window.location.href = '?excluir=' + id;
            }
        }
    </script>
</body>
</html>
