<?php
session_start(); // Inicia a sessão para acessar variáveis de sessão

// Configurações de conexão ao banco de dados
$servername = "localhost"; // Nome do servidor do banco de dados
$username = "root"; // Nome de usuário do banco de dados
$password = ""; // Senha do banco de dados
$dbname = "gerenciador_estoque"; // Nome do banco de dados

// Cria uma nova conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão falhou
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error); // Exibe erro e encerra o script
}

$preco = ""; // Inicializa a variável de preço
$message = ""; // Inicializa a variável de mensagem

// Verifica se o método de requisição é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $fornecedor = $_POST['fornecedor'];
    $produto_compra = $_POST['produto_compra'];
    $quantidade_compra = $_POST['quantidade_compra'];
    $preco_compra = $_POST['preco_compra'];
    $tipo_pagamento_compra = $_POST['tipo_pagamento_compra'];
    $data_compra = $_POST['data_compra'];
    $hora_compra = $_POST['hora_compra'];

    // Processamento do preço
    $preco = str_replace('.', '', $_POST['preco_compra']); // Remove o ponto
    $preco = str_replace(',', '.', $preco); // Troca a vírgula pelo ponto para conversão
    $quantidade = $_POST['quantidade_compra']; // Obtém a quantidade

    // Comando SQL para inserir uma nova compra
    $sql = "INSERT INTO compra (fk_id_fornecedor, produto_compra, quantidade_compra, preco_compra, tipo_pagamento_compra, data_compra, hora_compra) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql); // Prepara a instrução SQL
    // Liga os parâmetros à instrução preparada
    $stmt->bind_param("isissss", $fornecedor, $produto_compra, $quantidade_compra, $preco_compra, $tipo_pagamento_compra, $data_compra, $hora_compra);

    // Executa a instrução e verifica se a inserção foi bem-sucedida
    if ($stmt->execute()) {
        $message = "Compra cadastrada com sucesso!"; // Mensagem de sucesso
    } else {
        $message = "Erro ao cadastrar compra: " . $stmt->error; // Mensagem de erro
    }

    $stmt->close(); // Fecha a instrução preparada
}

$conn->close(); // Fecha a conexão com o banco de dados
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres para UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define a responsividade da página -->
    <meta http-equiv="X-UA-Compatible" content="IE=7"> <!-- Compatibilidade com Internet Explorer -->
    <link rel="stylesheet" href="css/cadastrocompras.css"> <!-- Link para o CSS da página de cadastro de compras -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"> <!-- Link para Font Awesome -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap"> <!-- Link para Google Fonts -->
    <title>Cadastro de Compras</title> <!-- Título da página -->
</head>
<body>
<header>
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()"> <!-- Logo da empresa que volta ao menu ao ser clicado -->
        <a href="estoque.php">Estoque</a> <!-- Link para a página de estoque -->
        <a href="fornecedores.php">Fornecedores</a> <!-- Link para a página de fornecedores -->
        <?php if ($_SESSION['nivel'] == 1): // Apenas admin pode ver estas opções ?>
            <a href="funcionarios.php">Funcionários</a> <!-- Link para a página de funcionários -->
            <a href="relatorio.php">Relatórios </a> <!-- Link para a página de relatórios -->
        <?php endif; ?>
        <a href="compras.php">Compras</a> <!-- Link para a página de compras -->
        <a href="vendas.php">Vendas</a> <!-- Link para a página de vendas -->
    </div>
</header>
<div class="botao--voltar"> <!-- Botão para voltar à página de compras -->
    <i class="fa-solid fa-arrow-left" onclick="trocarPagina('compras.php')"></i> <!-- Ícone de voltar -->
</div>    

