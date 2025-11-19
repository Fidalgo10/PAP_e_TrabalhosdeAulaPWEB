import { useState } from "react";

export default function AlterarNome() {
    const [nome, setNome] = useState('');
    const [idade, setIdade] = useState('');

    const mostrarMensagem = nome !== '' && idade !== '';
    return (
        <div>
            <h2>Alterar Nome e Idade</h2>
            <input
                type="text"
                value={nome}
                onChange={(e) => setNome(e.target.value)}
                placeholder="Escreve o teu nome..."
            />

            <input
                type="number"
                value={idade}
                onChange={(e) => setIdade(e.target.value)}
                placeholder="Escreve a tua idade..."
            />
            {mostrarMensagem && (
                <p>
                    Olá, eu sou o {nome}! E tenho {idade} anos.
                </p>
            )}
        </div>
    );
}