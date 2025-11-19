<?php
// Incluir arquivo de conexão com o banco de dados
include "../include/aceder_base_dados.inc.php";

// Verificar se foi enviado um POST com o produto a ser removido
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remover_produto']) && isset($_POST['produto_id'])) {
    // Filtrar e obter o ID do produto a ser removido
    $produto_id = mysqli_real_escape_string($conn, $_POST['produto_id']);

    // Construir e executar a query de remoção
    $sql = "DELETE FROM produtos WHERE id_imagem = '$produto_id'";
    if (mysqli_query($conn, $sql)) {
        // Redirecionar de volta para loja.php após a remoção
        header('Location: ../loja.php');
        exit;
    } else {
        echo "Erro ao remover o produto: " . mysqli_error($conn);
    }
} else {
    // Se não houver POST válido, redirecionar para loja.php
    header('Location: ../loja.php');
    exit;
}
?>