<main id="container-main"> <!-- Início do conteúdo principal -->
    <section id="Titulo-Principal"><h1>Cadastro de Compras</h1></section> <!-- Título da seção de cadastro -->

    <?php if ($message): ?> <!-- Verifica se há uma mensagem para exibir -->
        <p><?php echo $message; ?></p> <!-- Exibe a mensagem -->
    <?php endif; ?>

    <form method="POST"> <!-- Formulário para cadastro de compras -->
        <section id="container-elementos"> <!-- Container para os elementos do formulário -->
            <div class="elementos--itens"> <!-- Seção para selecionar o fornecedor -->
                <i class="fa-solid fa-truck"></i> <!-- Ícone de fornecedor -->
                <select id="Fornecedor" name="fornecedor" required> <!-- Seleção de fornecedor -->
                    <option value="" disabled selected>Selecione o Fornecedor...</option> <!-- Opção padrão -->
                    <?php
                        // Carregar fornecedores do banco de dados
                        $conn = new mysqli($servername, $username, $password, $dbname); // Nova conexão
                        $sql_fornecedores = "SELECT id_fornecedor, nome_fornecedor FROM fornecedor"; // Consulta para obter fornecedores
                        $result_fornecedores = $conn->query($sql_fornecedores); // Executa a consulta
                        while ($linha = $result_fornecedores->fetch_assoc()) { // Itera sobre os resultados
                            echo "<option value='" . $linha["id_fornecedor"] . "'>" . $linha["nome_fornecedor"] . "</option>"; // Exibe cada fornecedor como opção
                        }
                        $conn->close(); // Fecha a conexão
                    ?>
                </select>
            </div>

            <div class="elementos--itens"> <!-- Seção para inserir o produto comprado -->
                <i class="fa-solid fa-cube"></i> <!-- Ícone de produto -->
                <input type="text" id="ProdutoCompra" name="produto_compra" placeholder="Produto comprado..." required> <!-- Campo para o nome do produto -->
            </div>

            <div class="elementos--itens"> <!-- Seção para inserir a quantidade comprada -->
                <i class="fa-solid fa-box"></i> <!-- Ícone de quantidade -->
                <input type="number" id="QuantidadeCompra" name="quantidade_compra" placeholder="Quantidade..." required> <!-- Campo para a quantidade -->
            </div>

            <div class="elementos--itens"> <!-- Seção para inserir o preço da compra -->
                <i class="fa-solid fa-hand-holding-dollar"></i>R$ <!-- Ícone de preço -->
                <input type="text" id="PrecoCompra" onkeypress="mascara(this, mreais)" oninput="validarPreco();" name="preco_compra" placeholder="Preço..." step="0.01" value="<?php echo htmlspecialchars($preco); ?>" required> <!-- Campo para o preço -->
            </div>

            <div class="elementos--itens"> <!-- Seção para selecionar o tipo de pagamento -->
                <i class="fa-solid fa-credit-card"></i> <!-- Ícone de pagamento -->
                <select id="TipoPagamentoCompra" name="tipo_pagamento_compra" required> <!-- Seleção de tipo de pagamento -->
                    <option value="" disabled selected>Selecione o Tipo de Pagamento...</option> <!-- Opção padrão -->
                    <option value="À vista">Dinheiro/À vista</option> <!-- Opção de pagamento à vista -->
                    <option value="PIX">PIX</option> <!-- Opção de pagamento via PIX -->
                    <option value="Parcelado">Crédito</option> <!-- Opção de pagamento parcelado -->
                    <option value="Débito">Débito</option> <!-- Opção de pagamento por débito -->
                    <option value="Boleto">Boleto</option> <!-- Opção de pagamento por boleto -->
                </select>
            </div>

            <div class="elementos--itens"> <!-- Seção para inserir a data da compra -->
                <i class="fa-solid fa-calendar-day"></i> <!-- Ícone de data -->
                <input type="date " id="DataCompra" name="data_compra" required> <!-- Campo para a data da compra -->
            </div>

            <div class="elementos--itens"> <!-- Seção para inserir a hora da compra -->
                <i class="fa-solid fa-clock"></i> <!-- Ícone de hora -->
                <input type="time" id="HoraCompra" name="hora_compra" required> <!-- Campo para a hora da compra -->
            </div>

            <div class="button"> <!-- Seção para o botão de cadastro -->
                <button type="submit">Cadastrar</button> <!-- Botão para enviar o formulário -->
            </div>
        </section>
    </form>
</main>

<script>
    function mascara(o, f) { // Função para aplicar máscara ao campo de preço
        v_obj = o // Armazena o input
        v_fun = f // Armazena a função de máscara
        setTimeout("execmascara()", 1) // Delay para aplicar a máscara
    }

    function execmascara() { // Função que executa a máscara
        v_obj.value = v_fun(v_obj.value) // Atualiza o valor do input com a máscara
    }

    function mreais(v) { // Função para formatar o valor como moeda
        v = v.replace(/\D/g, "") // Remove tudo que não é dígito
        v = v.replace(/(\d{2})$/, ",$1") // Coloca a vírgula antes dos últimos dois dígitos
        v = v.replace(/(\d+)(\d{3},\d{2})$/g, "$1.$2") // Coloca o ponto antes dos milhares
        return v // Retorna o valor formatado
    }

    function validarPreco() { // Função para validar o preço
        const precoInput = document.getElementById("PrecoProduto"); // Obtém o campo de preço
        const maxPreco = 1000; // Define o preço máximo

        let preco = precoInput.value.replace(",", "."); // Converte vírgula para ponto no valor

        if (parseFloat(preco) > maxPreco) { // Verifica se o preço é maior que o máximo
            alert("O preço não pode ser superior a R$1000."); // Alerta ao usuário
            precoInput.value = maxPreco.toFixed(2).replace(".", ","); // Reseta o campo para o máximo permitido
        }
    }

    function trocarPagina(url) { // Função para trocar de página
        window.location.href = url; // Redireciona para a URL especificada
    }

    function voltarMenu() { // Função para voltar ao menu
        const nivel = <?php echo isset($_SESSION['nivel']) ? $_SESSION['nivel'] : 'null'; ?>; // Obtém o nível do usuário da sessão
        if (nivel !== null) { // Verifica se o nível não é nulo
            if (nivel == 1) { // Se o nível for 1 (administrador)
                window.location.href = 'menuAdm.php'; // Redireciona para o menu do administrador
            } else if (nivel == 2) { // Se o nível for 2 (funcionário)
                window.location.href = 'menuFuncionario.php'; // Redireciona para o menu do funcionário
            }
        } else { // Se o nível for nulo
            alert('Sessão expirada. Faça login novamente.'); // Alerta de sessão expirada
            window.location.href = 'login.php'; // Redireciona para a página de login
        }
    }
</script>
</body>
</html>