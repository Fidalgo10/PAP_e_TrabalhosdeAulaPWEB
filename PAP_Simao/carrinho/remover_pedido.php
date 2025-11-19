<?php
// Incluir conexão com o banco de dados
include "../include/aceder_base_dados.inc.php";

// Verifica se o usuário está logado
session_start();
if (!isset($_SESSION['id_utilizador'])) {
    echo 'Usuário não está logado.';
    exit;
}

// ID do usuário logado
$user_id = $_SESSION['id_utilizador'];

// Verifica se o usuário logado é administrador
if ($_SESSION['id_utilizador'] != 1) {
    echo 'Acesso negado. Esta página é restrita ao administrador.';
    exit;
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pedido_id = $_POST['pedido_id'];

    // Deleta os itens do pedido
    $sql = "DELETE FROM order_items WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $pedido_id);
    $stmt->execute();

    // Deleta o pedido
    $sql = "DELETE FROM orders WHERE id_order = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $pedido_id);

    if ($stmt->execute()) {
        echo "Pedido removido com sucesso.";
    } else {
        echo "Erro ao remover o pedido.";
    }
    
    $stmt->close();
    $conn->close();

    // Redireciona de volta para a página de pedidos
    header("Location: ver_pedidos.php");
    exit;
}
?>
