<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gerenciador_estoque";

    // Conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $message = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $usuario = $_POST['usuario'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $nivel = $_POST['nivel']; 

        $sql = "INSERT INTO usuario (nome_usuario, user_usuario, email_usuario, senha_usuario, nivel_usuario) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ssssi", $nome, $usuario, $email, $senha, $nivel); 

        if ($stmt->execute()) {
            $message = "Funcionário cadastrado com sucesso!";
        } else {
            $message = "Erro ao cadastrar funcionário: " . $stmt->error;
        }

        $stmt->close();
    }

    // RECUPERA NÍVEL DA CONTA 
    $nivel = $_SESSION['nivel'] ?? 0; // NÍVEL DA CONTA EM 0 CASO NÃO ESTEJA LOGADO

    $conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/cadastrofuncionarios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Cadastro de Funcionários</title>
</head>
<body>
<header>
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($_SESSION['nivel'] == 1): // Apenas admin pode ver estas opções ?>
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('funcionarios.php')"></i>
    </div>    
    
    <main id="container-main">
        <section id="Titulo-Principal"><h1>Cadastro de Funcionários</h1></section>
        
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <section id="container-elementos">
                <div class="elementos--itens">
                    <i class="fas fa-user-tag"></i>
                    <input type="text" id="NomeFuncionario" name="nome" placeholder="Nome do Funcionário..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="Usuario" name="usuario" placeholder="Nome de usuário..." required>
                </div>
                <div class="elementos--itens">
                    <i class='fa-solid fa-envelope'></i>
                    <input type="email" id="Email" name="email" placeholder="Email..." required>
                </div>
                <div class="elementos--itens">
                    <i class='fa-solid fa-lock'></i>
                    <input type="password" id="Senha" name="senha" placeholder="Senha (Mín. 6 Caracteres)..." minlength="6" required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-user-tie" aria-label="Ícone de Nível"></i>
                    <select id="Nivel" name="nivel" required>
                        <option value="">Selecione o Nível...</option>
                        <option value="1">1 - Admin</option>
                        <option value="2">2 - Funcionário</option>
                    </select>
                </div>
                <div class="button">
                    <button type="submit">Cadastrar</button>
                </div>
            </section>
        </form>
    </main>
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
                window.location.href = 'login.php';
            }
        }
    </script>
</body>
</html>
