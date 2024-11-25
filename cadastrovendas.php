<?php
// Inicia a sessão
session_start();

// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gerenciador_estoque";

// Cria a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error); // Exibe erro caso a conexão falhe
}

// Variável para exibir mensagens ao usuário
$message = "";

// Consulta para buscar os produtos disponíveis
$sql_produtos = "SELECT id_produto, nome_produto, quantidade_produto FROM produto";
$result_produtos = $conn->query($sql_produtos); // Executa a consulta

// Processa o formulário quando o método é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados enviados pelo formulário
    $tipoPagamento = $_POST['tipo'];
    $quantidade = $_POST['quantidade'];
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $produto_id = $_POST['produto'];

    // Verifica a quantidade disponível do produto no estoque
    $sql_estoque = "SELECT quantidade_produto FROM produto WHERE id_produto = ?";
    $stmt_estoque = $conn->prepare($sql_estoque); // Prepara a consulta
    $stmt_estoque->bind_param("i", $produto_id); // Vincula o ID do produto
    $stmt_estoque->execute(); // Executa a consulta
    $stmt_estoque->bind_result($quantidade_estoque); // Obtém o resultado
    $stmt_estoque->fetch(); // Recupera os dados
    $stmt_estoque->close(); // Fecha a consulta

    // Verifica se a quantidade solicitada está disponível
    if ($quantidade > $quantidade_estoque) {
        $message = "Erro: A quantidade solicitada excede o estoque disponível.";
    } else {
        // Insere a venda na tabela de vendas
        $sql_venda = "INSERT INTO venda (tipo_pagamento_venda, quantidade_venda, data_venda, hora_venda, fk_id_produto) VALUES (?, ?, ?, ?, ?)";
        $stmt_venda = $conn->prepare($sql_venda); // Prepara a consulta
        $stmt_venda->bind_param("ssssi", $tipoPagamento, $quantidade, $data, $horario, $produto_id);

        if ($stmt_venda->execute()) { // Executa a consulta de venda
            // Atualiza o estoque do produto
            $nova_quantidade = $quantidade_estoque - $quantidade;
            $sql_atualiza_estoque = "UPDATE produto SET quantidade_produto = ? WHERE id_produto = ?";
            $stmt_atualiza = $conn->prepare($sql_atualiza_estoque); // Prepara a consulta
            $stmt_atualiza->bind_param("ii", $nova_quantidade, $produto_id);
            $stmt_atualiza->execute(); // Executa a atualização
            $stmt_atualiza->close(); // Fecha a consulta
            $message = "Venda cadastrada com sucesso!";
        } else {
            $message = "Erro ao cadastrar a venda.";
        }
        $stmt_venda->close(); // Fecha a consulta de venda
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Metadados da página -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Estilos e ícones -->
    <link rel="stylesheet" href="css/cadastrovendas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Cadastro de Vendas</title>
</head>
<body>
<header>
    <!-- Cabeçalho com navegação -->
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
        <a href="estoque.php">Estoque</a>
        <a href="fornecedores.php">Fornecedores</a>
        <!-- Exibe opções administrativas apenas para usuários de nível 1 -->
        <?php if ($_SESSION['nivel'] == 1): ?>
            <a href="funcionarios.php">Funcionários</a>
            <a href="relatorio.php">Relatórios</a>
        <?php endif; ?>
        <a href="compras.php">Compras</a>
        <a href="vendas.php">Vendas</a>
    </div>
</header>
<div class="botao--voltar">
    <!-- Botão para voltar -->
    <i class="fa-solid fa-arrow-left" onclick="trocarPagina('vendas.php')" aria-label="Voltar"></i>
</div>   
<main id="container-main">
    <section id="Titulo-Principal"><h1>Cadastro de Vendas</h1></section>

    <!-- Mensagem de feedback -->
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Formulário para cadastro de vendas -->
    <form action="cadastrovendas.php" method="POST">
        <section id="container-elementos">
            <!-- Campo para tipo de pagamento -->
            <div class="elementos--itens">
                <i class="fa-solid fa-money-check-dollar" aria-label="Ícone de ID"></i>
                <select id="tipo" name="tipo" required>
                    <option value="" disabled selected>Selecione o Tipo de Pagamento...</option>
                    <option value="À vista">Dinheiro/À vista</option>
                    <option value="PIX">PIX</option>
                    <option value="Parcelado">Crédito</option>
                    <option value="Débito">Débito</option>
                    <option value="Boleto">Boleto</option>
                </select>
            </div>
            <!-- Campo para quantidade -->
            <div class="elementos--itens">
                <i class="fa-solid fa-boxes-stacked" aria-label="Ícone de Material"></i>
                <input type="number" id="Quantidade" name="quantidade" placeholder="Quantidade..." required>
            </div>
            <!-- Campo para data -->
            <div class="elementos--itens">
                <i class="fa-regular fa-calendar-days" aria-label="Ícone de Data"></i>
                <input type="date" id="data" name="data" placeholder="Data da Venda..." maxlength="10" required>
            </div>
            <!-- Campo para horário -->
            <div class="elementos--itens">
                <i class="fa-regular fa-clock" aria-label="Ícone de Horário"></i>
                <input type="time" id="Horario" name="horario" placeholder="Horário da Venda..." required>
            </div>
            <!-- Campo para seleção de produto -->
            <div class="elementos--itens">
                <i class="fa-solid fa-box-open" aria-label="Ícone de Produto"></i>
                <select id="Produto" name="produto" required>
                    <option value="">Selecione um produto</option>
                    <?php while ($linha = $result_produtos->fetch_assoc()): ?>
                        <option value="<?php echo $linha['id_produto']; ?>">
                            <?php echo htmlspecialchars($linha['nome_produto']) . " - Estoque: " . $linha['quantidade_produto']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <!-- Botão de envio -->
            <div class="button">
                <button type="submit">Cadastrar</button>
            </div>
        </section>
    </form>
</main>
<script>
    // Funções de navegação
    function trocarPagina(url) {
        console.log("Tentando navegar para:", url);
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
