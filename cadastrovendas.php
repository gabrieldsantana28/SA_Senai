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

    $sql_produtos = "SELECT id_produto, nome_produto FROM produto";
    $result_produtos = $conn->query($sql_produtos);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $tipoPagamento = $_POST['tipo'];
        $quantidade = $_POST['quantidade'];
        $data = $_POST['data'];
        $horario = $_POST['horario'];
        $produto_id = $_POST['produto'];

        $sql_nome_produto = "SELECT nome_produto FROM produto WHERE id_produto = ?";
        $stmt_produto = $conn->prepare($sql_nome_produto);
        $stmt_produto->bind_param("i", $produto_id);
        $stmt_produto->execute();
        $stmt_produto->bind_result($nome_produto);
        $stmt_produto->fetch();
        $stmt_produto->close();

        $sql_venda = "INSERT INTO venda (tipo_pagamento, quantidade, data_venda, hora_venda, produto_venda) VALUES (?, ?, ?, ?, ?)";
        $stmt_venda = $conn->prepare($sql_venda);
        $stmt_venda->bind_param("sssss", $tipoPagamento, $quantidade, $data, $horario, $nome_produto);

        if ($stmt_venda->execute()) {
          if ($stmt_venda->affected_rows > 0) {
              $message = "Venda cadastrada com sucesso!";
          } else {
              $message = "Erro ao cadastrar venda: Nenhuma linha foi afetada.";
          }
      } else {
          $message = "Erro ao cadastrar venda: " . $stmt_venda->error;
      }
      
      $stmt_venda->close();
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
    <link rel="stylesheet" href="css/cadastrovendas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@100;400;600;900&display=swap">
    <title>Cadastro de Vendas</title>
</head>
<body>
    <header>
        <div class="hdr">
            <img class="logo-header" src="./images/comp.png" alt="LOGO">
            <a href="#" onclick="voltarMenu()">Menu</a>
            <a href="estoque.php">Gerenciamento de Estoque</a>
            <a href="funcionarios.php">Gerenciamento de Funcionários</a>
            <a href="fornecedores.php">Gerenciamento de Fornecedores</a>
            <a href="cadastroprodutos.php">Cadastro de Produtos</a>
            <a href="vendas.php">Controle de Vendas</a>
            <a href="relatorio.php">Relatórios</a>
        </div>
    </header>
    <div class="botao--voltar">
        <i class="fa-solid fa-arrow-left" onclick="trocarPagina('vendas.php')" aria-label="Voltar"></i>
    </div>   
    <main id="container-main">
        <section id="Titulo-Principal"><h1>Cadastro de Vendas</h1></section>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="cadastrovendas.php" method="POST">
            <section id="container-elementos">
                <div class="elementos--itens">
                    <i class="fa-solid fa-money-check-dollar" aria-label="Ícone de ID"></i>
                    <input type="text" id="Tipo" name="tipo" placeholder="Tipo de Pagamento..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-boxes-stacked" aria-label="Ícone de Material"></i>
                    <input type="number" id="Quantidade" name="quantidade" placeholder="Quantidade..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-regular fa-calendar-days" aria-label="Ícone de Data"></i>
                    <input type="text" id="data" name="data" placeholder="Data da Venda..." maxlength="10">
                </div>
                <div class="elementos--itens">
                    <i class="fa-regular fa-clock" aria-label="Ícone de Horário"></i>
                    <input type="text" id="Horario" name="horario" placeholder="Horário da Venda..." required>
                </div>
                <div class="elementos--itens">
                    <i class="fa-solid fa-box-open" aria-label="Ícone de Produto"></i>
                    <select id="Produto" name="produto" required>
                        <option value="">Selecione um Produto...</option>
                        <?php if ($result_produtos->num_rows > 0): ?>
                            <?php while ($row = $result_produtos->fetch_assoc()): ?>
                                <option value="<?php echo $row['id_produto']; ?>"><?php echo $row['nome_produto']; ?></option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="">Nenhum produto disponível</option>
                        <?php endif; ?>
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
          <?php if ($nivel == 1): ?>
              window.location.href = 'menuAdm.php';
          <?php elseif ($nivel == 2): ?>
              window.location.href = 'menuFuncionario.php';
          <?php else: ?>
              alert('Nível de conta não identificado. Faça login novamente.');
              window.location.href = 'login.php'; 
          <?php endif; ?>
      }

      // FUNÇÃO MÁSCARA DATA 
      function mascaraData(val) {
          var pass = val.value;
          var expr = /[0123456789]/;

          for (i = 0; i < pass.length; i++) {
          var lchar = val.value.charAt(i);
          var nchar = val.value.charAt(i + 1);

          if (i == 0) {
              if ((lchar.search(expr) != 0) || (lchar > 3)) {
                  val.value = "";
                }
          } else if (i == 1) {
                if (lchar.search(expr) != 0) {
                  var tst1 = val.value.substring(0, (i));
                    val.value = tst1;
                    continue;
                }

        if ((nchar != '/') && (nchar != '')) {
          var tst1 = val.value.substring(0, (i) + 1);
          if (nchar.search(expr) != 0)
            var tst2 = val.value.substring(i + 2, pass.length);
          else
            var tst2 = val.value.substring(i + 1, pass.length);

          val.value = tst1 + '/' + tst2;
        }

        } else if (i == 4) {
            if (lchar.search(expr) != 0) {
                var tst1 = val.value.substring(0, (i));
                val.value = tst1;
               continue;
            }

            if ((nchar != '/') && (nchar != '')) {
                var tst1 = val.value.substring(0, (i) + 1);
                if (nchar.search(expr) != 0)
                    var tst2 = val.value.substring(i + 2, pass.length);
                else
                    var tst2 = val.value.substring(i + 1, pass.length);
                    val.value = tst1 + '/' + tst2;
            }
        }

      if (i >= 6) {
        if (lchar.search(expr) != 0) {
          var tst1 = val.value.substring(0, (i));
          val.value = tst1;
        }
      }
    }

    if (pass.length > 10)
      val.value = val.value.substring(0, 10);

    return true;
  }

  // FUNÇÃO VALIDAÇÃO DE DATA 
  function validarData(data) {
    var partesData = data.split('/');
    var dia = parseInt(partesData[0], 10);
    var mes = parseInt(partesData[1], 10);
    var ano = parseInt(partesData[2], 10);

    var dataValida = new Date(ano, mes - 1, dia);

    // DATA INVÁLIDA
    if (dataValida.getFullYear() != ano || (dataValida.getMonth() + 1) != mes || dataValida.getDate() != dia) {
        return false;
    }

    // ANO INVÁLIDO
    if (ano < 2000 || ano > 2024) {
        return false; 
    }

    return true; 
}


  window.onload = function() {
    var form = document.querySelector('form'); 
    var inputData = document.getElementById("data"); 

    form.addEventListener("submit", function(event) {
      var data = inputData.value;

      if (!validarData(data)) {
        alert("Data inválida. Por favor, insira uma data válida.");
        event.preventDefault();  
      }
    });

    inputData.addEventListener("input", function() {
      mascaraData(this);
    });
  };
</script>





<script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0"></script>

<script>
    // APLICA MÁSCARA DE HORÁRIO - CLEAVE.JS
    document.addEventListener('DOMContentLoaded', function() {
        new Cleave('#Horario', {
            time: true,
            timePattern: ['h', 'm'], 
            delimiter: ':',           
            timeFormat: '24'        
        });
    });
    </script>
</body>
</html>
