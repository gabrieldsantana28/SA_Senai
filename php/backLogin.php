<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0"); 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gerenciador_estoque";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario']) && isset($_POST['senha'])) {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Consulta para verificar as credenciais do usuário
    $sql = "SELECT * FROM usuario WHERE user_usuario = ? AND senha_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nome = $row['nome_usuario'];
        $nivel = $row['nivel_usuario'];

        $_SESSION['usuario'] = $usuario;
        $_SESSION['nome'] = $nome;
        $_SESSION['nivel'] = $nivel;

        if ($nivel == 1) {
            header("Location: /002 - Turma_2o_Semestre_2024/SA_Senai/menuAdm.php");
        } elseif ($nivel == 2) {
            header("Location: /002 - Turma_2o_Semestre_2024/SA_Senai/menuFuncionario.php");
        }
        exit();
    } else {
        $_SESSION['login_error'] = "Usuário ou senha incorretos";
        header("Location: /002 - Turma_2o_Semestre_2024/SA_Senai/index.php");
        exit();
    }

} else {
    // Redireciona para a página de login se o método POST não for usado
    header("Location: /002 - Turma_2o_Semestre_2024/SA_Senai/index.php");
    exit();
}
?>
