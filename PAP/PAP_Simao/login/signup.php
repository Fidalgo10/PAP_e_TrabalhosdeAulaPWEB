<?php
// Conectar ao banco de dados
include "../include/aceder_base_dados.inc.php";

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nome = mysqli_real_escape_string($conn, $_POST["nome"]);
  $sobrenome = mysqli_real_escape_string($conn, $_POST["sobrenome"]);
  $email = mysqli_real_escape_string($conn, $_POST["email"]);
  $telefone = mysqli_real_escape_string($conn, $_POST["telefone"]);
  $data_nascimento = mysqli_real_escape_string($conn, $_POST["data_nascimento"]);
  $morada = mysqli_real_escape_string($conn, $_POST["morada"]);
  $codigo_postal = mysqli_real_escape_string($conn, $_POST["codigo_postal"]);
  $porta = mysqli_real_escape_string($conn, $_POST["porta"]);
  $iban = mysqli_real_escape_string($conn, $_POST["iban"]);
  $senha = mysqli_real_escape_string($conn, $_POST["senha"]);

  // Verificar se o e-mail já está em uso
  $check_email_query = "SELECT * FROM utilizadores WHERE email='$email'";
  $check_email_result = $conn->query($check_email_query);
  if ($check_email_result->num_rows > 0) {
      echo "<p style='color: red;'>Erro ao cadastrar: Já existe uma conta com este email.</p>";
  } else {
      // Inserir dados no banco de dados
      $query = "INSERT INTO utilizadores (nome, sobrenome, email, telefone, senha, data_nascimento, morada, codigo_postal, porta, iban) 
                VALUES ('$nome', '$sobrenome', '$email', '$telefone', '$senha', '$data_nascimento', '$morada', '$codigo_postal', '$porta', '$iban')";
      if ($conn->query($query) === TRUE) {
          echo "Cadastro realizado com sucesso!";
          // Redirecionar após o cadastro
          header("Location: ../index.php");
          exit;
      } else {
          echo "Erro ao cadastrar: " . $conn->error;
      }
  }
}

// Fechar conexão ao banco de dados
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../img/CFV_icon.ico" type="image/x-icon">
  <script src="https://kit.fontawesome.com/1165876da6.js" crossorigin="anonymous"></script>
  <title>Signup</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
      color: #333;
    }
    .container {
      max-width: 400px;
      margin: 20px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h1 {
      color: #007bff;
      text-align: center;
    }
    label {
      display: block;
      margin-bottom: 8px;
    }
    input[type=text], input[type=email], input[type=tel], input[type=password], input[type=date] {
      width: calc(100% - 20px);
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }
    input[type=submit] {
      background-color: #007bff;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      width: 100%;
      margin-top: 10px;
    }
    input[type=submit]:hover {
      background-color: #0056b3;
    }
    .password-toggle {
      cursor: pointer;
      color: #007bff;
      font-size: 14px;
      float: right;
    }
    .login-link {
      text-align: center;
      margin-top: 10px;
    }
    .login-link a {
      color: #007bff;
      text-decoration: none;
    }
    .login-link a:hover {
      text-decoration: underline;
    }

    .btn-home {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000; /* Para garantir que o botão esteja sempre acima de outros conteúdos */
        background-color: #1a73e8;
        color: #ffffff;
        border: none;
        padding: 10px;
        cursor: pointer;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: background-color 0.3s, transform 0.3s;
        font-size: 30px;
    }

    .btn-home:hover {
        background-color: #0d47a1;
        transform: scale(1.1);
    }

    @media only screen and (max-width: 600px) {
      input[type=text], input[type=email], input[type=tel], input[type=password], input[type=date] {
      font-size: 14px; /* Tamanho menor para dispositivos móveis e tablets */
    }
    .container h1{
        font-size: 22px;
    }
    .container label, p{
        font-size: 14px;
    }
    .container span{
        font-size: 13px;
    }
    .btn-home{
        font-size: 20px;
    }
  }
  </style>
</head>
<body>
<a href="../index.php" class="btn-home"><i class="fas fa-home"></i></a>
  <div class="container">
    <h1>Crie a sua conta</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <label for="nome">Nome:</label>
      <input type="text" id="nome" name="nome" required><br>
      <label for="sobrenome">Sobrenome:</label>
      <input type="text" id="sobrenome" name="sobrenome" required><br>
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required><br>
      <label for="telefone">Telefone:</label>
      <input type="tel" id="telefone" name="telefone" required><br>
      <label for="data_nascimento">Data de Nascimento:</label>
      <input type="date" id="data_nascimento" name="data_nascimento" required><br>
      <label for="morada">Morada:</label>
      <input type="text" id="morada" name="morada" required><br>
      <label for="codigo_postal">Código Postal:</label>
      <input type="text" id="codigo_postal" name="codigo_postal" required><br>
      <label for="porta">Porta:</label>
      <input type="text" id="porta" name="porta" required><br>
      <label for="iban">IBAN:</label>
      <input type="text" id="iban" name="iban" required><br>
      <label for="senha">Senha:</label>
      <input type="password" id="senha" name="senha" required>
      <span class="password-toggle" onclick="togglePassword()">Mostrar senha</span><br>
      <input type="submit" value="Criar conta">
    </form>
    <div class="login-link">
      <p>Já possui conta? <a href="login.php">Login</a></p>
    </div>
  </div>

  <script>
    function togglePassword() {
      var senhaInput = document.getElementById("senha");
      if (senhaInput.type === "password") {
        senhaInput.type = "text";
        document.querySelector(".password-toggle").textContent = "Esconder senha";
      } else {
        senhaInput.type = "password";
        document.querySelector(".password-toggle").textContent = "Mostrar senha";
      }
    }
  </script>
</body>
</html>