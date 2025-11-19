import { useState } from "react";

export default function ListaCompras({ onBack }) {
    const [item, setItem] = useState('');
    const [lista, setLista] = useState([]);

    function adicionarItem() {
        if (item.trim() !== '') {
            setLista([...lista, item.trim()]);
            setItem('');
        }
    }

    function removerItem(index) {
        const novaLista = lista.filter((_, i) => i !== index);
        setLista(novaLista);
    }

    return (
        <div>
            <h2>Lista de Compras</h2>
            <input
                type="text"
                value={item}
                onChange={(e) => setItem(e.target.value)}
                placeholder="Escreve um item..."
            />
            <button onClick={adicionarItem}>Adicionar</button>

            {lista.length > 0 ? (
                <ul>
                    {lista.map((produto, index) => (
                        <li key={index}>
                            {produto}{' '}
                            <button onClick={() => removerItem(index)}>Remover</button>
                        </li>
                    ))}
                </ul>
            ) : (
                <p>A lista está vazia.</p>
            )    
            }
            <button onClick={onBack}>Voltar</button>
        </div>
    );
}

            // <ul>
            //     {lista.map((item, index) => (
            //         <li key={index}>
            //             {item} <button onClick={() => removerItem(index)}>Remover</button>
            //         </li>
            //     ))}
            // </ul>