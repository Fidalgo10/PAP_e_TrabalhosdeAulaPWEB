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

// Consulta para obter todos os pedidos junto com os detalhes do usuário
$sql = "SELECT o.*, u.nome, u.email, u.telefone, u.iban, GROUP_CONCAT(oi.titulo, ' (', oi.quantidade, ' x ', oi.tamanho, ') ' SEPARATOR ', ') as product_details
        FROM orders o
        LEFT JOIN order_items oi ON o.id_order = oi.order_id
        LEFT JOIN utilizadores u ON o.id_utilizador = u.id_utilizador
        GROUP BY o.id_order";

$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link rel="icon" href="../img/CFV_icon.ico" type="image/x-icon">
    <script src="https://kit.fontawesome.com/1165876da6.js" crossorigin="anonymous"></script>
    <style>
        /* Estilos gerais */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        #page-content {
            max-width: 800px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .texto1 {
            font-size: 30px;
            text-align: center;
            margin-bottom: 10px;
            color: #1a73e8;
        }

        .barra {
            height: 3px;
            background-color: #1a73e8;
            margin-bottom: 20px;
        }

        .pedidos {
            margin-top: 20px;
        }

        .pedido {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .pedido h3 {
            margin-bottom: 5px;
            color: #1a73e8;
            font-size: 18px;
        }

        .pedido p {
            margin-bottom: 5px;
            font-size: 17px;
        }

        .pedido .produtos {
            margin-bottom: 10px;
        }

        .pedido label {
            font-size: 17px;
        }

        .pedido select {
            font-size: 15px;
        }

        form {
            display: inline;
        }

        button {
            background-color: #1a73e8;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 3px;
            transition: background-color 0.3s;
            font-size: 15px;
        }

        button:hover {
            background-color: #0d47a1;
        }

        .remover-btn {
            color: white;
            background-color: #ff1744;
            border: none;
            padding: 6px 10px;
            border-radius: 3px;
            text-align: center;
            font-size: 17px;
        }

        .remover-btn:hover {
            background-color: #d50000;
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

        /* Responsividade para tablets */
        @media (max-width: 768px) {

            .pedido {
                padding: 8px;
            }

            .texto1 {
                font-size: 24px;
            }

            .pedido h3 {
                font-size: 18px;
            }

            .pedido p {
                font-size: 14px;
            }

            .pedido label {
                font-size: 14px;
            }

            .pedido select {
                font-size: 12px;
            }

            button {
                font-size: 12px;
            }

            .fa-trash-alt {
                font-size: 14px;
            }

            .btn-home {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
<a href="../loja.php" class="btn-home"><i class="fas fa-home"></i></a>
<div id="page-content">
    <h2 class="texto1">Pedidos Realizados</h2>
    <div class="barra"></div>

    <?php if (mysqli_num_rows($resultado) > 0): ?>
        <div class="pedidos">
            <?php while ($pedido = mysqli_fetch_assoc($resultado)): ?>
                <div class="pedido">
                    <h3>ID Pedido: <?php echo $pedido['id_order']; ?></h3>
                    <p><strong>Nome:</strong> <?php echo $pedido['nome']; ?></p>
                    <p><strong>Email:</strong> <?php echo $pedido['email']; ?></p>
                    <p><strong>Telefone:</strong> <?php echo $pedido['telefone']; ?></p>
                    <p><strong>IBAN:</strong> <?php echo $pedido['iban']; ?></p>
                    <p><strong>Data:</strong> <?php echo $pedido['order_date']; ?></p>
                    <p><strong>Total:</strong> <?php echo $pedido['total_amount']; ?>€</p>
                    <p><strong>Produtos:</strong> <?php echo $pedido['product_details']; ?></p>
                    <p><strong>Opção de Pagamento:</strong> <?php echo $pedido['opcao_pagamento']; ?></p>
                    <form action="atualizar_status.php" method="post" style="display:inline;">
                        <input type="hidden" name="pedido_id" value="<?php echo $pedido['id_order']; ?>">
                        <label for="status">Status:</label>
                        <select name="status" id="status">
                            <option value="pendente" <?php echo $pedido['estado'] == 'pendente' ? 'selected' : ''; ?>>Por pagar</option>
                            <option value="processamento" <?php echo $pedido['estado'] == 'processamento' ? 'selected' : ''; ?>>Pago</option>
                            <option value="enviado" <?php echo $pedido['estado'] == 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                            <option value="entregue" <?php echo $pedido['estado'] == 'entregue' ? 'selected' : ''; ?>>Entregue</option>
                        </select>
                        <button type="submit">Atualizar Status</button>
                    </form>
                    <form action="remover_pedido.php" method="post" style="display:inline;">
                        <input type="hidden" name="pedido_id" value="<?php echo $pedido['id_order']; ?>">
                        <button title="Remover Pedido" type="submit" class="remover-btn" onclick="return confirm('Tem certeza que deseja remover este pedido?');">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>Nenhum pedido encontrado.</p>
    <?php endif; ?>
</div>
</body>
</html>
