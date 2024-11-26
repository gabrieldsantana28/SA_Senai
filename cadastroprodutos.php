<?php
session_start(); // Inicia a sessão para acessar as variáveis de sessão

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

// Inicializa variáveis para armazenar dados do formulário
$message = ""; // Mensagem de feedback para o usuário
$nome = ""; // Nome do produto
$descricao = ""; // Descrição do produto
$tamanho = ""; // Tamanho do produto
$cor = ""; // Cor do produto
$preco = ""; // Preço do produto
$quantidade = ""; // Quantidade do produto

// Verifica se o método de requisição é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $tamanho = $_POST['tamanho'];
    $cor = $_POST['cor'];

    // Processamento do preço
    $preco = str_replace('.', '', $_POST['preco']); // Remove o ponto do preço
    $preco = str_replace(',', '.', $preco); // Troca a vírgula pelo ponto para conversão
    $quantidade = $_POST['quantidade']; // Obtém a quantidade

    // Verifica se o preço excede o limite
    if ($preco > 1000) {
        $message = "Erro: O preço não pode exceder R$1000."; // Mensagem de erro se o preço for maior que 1000
    } else {
        // Comando SQL para inserir um novo produto
        $sql = "INSERT INTO produto (nome_produto, descricao_produto, tamanho_produto, cor_produto, preco_produto, quantidade_produto) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql); // Prepara a instrução SQL

        // Liga os parâmetros à instrução preparada
        $stmt->bind_param("ssssdi", $nome, $descricao, $tamanho, $cor, $preco, $quantidade);

        // Executa a instrução e verifica se a inserção foi bem-sucedida
        if ($stmt->execute()) {
            $message = "Produto cadastrado com sucesso!"; // Mensagem de sucesso
            // Limpa os campos após o sucesso
            $nome = "";
            $descricao = "";
            $tamanho = "";
            $cor = "";
            $preco = "";
            $quantidade = "";
        } else {
            $message = "Erro ao cadastrar produto: " . $stmt->error; // Mensagem de erro
        }

        $stmt->close(); // Fecha a instrução preparada
    }
}

$conn->close(); // Fecha a conexão com o banco de dados
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres para UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define a responsividade da página -->
    <meta http-equiv="X-UA-Compatible" content="IE=7"> <!-- Compatibilidade com Internet Explorer -->
    <link rel="stylesheet" href="css/cadastroprodutos.css"> <!-- Link para o CSS da página de cadastro de produtos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"> <!-- Link para Font Awesome -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap"> <!-- Link para Google Fonts -->
    <title>Cadastro de Produtos</title> <!-- Título da página -->
</head>
<body>
<header>
    <div class="hdr">
        <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()"> <!-- Logo da empresa que volta ao menu ao ser clicado -->
        <a href="estoque.php">Estoque</a> <!-- Link para a página de estoque -->
        <a href="for necedores.php">Fornecedores</a> <!-- Link para a página de fornecedores -->
        <?php if ($_SESSION['nivel'] == 1): // Apenas admin pode ver estas opções ?>
            <a href="funcionarios.php">Funcionários</a> <!-- Link para a página de funcionários -->
            <a href="relatorio.php">Relatórios</a> <!-- Link para a página de relatórios -->
        <?php endif; ?>
        <a href="compras.php">Compras</a> <!-- Link para a página de compras -->
        <a href="vendas.php">Vendas</a> <!-- Link para a página de vendas -->
    </div>
