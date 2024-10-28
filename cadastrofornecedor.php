<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nossasa";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $material = $_POST['material'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    $sql = "INSERT INTO fornecedor (nome_fornecedor, materialFornecido, telefone_fornecedor, endereco_fornecedor) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ssss", $nome, $material, $telefone, $endereco);

    if ($stmt->execute()) {
        $message = "Fornecedor cadastrado com sucesso!";
    } else {
        $message = "Erro ao cadastrar fornecedor: " . $stmt->error;
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/cadastrofornecedor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Cadastro de Fornecedores</title>
    <script>
        function mascara(telefone){ 
            if(telefone.value.length == 0)
                telefone.value = '(' + telefone.value; //quando começamos a digitar, o script irá inserir um parênteses no começo do campo.
            if(telefone.value.length == 3)
                telefone.value = telefone.value + ') '; //quando o campo já tiver 3 caracteres (um parênteses e 2 números) o script irá inserir mais um parênteses, fechando assim o código de área.
            if(telefone.value.length == 10)
                telefone.value = telefone.value + '-'; //quando o campo já tiver 8 caracteres, o script irá inserir um tracinho, para melhor visualização do telefone.
        }   
    </script>
</head>
<body>
    <header>
        <div class="hdr">
            <img class="logo-header" src="./images/comp.png" alt="LOGO" onclick="voltarMenu()">
            <a href="estoque.php">Gerenciamento de Estoque</a>
            <a href="funcionarios.php">Gerenciamento de Funcionários</a>
            <a href="fornecedores.php">Gerenciamento de Fornecedores</a>
            <a href="cadastroprodutos.php">Cadastro de Produtos</a>
            <a href="vendas.php">Controle de Vendas</a>
            <a href="relatorio.php">Relatórios</a>
        </div>
    </header>
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('fornecedores.php')" aria-label="Voltar"></i>
    </div>   
    <main id="container-main">
        <section id="Titulo-Principal"><h1>Cadastro de Fornecedores</h1></section>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="cadastrofornecedor.php" method="POST">
            <section id="container-elementos">
                <div class="elementos--itens">
                    <i class="fas fa-id-badge" aria-label="Ícone de ID"></i>
                    <input type="text" id="NomeFornecedor" name="nome" placeholder="Nome do Fornecedor..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-boxes-stacked" aria-label="Ícone de Material"></i>
                    <input type="text" id="MatFornecido" name="material" placeholder="Material Fornecido..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-phone" aria-label="Ícone de Telefone"></i>
                    <input type="text" id="telefone" name="telefone" placeholder="Telefone..."  required maxlength="15" onkeypress="mascara(this)">
                </div>
                <div class="elementos--itens">
                    <i class="fas fa-map-marker-alt" aria-label="Ícone de Endereço"></i>
                    <input type="text" id="Endereco" name="endereco" placeholder="Endereço..." required>
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
