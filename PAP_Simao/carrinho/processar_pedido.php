<?php
// Incluir conexão com o banco de dados
include "../include/aceder_base_dados.inc.php";

// Verifica se o usuário está logado
session_start();
if (!isset($_SESSION['id_utilizador'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não está logado.']);
    exit;
}

// ID do usuário logado
$user_id = $_SESSION['id_utilizador'];

// Verifica se há dados do carrinho no POST
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['carrinho']) || empty($data['carrinho'])) {
    echo json_encode(['success' => false, 'message' => 'Carrinho está vazio.']);
    exit;
}

// Recupera os itens do carrinho do POST
$carrinho = $data['carrinho'];
$total = 0;

// Calcula o total do pedido
foreach ($carrinho as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

// Busca o último ID de pedido usado e incrementa para obter o próximo
$sql_max_order_id = "SELECT MAX(id_order) as max_id FROM orders";
$result_max_order_id = $conn->query($sql_max_order_id);

if ($result_max_order_id && $result_max_order_id->num_rows > 0) {
    $row = $result_max_order_id->fetch_assoc();
    $next_order_id = intval($row['max_id']) + 1;
} else {
    $next_order_id = 1; // Se não houver nenhum pedido ainda, começar com 1
}

// Inserir na tabela orders
$sql_order = "INSERT INTO orders (id_utilizador, order_date, total_amount) VALUES (?, NOW(), ?)";
$stmt_order = $conn->prepare($sql_order);
if ($stmt_order === false) {
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar statement para orders: ' . $conn->error]);
    exit;
}

$total_amount = floatval($total); // Supondo que $total seja o valor total do carrinho

$stmt_order->bind_param("id", $user_id, $total_amount);
if (!$stmt_order->execute()) {
    echo json_encode(['success' => false, 'message' => 'Erro ao executar statement para orders: ' . $stmt_order->error]);
    exit;
}

// Obtém o ID do pedido inserido
$new_order_id = $stmt_order->insert_id;
// Inserir na tabela order_items
$sql_item = "INSERT INTO order_items (order_id, titulo, quantidade, tamanho, preco_unitario, preco_total) VALUES (?, ?, ?, ?, ?, ?)";
$stmt_item = $conn->prepare($sql_item);
if ($stmt_item === false) {
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar statement para order_items: ' . $conn->error]);
    exit;
}

foreach ($carrinho as $item) {
    $titulo = $item['titulo'];
    $quantidade = $item['quantidade'];
    $tamanho = $item['tamanho']; 
    $preco_unitario = $item['preco'];
    $preco_total = $preco_unitario * $quantidade;

    $stmt_item->bind_param("isssid", $new_order_id, $titulo, $quantidade, $tamanho, $preco_unitario, $preco_total);
    if (!$stmt_item->execute()) {
        echo json_encode(['success' => false, 'message' => 'Erro ao executar statement para order_items: ' . $stmt_item->error]);
        exit;
    }
}

// Sucesso no processamento do pedido
echo json_encode(['success' => true, 'order_id' => $new_order_id]);
exit;
?>
