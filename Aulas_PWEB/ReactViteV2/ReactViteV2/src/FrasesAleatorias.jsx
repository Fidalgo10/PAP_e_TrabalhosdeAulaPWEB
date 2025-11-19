import { useState } from "react";

export default function FasesAleatorias({ onBack }) {
    const frases = [
        "A vida é 10% o que acontece comigo e 90% como eu reajo a isso.",
        "Acredite em si mesmo e todo o resto virá naturalmente.",
        "O sucesso é a soma de pequenos esforços repetidos dia após dia.",
        "A única maneira de fazer um excelente trabalho é amar o que você faz.",
        "Não espere por oportunidades, crie-as.",
        "A jornada de mil milhas começa com um único passo.",
        "A persistência é o caminho do êxito.",
    ]
    const [frase, setFrase] = useState("");
    function gerarFrase() {
        const indiceAleatorio = Math.floor(Math.random() * frases.length);
        setFrase(frases[indiceAleatorio]);
    }
    return (
        <div>
            <h2>Gerador de Frases Aleatórias</h2>
            <button onClick={gerarFrase}>Gerar Frase</button>
            {frase && (
                <p style={{marginTop: '20px', fontStyle: 'italic'} }><em>"{frase}"</em></p>
            )}
        </div>
    );
}