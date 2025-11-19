<?php
// Conectar ao banco de dados
include "include/aceder_base_dados.inc.php";

$pageTitle = "CFV-Loja azul";
include './loja_style/header_loja.php';
?>
<link rel="stylesheet" href="./loja_style/styleloja.css">
<br><br><br><br>
<div id="page-content">
    <h2 class="texto1">CFV - Loja azul</h2><br>
    <div class="barra"></div><br><br>
    <h3 class="texto2">Produtos disponíveis</h3><br>
    <h4 class="texto3">
        Faça login para realizar compras 
    </h4><br><br>
    <script>
        // Função para verificar se o usuário está logado
        function isUserLoggedIn() {
            <?php
            // Aqui você deve implementar a lógica real para verificar se o usuário está logado
            // Por exemplo, verificando a existência de uma variável de sessão ou cookie
            if (isset($_SESSION['nome'])) {
                echo 'return true;'; // Usuário está logado
            } else {
                echo 'return false;'; // Usuário não está logado
            }
            ?>
        }

        // Evento quando o DOM estiver completamente carregado
        document.addEventListener('DOMContentLoaded', function() {
            const loginMessage = document.querySelector('.texto3');
            
            // Verifica se o usuário está logado ao carregar a página
            if (isUserLoggedIn()) {
                loginMessage.style.display = 'none'; // Oculta o texto se estiver logado
            } else {
                loginMessage.style.display = 'block'; // Mostra o texto se não estiver logado
            }
        });
    </script> 
        <!-- Este script serve para mostrar o texto3 se nao tiver o login efetuado-->
    <div class="barra"></div><br>

    <form class="formulario-filtro" action="loja.php" method="post">
        <label for="filtro">Filtrar por:</label>
        <select name="cat">
            <option value="todos">Todos os produtos</option>
            <?php
            $sql = "SELECT * FROM categorias";
            $resultado = mysqli_query($conn, $sql);
            while ($linha = mysqli_fetch_assoc($resultado)) {
                $selected = (isset($_POST['cat']) && $_POST['cat'] == $linha['nome_categoria']) ? 'selected' : '';
                echo "<option value='" . $linha['nome_categoria'] . "' $selected>" . $linha['nome_categoria'] . "</option>";
            }
            ?>
        </select>
        <button type="submit">Aplicar filtro</button>
    </form>
    <div class="barra"></div><br>

    <div class="button-container">
    <?php
    // Verifica se o usuário logado é administrador
    if (isset($_SESSION['id_utilizador']) && $_SESSION['id_utilizador'] == 1) {
        echo '<a href="loja_style/adicionar_produto.php"><button id="btn-carregar-produto">Carregar Produto</button></a>';
        echo '<a href="carrinho/ver_pedidos.php"><button id="btn-ver-pedidos">Ver Pedidos</button></a>';
    }
    ?>
    </div>
    <?php
    // Obter os produtos filtrados do banco de dados
    $sql = "SELECT * FROM produtos";
    if (isset($_POST['cat']) && $_POST['cat'] != 'todos') {
        $categoria_selecionada = mysqli_real_escape_string($conn, $_POST['cat']);
        $sql .= " WHERE categoria = '$categoria_selecionada'";
    }

    $resultado = mysqli_query($conn, $sql);
    $produtos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    ?>

    <div class="produtos">
        <?php if (count($produtos) > 0): ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="produto_card">
                <?php if (isset($_SESSION['id_utilizador']) && $_SESSION['id_utilizador'] == 1): ?>
                    <form action="loja_style/remover_produto.php" method="post" class="form_remover_produto" onsubmit="return confirmarRemocao()">
                        <input type="hidden" name="produto_id" value="<?php echo $produto['id_imagem']; ?>">
                        <button type="submit" name="remover_produto" class="btn_remover_produto" title="Remover Produto">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                <?php endif; ?>
                <script>
                    function confirmarRemocao() {
                        return confirm("Tem certeza que deseja remover este produto?");
                    }
                </script>

                    <div><img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['titulo']; ?>"></div>
                    <div class="desc">
                        <?php if (!empty($produto['link_detalhes'])): ?>
                            <a href="<?php echo $produto['link_detalhes']; ?>"><button class="btn_detalhes">Detalhes</button></a>
                        <?php endif; ?>
                        <span><?php echo $produto['descricao']; ?></span>
                    </div>
                    <div class="titulo"><?php echo $produto['titulo']; ?></div>
                    <div class="box">
                        <div class="preco"><?php echo $produto['preco']; ?>€</div>
                        <div class="div_comprar">
                            <?php if ($produto['categoria'] != 'Outros'): ?>
                                <select name="tamanho" class="tamanho">
                                    <option value="XS">XS</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                    <option value="3XL">3XL</option>
                                </select>
                            <?php endif; ?>
                            <input type="number" name="quantidade" value="1" min="1">
                            <?php if (isset($_SESSION['nome'])): ?>
                                <button type="button" name="add_to_cart" 
                                        data-id="<?php echo $produto['imagem']; ?>"
                                        data-titulo="<?php echo $produto['titulo']; ?>"
                                        data-preco="<?php echo $produto['preco']; ?>" 
                                        data-tamanho="<?php echo $produto['categoria'] != 'Outros' ? 'true' : 'false'; ?>" title="Adicionar ao carrinho">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum produto encontrado.</p>
        <?php endif; ?>
    </div>

    <button id="btn_car_fix">🛒</button>
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Carrinho de Compras</h2>
            <div id="carrinho-itens">
                <!-- Aqui serão listados os itens do carrinho -->
            </div>
            <div id="total">
                <h3>Total: <span id="total-valor">0</span>€</h3>
            </div>
            <button id="btn-comprar">Comprar</button>
        </div>
    </div>
<!-- Modal de Pagamento -->
    <div id="pagamentoModal" class="modal-pagamento">
        <div class="modal-content-pagamento">
            <span class="close-pagamento" onclick="fecharPagamentoModal()">&times;</span>
            <h2>Escolha a forma de pagamento e realize o pagamento</h2>
            <div class="payment-options">
                <div class="payment-option">
                    <h3>Transferência</h3><br>
                    <p>IBAN: xxxxxxxxxx</p><br>
                    <button class="select-payment" data-option="transferencia">Selecionar</button>
                </div>
                <div class="payment-option">
                    <h3>MBWAY</h3><br>
                    <p>Telemóvel: 964 646 774</p><br>
                    <button class="select-payment" data-option="mbway">Selecionar</button>
                </div>
            </div>
            <div id="total-pagamento">
                <h3>Total a pagar: <span id="total-valor-pagamento">0</span>€</h3>
            </div>
            <button id="realizarPagamentoBtn" title="CLIQUE APENAS QUANDO REALIZAR O PAGAMENTO">Pagamento realizado</button>
            <br><P class="nota">Nota: Por favor utilize o IBAN/Número de telefone que submeteu ao criar a sua conta. (Caso queira fazer com outro IBAN/Número por favor, entre em contacto connosco depois de concluir a compra para nós termos conhecimento da situação)</P>
        </div>
    </div>
<?php
// Verificação e alerta caso o usuário não esteja logado
if (!isset($_SESSION['nome'])) {
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    echo '    var btnCarFix = document.getElementById("btn_car_fix");';
    echo '    btnCarFix.addEventListener("click", function() {';
    echo '        alert("Faça login se quiser adicionar e ver produtos no carrinho.");';
    echo '    });';
    echo '});';
    echo '</script>';
}
?>
<script src="carrinho/carrinho.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php    
include './loja_style/footer_loja.php'; // Inclui o rodapé
?>
</div>
</body>
</html>
