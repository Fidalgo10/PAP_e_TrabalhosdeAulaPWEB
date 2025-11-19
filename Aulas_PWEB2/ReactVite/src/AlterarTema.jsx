import { useState } from "react";

export default function Tema() {
    const [escuro, setEscuro] = useState(false);

    function alterarTema() {
        setEscuro(!escuro);
    }
    const estilos = {
        backgroundColor: escuro ? "black" : "white",
        color: escuro ? "white" : "black",
        padding: "20px",
        minHeight: "150px",
        border: '1px solid #ccc',
        borderRadius: '8px',
    }
    return (
        <div style={estilos}>
            <h2>Tema {escuro ? 'Escuro' : 'Claro'}</h2>
            <p>Este é um exemplo de alterância entre temas usando React.</p>
            <button onClick={alterarTema}>Mudar para {escuro ? 'Tema Claro' : 'Tema Escuro'}</button>
        </div>
    );
}