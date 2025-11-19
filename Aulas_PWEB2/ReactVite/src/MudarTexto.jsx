import { useState } from "react";

export default function MudarTexto() {
    const [mensagem, setMensagem] = useState("Texto Inicial");

    function mudarTexto() {
        setMensagem("O Texto foi Alterado!");
    }

    function voltarTextoInicial() {
        setMensagem("Texto Inicial");
    }

    return (
        <div>
            <h2>Mudar Texto</h2>
            <p>{mensagem}</p>
            <button onClick={mudarTexto}>Mudar Mensagem</button>
            <button onClick={voltarTextoInicial}>Voltar ao Texto Inicial</button>
        </div>
    );
}