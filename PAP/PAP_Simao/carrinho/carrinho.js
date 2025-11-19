document.addEventListener('DOMContentLoaded', function() {
    var contador = 0;
    var carrinho = [];

    // Seleciona todos os botões de adicionar ao carrinho
    var botoesCarrinho = document.querySelectorAll('[name="add_to_cart"]');
    var btnCarFix = document.getElementById('btn_car_fix');

    // Modal de carrinho
    var modal = document.getElementById("modal");
    var span = document.getElementsByClassName("close")[0];
    var btnComprar = document.getElementById("btn-comprar");
    var totalValor = document.getElementById("total-valor");

    // Modal de pagamento
    var pagamentoModal = document.getElementById("pagamentoModal");
    var realizarPagamentoBtn = document.getElementById("realizarPagamentoBtn");
    var totalValorPagamento = document.getElementById("total-valor-pagamento");
    var spanPagamento = document.querySelector('.close-pagamento');

    // Função para fechar o modal de carrinho
    function fecharModal() {
        modal.style.display = "none";
    }

    // Função para fechar o modal de pagamento
    function fecharPagamentoModal() {
        pagamentoModal.style.display = "none";
    }

    span.onclick = fecharModal;
    if (spanPagamento) {
        spanPagamento.onclick = fecharPagamentoModal;
    }

    // Evento para selecionar forma de pagamento
    var selectPaymentButtons = document.querySelectorAll('.select-payment');
    selectPaymentButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            selectPaymentButtons.forEach(function(btn) {
                btn.classList.remove('selected');
            });
            this.classList.add('selected');
        });
    });

    // Função para carregar o carrinho do localStorage
    function carregarCarrinho() {
        if (localStorage.getItem('carrinho')) {
            carrinho = JSON.parse(localStorage.getItem('carrinho'));
            contador = carrinho.reduce((total, item) => total + item.quantidade, 0);
            btnCarFix.innerText = '🛒 ' + contador;
        }
    }

    // Função para salvar o carrinho no localStorage
    function salvarCarrinho() {
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
    }

    // Função para limpar o carrinho no localStorage
    function limparCarrinho() {
        localStorage.removeItem('carrinho');
        carrinho = [];
        contador = 0;
        btnCarFix.innerText = '🛒 ' + contador;
    }

    // Função para verificar se o usuário está logado
    function verificarSessao() {
        return fetch('carrinho/verificar_sessao.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => data.logged_in)
        .catch(error => {
            console.error('Erro ao verificar a sessão:', error);
            return false;
        });
    }

    // Carregar o carrinho ao carregar a página
    carregarCarrinho();

    // Verificar a sessão do usuário e limpar o carrinho se não estiver logado
    verificarSessao().then(loggedIn => {
        if (!loggedIn) {
            limparCarrinho();
        }
    });

    // Atualizar o carrinho ao clicar no carrinho fixo
    btnCarFix.onclick = function() {
        if (modal.style.display === "block") {
            fecharModal(); // Fecha o modal se estiver aberto
        } else {
            exibirCarrinho(); // Exibe o carrinho se estiver fechado
        }
    }

    // Adiciona evento de clique a cada botão de adicionar ao carrinho
    botoesCarrinho.forEach(function(botao) {
        botao.addEventListener('click', function(event) {
            event.preventDefault();

            var quantidadeInput = this.previousElementSibling;
            var quantidade = parseInt(quantidadeInput.value);
            var tamanhoSelect = this.parentElement.querySelector('[name="tamanho"]');
            var tamanho = ''; // Inicializa tamanho como vazio

            if (tamanhoSelect && tamanhoSelect.selectedIndex !== -1) {
                tamanho = tamanhoSelect.options[tamanhoSelect.selectedIndex].text;
            }

            console.log("Tamanho selecionado:", tamanho); // Debug para verificar o tamanho selecionado

            if (quantidade && quantidade > 0) {
                var produto = {
                    id: this.dataset.id,
                    titulo: this.dataset.titulo,
                    quantidade: quantidade,
                    tamanho: tamanho, // Armazena o tamanho selecionado
                    preco: parseFloat(this.dataset.preco)
                };

                carrinho.push(produto);
                contador += quantidade;
                btnCarFix.innerText = '🛒 ' + contador;

                alert(`${quantidade} item(s) de ${produto.titulo} (Tamanho: ${produto.tamanho}) adicionado(s) ao carrinho.`);

                salvarCarrinho(); // Salva o carrinho no localStorage

                quantidadeInput.value = '1';
            }
        });
    });

    // Exibir conteúdo do carrinho
    function exibirCarrinho() {
        var carrinhoItens = document.getElementById('carrinho-itens');
        carrinhoItens.innerHTML = '';

        var total = 0;

        carrinho.forEach(function(item, index) {
            var totalItem = item.quantidade * item.preco; // Calcula o total do item
            total += totalItem; // Soma ao total geral
            var itemHTML = `
                <div class="carrinho-item">
                    <img src="${item.id}" title="${item.titulo}">
                    <div>
                        <h4>${item.titulo}</h4>
                        <p>Tamanho: ${item.tamanho}</p> <!-- Exibe o tamanho -->
                        <p>Quantidade: ${item.quantidade}</p>
                        <p>Preço unitário: ${item.preco.toFixed(2)}€</p>
                        <p>Total: ${(item.quantidade * item.preco).toFixed(2)}€</p>
                        <i class="fas fa-remove remover-item" title="Remover artigo do carrinho" data-index="${index}"></i>
                    </div>
                </div>
            `;
            carrinhoItens.innerHTML += itemHTML;
        });

        totalValor.innerText = total.toFixed(2);
        modal.style.display = "block";

        // Adicionar evento de clique para os ícones de remover item
        var botoesRemover = document.querySelectorAll('.remover-item');
        botoesRemover.forEach(function(botao) {
            botao.addEventListener('click', function() {
                var index = parseInt(this.dataset.index);
                var quantidadeRemovida = carrinho[index].quantidade;
                carrinho.splice(index, 1); // Remove o item do carrinho
                salvarCarrinho(); // Salva o carrinho atualizado no localStorage
                exibirCarrinho(); // Atualiza a exibição do carrinho

                // Atualiza o contador
                contador -= quantidadeRemovida;
                btnCarFix.innerText = '🛒 ' + contador;
            });
        });
    }

    // Evento para comprar
    btnComprar.onclick = function() {
        if (carrinho.length === 0) {
            alert('Seu carrinho está vazio.');
            return;
        }
        fecharModal();
        totalValorPagamento.innerText = totalValor.innerText; // Atualiza o total no modal de pagamento
        pagamentoModal.style.display = "block";
    }

    // Evento para realizar pagamento
    realizarPagamentoBtn.onclick = function() {
        var selectedPaymentOption = document.querySelector('.select-payment.selected');
        if (!selectedPaymentOption) {
            alert('Selecione uma forma de pagamento.');
            return;
        }
        var opcao_pagamento = selectedPaymentOption.dataset.option;

        fetch('carrinho/processar_pedido.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                carrinho: carrinho,
                total: parseFloat(totalValor.innerText),
                opcao_pagamento: opcao_pagamento // Certifique-se de que opcao_pagamento está definido corretamente
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na requisição AJAX: ' + response.statusText);
            }
            return response.json(); // Verifique aqui o que está sendo retornado
        })
        .then(data => {
            console.log(data); // Exiba o conteúdo da resposta para depuração
            if (data.success) {
                alert('Pedido concluído com sucesso, entraremos em contacto em menos de 24H!');
                carrinho = [];
                contador = 0;
                btnCarFix.innerText = '🛒 ' + contador;
                salvarCarrinho(); // Limpa o localStorage ao finalizar a compra
                fecharPagamentoModal();
            } else {
                if (data.message) {
                    alert(`Erro ao processar a compra: ${data.message}`);
                } else {
                    alert('Erro desconhecido ao processar a compra.');
                }
            }
        })
        .catch(error => {
            console.error('Erro na requisição AJAX:', error);
            alert('Erro na requisição AJAX. Tente novamente.');
        });
    }

    // Fechar o modal se o usuário clicar fora dele
    window.onclick = function(event) {
        if (event.target == modal) {
            fecharModal();
        }
        if (event.target == pagamentoModal) {
            fecharPagamentoModal();
        }
    }
});
