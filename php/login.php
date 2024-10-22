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

    $conn = new mysqli($servername, $username, $password, $dbname); 
    if ($conn->connect_error) {
        exit();
    }

    $usuario = $_POST['usuario']; 
    $senha = $_POST['senha']; 

    $sql = "SELECT * FROM usuario WHERE usuario = '$usuario' AND senha = '$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); 
        $nome = $row['nome']; 
        $nivel = $row['nivel']; 
        $_SESSION['nome'] = $nome;
        
        if ($nivel == 1) { 
            header("Location: /Desenvolvimento_SA/menuAdm.html"); 
        } elseif ($nivel == 2) {
            header("Location: /Desenvolvimento_SA/menuFuncionario.html");  
        }
        } else {
            echo "Erro!";
        }
    $conn->close(); 
    ?>