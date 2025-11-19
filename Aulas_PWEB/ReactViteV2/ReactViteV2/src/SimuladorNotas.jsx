import { useState } from "react";

export default function SimuladorNotas({ onBack }) {
    const [nota1, setNota1] = useState("");
    const [nota2, setNota2] = useState("");
    const [nota3, setNota3] = useState("");
    const [media, setMedia] = useState(null);
    const [Mensagem, setMensagem] = useState("");

    function calcularMedia() {
        const n1 = parseFloat(nota1);
        const n2 = parseFloat(nota2);
        const n3 = parseFloat(nota3);
        if (isNaN(n1) || isNaN(n2) || isNaN(n3) || nota1 === "" || nota2 === "" || nota3 === "") {
            setMensagem("Por favor, insira todas as notas corretamente.");
            setMedia(null);
            return;
        }
        const resultado = (n1 + n2 + n3) / 3;
        setMedia(resultado.toFixed(2));
        if (resultado >= 10) {
            setMensagem("Aprovado");
        } else {
            setMensagem("Reprovado");
        }
    }
    function limparCampos() {
        setNota1("");
        setNota2("");
        setNota3("");
        setMedia(null);
        setMensagem("");
    }

    return (
        <div>
            <h2>Simulador de Notas</h2>
            <div>
                <label>Nota 1: </label>
                <input
                    type="number"
                    value={nota1}
                    onChange={(e) => setNota1(e.target.value)}
                />
            </div>
            <div>
                <label>Nota 2: </label> 
                <input
                    type="number"
                    value={nota2}
                    onChange={(e) => setNota2(e.target.value)}
                />
            </div>
            <div>
                <label>Nota 3: </label>
                <input
                    type="number"
                    value={nota3}
                    onChange={(e) => setNota3(e.target.value)}
                />
            </div>
            <button onClick={calcularMedia}>Calcular Média</button>
            {media !== null && (
                <div>
                    <p>Média: {media}</p>
                    <p>Situação: {Mensagem}</p>
                </div>
            )}
        </div>
    );
}
