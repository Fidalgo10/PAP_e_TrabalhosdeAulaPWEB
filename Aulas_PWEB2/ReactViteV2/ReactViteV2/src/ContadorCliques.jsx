import { useState } from "react";

export default function ContadorCliques({ onBack }) {
    const [cliquesA, setCliquesA] = useState(0);
    const [cliquesB, setCliquesB] = useState(0);    
    const [cliquesC, setCliquesC] = useState(0);

    function totalCliques() {
        return cliquesA + cliquesB + cliquesC;
    }
    return (
        <div>
            <h2>Contador de Cliques</h2>
            <p>Cliques no Botão A: {cliquesA}</p>
            <button onClick={() => setCliquesA(cliquesA + 1)}>Botão A</button>
            <p>Cliques no Botão B: {cliquesB}</p>
            <button onClick={() => setCliquesB(cliquesB + 1)}>Botão B</button>
            <p>Cliques no Botão C: {cliquesC}</p>
            <button onClick={() => setCliquesC(cliquesC + 1)}>Botão C</button>
            <h3>Total de Cliques: {totalCliques()}</h3>
        </div>
    );
}