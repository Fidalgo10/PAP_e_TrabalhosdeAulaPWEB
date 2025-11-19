<?php
include "../include/aceder_base_dados.inc.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];
    $imagem = $_FILES['imagem']['name'];
    $target_dir = __DIR__ . "/../img/produtos_loja/"; // Caminho absoluto para a pasta de imagens
    $target_file = $target_dir . basename($imagem);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar se o arquivo é uma imagem real
    $check = getimagesize($_FILES['imagem']['tmp_name']);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "O arquivo não é uma imagem.";
        $uploadOk = 0;
    }

    // Verificar o tamanho do arquivo
    if ($_FILES['imagem']['size'] > 5000000) {
        echo "Desculpe, o seu arquivo é muito grande.";
        $uploadOk = 0;
    }

    // Permitir certos formatos de arquivo
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
        $uploadOk = 0;
    }

    // Verificar se $uploadOk está definido como 0 por um erro
    if ($uploadOk == 0) {
        echo "Desculpe, seu arquivo não foi carregado.";
    // Tentar fazer o upload do arquivo
    } else {
        // Mover o arquivo temporário para o diretório de destino
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $target_file)) {
            $imagem_path = "./img/produtos_loja/" . basename($imagem);
            $sql = "INSERT INTO produtos (titulo, descricao, preco, categoria, imagem) VALUES ('$titulo', '$descricao', '$preco', '$categoria', '$imagem_path')";
            if (mysqli_query($conn, $sql)) {
                // Redirecionar para loja.php após adicionar o produto com sucesso
                header('Location: ../loja.php');
                exit;
            } else {
                echo "Erro ao adicionar o produto: " . mysqli_error($conn);
            }
        } else {
            echo "Erro ao fazer o upload da imagem.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <link rel="icon" href="../img/CFV_icon.ico" type="image/x-icon">
    <script src="https://kit.fontawesome.com/1165876da6.js" crossorigin="anonymous"></script>
    <style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    color: #333;
}

.btn-home {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
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

#page-content {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #1a73e8;
    text-align: center;
    margin-bottom: 20px;
}

.barra {
    width: 50px;
    height: 4px;
    background-color: #1a73e8;
    margin: 10px auto;
}

.container {
    margin-top: 20px;
}

form {
    max-width: 600px;
    margin: 0 auto;
}

label {
    font-weight: bold;
    margin-bottom: 8px;
    display: block;
}

input[type="text"],
select,
input[type="file"],
button {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 20 20" fill="%231a73e8"><path d="M10 12.59l-5.3-5.3a1 1 0 011.42-1.42L10 10.75l4.88-4.88a1 1 0 111.42 1.42l-5.3 5.3a1 1 0 01-1.42 0z"/></svg>');
    background-size: 12px;
    background-position: calc(100% - 10px) center;
    background-repeat: no-repeat;
}

button {
    background-color: #1a73e8;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #0d47a1;
}

.nota{
    font-size: 16px;
    color: green;
}

@media only screen and (max-width: 600px) {
    input[type="text"],
    select,
    input[type="file"],
    button {
        width: calc(100% - 20px);
        font-size: 14px;
        padding: 8px;
    }

    label {
        font-size: 14px;
    }

    .btn-home {
        font-size: 24px;
        padding: 8px;
    }

    h2 {
        font-size: 18px;
    }

    #page-content {
        padding: 10px;
    }

    .nota{
        font-size: 14px;
    }
}
</style>
</head>
<body>
    <a href="../loja.php" class="btn-home"><i class="fas fa-home"></i></a>
    <div id="page-content">
        <h2>Adicionar Novo Produto</h2>
        <div class="barra"></div>

        <div class="container">
            <form id="produtoForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required>
                
                <label for="descricao">Descrição:</label>
                <select id="descricao" name="descricao" required>
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                    <option value="Masculino/Feminino">Masculino/Feminino</option>
                </select>
                
                <label for="preco">Preço (€):</label>
                <input type="text" id="preco" name="preco" required>
                
                <label for="categoria">Categoria:</label>
                <select id="categoria" name="categoria" required>
                    <option value="Hoodies">Hoodies</option>
                    <option value="Equipamentos">Equipamentos</option>
                    <option value="T-shirts">T-shirts</option>
                    <option value="Fatos de treino">Fatos de treino</option>
                    <option value="Outros">Outros</option>
                </select>
                
                <label for="imagem">Escolher Imagem:</label>
                <input type="file" id="imagem" name="imagem" accept="image/*" required>
                <p class="nota">Sugestão: imagens JPG (1650x2000)</p><br>
                <button type="submit" id="submitBtn">Adicionar Produto</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('submitBtn').addEventListener('click', function(event) {
            event.preventDefault(); // Evitar envio automático do formulário
            if (confirm("Tem a certeza que pretende adicionar este produto?")) {
                document.getElementById('produtoForm').submit(); // Enviar o formulário
            }
        });
    </script>
</body>
</html>
