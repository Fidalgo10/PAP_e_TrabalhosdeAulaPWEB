import { useState } from "react";

export default function GeradorCores({ onBack }) {
    const [cor, setCor] = useState("#ffffff");

    function gerarCor() {
        const novaCor = '#' + Math.floor(Math.random()*16777215).toString(16).padStart(6, '0');
        setCor(novaCor);
    }

    return (
        <div>
            <h2>Gerador de Cores</h2>
            <div style={{
                width: '100px',
                height: '100px',
                backgroundColor: cor,
                border: '2px solid #000',
                marginBottom: '10px'
            }}></div>
            <button onClick={gerarCor}>Gerar Cor</button>
            <br />
        </div> 
    );
}