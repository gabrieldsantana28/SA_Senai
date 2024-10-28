<?php
session_start();
// Evita que a página seja armazenada em cache
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// CONEXÃO COM O BANCO DE DADOS
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nossasa";

// Conecta ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se os campos de usuário e senha foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario']) && isset($_POST['senha'])) {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Consulta segura usando prepared statements para evitar SQL injection
    $sql = "SELECT * FROM usuario WHERE usuario = ? AND senha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $senha); // 'ss' indica que ambos os parâmetros são strings
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nome = $row['nome'];
        $nivel = $row['nivel'];

        // Armazena o nome e o nível do usuário na sessão
        $_SESSION['nome'] = $nome;
        $_SESSION['nivel'] = $nivel;

        // Redireciona com base no nível do usuário
        if ($nivel == 1) {
            header("Location: /GitHub/SA_Senai/menuAdm.php");
        } elseif ($nivel == 2) {
            header("Location: /GitHub/SA_Senai/menuFuncionario.php");
        }
        exit();
    } else {
        // Usuário ou senha incorretos
        $_SESSION['login_error'] = "Usuário ou senha incorretos";
        header("Location: /login.php");
        exit();
    }

} else {
    // Redireciona para a página de login se o método POST não for usado
    header("Location: /login.php");
    exit();
}
?>
