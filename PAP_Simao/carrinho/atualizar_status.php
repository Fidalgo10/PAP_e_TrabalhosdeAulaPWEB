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
    $status = $_POST['status'];

    // Atualiza o status do pedido no banco de dados
    $sql = "UPDATE orders SET estado = ? WHERE id_order = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $pedido_id);
    
    if ($stmt->execute()) {
        echo "Status do pedido atualizado com sucesso.";
    } else {
        echo "Erro ao atualizar o status do pedido.";
    }
    
    $stmt->close();
    $conn->close();

    // Redireciona de volta para a página de pedidos
    header("Location: ver_pedidos.php");
    exit;
}
?>