</header>
    <div class="botao--voltar"> <!-- Botão para voltar à página de estoque -->
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('estoque.php')"></i> <!-- Ícone de voltar -->
    </div>

    <main id="container-main"> <!-- Início do conteúdo principal -->
        <section id="Titulo-Principal"><h1>Cadastro de Produtos</h1></section> <!-- Título da seção de cadastro -->

        <?php if (!empty($message)): ?> <!-- Verifica se há uma mensagem para exibir -->
            <div class="message"><?php echo $message; ?></div> <!-- Exibe a mensagem -->
        <?php endif; ?>

        <form action="cadastroprodutos.php" method="POST"> <!-- Formulário para cadastro de produtos -->
            <section id="container-elementos"> <!-- Container para os elementos do formulário -->
                <div class="elementos--itens"> <!-- Seção para inserir o nome do produto -->
                    <i class="fa-solid fa-boxes-stacked"></i> <!-- Ícone de produto -->
                    <input type="text" id="NomeProduto" name="nome" placeholder="Nome do Produto..." value="<?php echo htmlspecialchars($nome); ?>" required> <!-- Campo para o nome do produto -->
                </div>
                <div class="elementos--itens"> <!-- Seção para inserir a descrição do produto -->
                    <i class="fa-solid fa-comment-dots"></i> <!-- Ícone de descrição -->
                    <input type="text" id="DescProduto" name="descricao" placeholder="Descrição do Produto..." value="<?php echo htmlspecialchars($descricao); ?>" required> <!-- Campo para a descrição do produto -->
                </div>
                <div class="elementos--itens"> <!-- Seção para inserir o tamanho do produto -->
                    <i class="fa-solid fa-maximize"></i> <!-- Ícone de tamanho -->
                    <input type="text" id="TamProduto" name="tamanho" placeholder="Tamanho do Produto..." value="<?php echo htmlspecialchars($tamanho); ?>" required> <!-- Campo para o tamanho do produto -->
                </div>
                <div class="elementos--itens"> <!-- Seção para inserir a cor do produto -->
                    <i class="fa-solid fa-palette"></i> <!-- Ícone de cor -->
                    <input type="text" id="CorProduto" name="cor" placeholder="Cor do Produto..." value="<?php echo htmlspecialchars($cor); ?>" required> <!-- Campo para a cor do produto -->
                </div>
                <div class="elementos--itens"> <!-- Seção para inserir o preço do produto -->
                    <i class="fa-solid fa-hand-holding-dollar"></i>R$ <!-- Ícone de preço -->
                    <input type="text" id="PrecoProduto" onkeypress="mascara(this, mreais)" oninput="validarPreco();" name="preco" placeholder="Preço do Produto..." step="0.01" value="<?php echo htmlspecialchars($preco); ?>" required> <!-- Campo para o preço do produto -->
                </div>
                <div class="elementos--itens"> <!-- Seção para inserir a quantidade do produto -->
                    <i class="fa-solid fa-arrow-up-short-wide"></i> <!-- Ícone de quantidade -->
                    <input type="number" id="QuantProduto" name="quantidade" placeholder="Quantidade do Produto..." max="9999" value="<?php echo htmlspecialchars($quantidade); ?>" required> <!-- Campo para a quantidade do produto -->
                </div>
                <div class="button"> <!-- Seção para o botão de confirmação -->
                    <button type="submit">Confirmar</button> <!-- Botão para enviar o formulário -->
                </div>
            </section>
        </form>
    </main>
</body>
</html>
<script>
        function trocarPagina(url) { // Função para trocar de página
            console.log("Tentando navegar para:", url); // Log da URL para depuração
            window.location.href = url; // Redireciona para a URL especificada
        }

        function mascara(o, f) { // Função para aplicar máscara ao input
            v_obj = o // Armazena o input
            v_fun = f // Armazena a função de máscara
            setTimeout("execmascara()", 1) // Delay para aplicar a máscara
        }

        function execmascara() { // Executa a máscara
            v_obj.value = v_fun(v_obj.value) // Atualiza o valor do input com a máscara
        }

        function mreais(v) { // Função para formatar o valor como moeda
            v = v.replace(/\D/g, "") // Remove tudo que não é dígito
            v = v.replace(/(\d{2})$/, ",$1") // Adiciona vírgula antes dos últimos dois dígitos
            v = v.replace(/(\d+)(\d{3},\d{2})$/g, "$1.$2") // Adiciona ponto antes dos milhares
            return v // Retorna o valor formatado
        }

        function validarPreco() { // Função para validar o preço
            const precoInput = document.getElementById("PrecoProduto"); // Obtém o input de preço
            const maxPreco = 1000; // Define o preço máximo

            let preco = precoInput.value.replace(",", "."); // Converte vírgula para ponto no valor

            if (parseFloat(preco) > maxPreco) { // Verifica se o preço excede o máximo
                alert("O preço não pode ser superior a R$1000."); // Alerta de erro
                precoInput.value = maxPreco.toFixed(2).replace(".", ","); // Reseta o valor para o máximo permitido
            }
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