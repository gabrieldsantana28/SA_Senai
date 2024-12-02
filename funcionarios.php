<?php
    session_start();
    // Inicia a sessão para que informações como o nível de acesso do usuário estejam disponíveis.

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gerenciador_estoque";

    // Cria uma conexão com o banco de dados usando as credenciais fornecidas.
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica se a conexão foi bem-sucedida.
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
        // Encerra o script caso a conexão falhe.
    }

    $conn->set_charset("utf8");

    $message = "";
    // Inicializa a variável para armazenar mensagens de sucesso ou erro.

    // Verifica se o método de requisição é POST e se um ID de usuário foi enviado para edição.
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_usuario'])) {
        $id_usuario = $_POST['id_usuario'];
        $nome = $_POST['nome'];
        $usuario = $_POST['usuario'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        // Atualiza os dados do funcionário no banco.
        $sql = "UPDATE usuario SET nome_usuario=?, user_usuario=?, email_usuario=?, senha_usuario=? WHERE id_usuario=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nome, $usuario, $email, $senha, $id_usuario);

        // Verifica se a atualização foi bem-sucedida.
        if ($stmt->execute()) {
            $message = "Funcionário atualizado com sucesso!";
        } else {
            $message = "Erro ao atualizar funcionário: " . $stmt->error;
        }

        $stmt->close();
    }

    // Verifica se um ID de usuário foi enviado para exclusão via GET.
    if (isset($_GET['excluir'])) {
        $id_usuario = $_GET['excluir'];
        $sql = "DELETE FROM usuario WHERE id_usuario=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);

        // Tenta excluir o funcionário.
        if ($stmt->execute()) {
            $message = "Funcionário excluído com sucesso!";
        } else {
            $message = "Erro ao excluir funcionário: " . $stmt->error;
        }

        $stmt->close();
    }

    $nivel = $_SESSION['nivel'] ?? 0; 
    // Obtém o nível do usuário da sessão. Caso não esteja definido, o padrão é 0.

    $pesquisa = $_POST['PesquisarFuncionario'] ?? '';
    // Captura o valor digitado no campo de pesquisa.

    // Consulta para buscar funcionários no banco de dados com base no valor pesquisado.
    $sql = "SELECT id_usuario, nome_usuario, user_usuario, email_usuario, senha_usuario, nivel_usuario FROM usuario WHERE user_usuario LIKE ? OR nome_usuario LIKE ? OR id_usuario LIKE ?";
    $stmt = $conn->prepare($sql);
    $likePesquisa = "%" . $pesquisa . "%";
    $stmt->bind_param("sss", $likePesquisa, $likePesquisa, $likePesquisa);
    $stmt->execute();
    $result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <link rel="stylesheet" href="css/funcionarios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Desenvolvimento de Sistemas</title>
</head>
<body>
<header>
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <?php if ($_SESSION['nivel'] == 1): // Apenas administradores podem ver essas opções. ?>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('menuAdm.php')"></i>
    </div>      

    <section id="Titulo-Principal"><h1>Gerenciamento de Funcionários</h1></section>

    <main id="container-main">
        <section>
            <br>
            <!-- Formulário para pesquisa de funcionários. -->
            <form method="POST" action="">
                <div style="margin: auto;" class="elementos--itens">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="PesquisarFuncionario" name="PesquisarFuncionario" placeholder="Pesquisar Funcionário...">
                    <button type="submit" class="icon-btn" id="redirectBtn">
                        <a class="fa-solid fa-plus" href="cadastrofuncionarios.php"></a>
                    </button>
                </div>
            </form>
            <br>
        </section>

        <section>
            <!-- Exibe a lista de funcionários ou uma mensagem se não houver resultados. -->
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="fornecedor--item">
                        <!-- Exibe o ID e o nome do funcionário, com opção para expandir os detalhes. -->
                        <span onclick="toggleDetalhes(<?php echo $row['id_usuario']; ?>)" style="cursor: pointer;">
                            <strong>&gt;</strong> <?php echo $row['id_usuario']." - "; echo htmlspecialchars($row['nome_usuario']); ?> (<?php echo $row['nivel_usuario'] == 1 ? 'Administrador' : 'Funcionário'; ?>)
                        </span>
                        <!-- Ícone para excluir o funcionário. -->
                        <i class="fa-solid fa-trash" style="color: red;" onclick="confirmarExclusao(<?php echo $row['id_usuario']; ?>)"></i>
                    </div>
                    <!-- Formulário de edição que aparece ao expandir os detalhes. -->
                    <div class="detalhes" id="detalhes-<?php echo $row['id_usuario']; ?>">
                        <form method="POST">
                            <input type="hidden" name="id_usuario" value="<?php echo $row['id_usuario']; ?>">
                            <p><strong>Nome:</strong> <input class="inputs" type="text" name="nome" value="<?php echo htmlspecialchars($row['nome_usuario']); ?>"></p>
                            <p><strong>Usuário:</strong> <input class="inputs" type="text" name="usuario" value="<?php echo htmlspecialchars($row['user_usuario']); ?>"></p>
                            <p><strong>Email:</strong> <input class="inputs" type="email" name="email" value="<?php echo htmlspecialchars($row['email_usuario']); ?>"></p>
                            <p><strong>Senha:</strong> <input class="inputs" type="password" name="senha" value="<?php echo htmlspecialchars($row['senha_usuario']); ?>"></p>
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
            // Redireciona para uma página específica.
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

        function toggleDetalhes(id) {
            const detalhesDiv = document.getElementById('detalhes-' + id);
            detalhesDiv.style.display = detalhesDiv.style.display === 'block' ? 'none' : 'block';
            // Alterna a exibição dos detalhes do funcionário (abre e fecha).
        }

        function confirmarExclusao(id) {
            if (confirm('Tem certeza que deseja excluir este funcionário?')) {
                window.location.href = '?excluir=' + id;
                // Exibe uma mensagem de confirmação antes de excluir o funcionário.
            }
        }
    </script>
</body>
</html>
