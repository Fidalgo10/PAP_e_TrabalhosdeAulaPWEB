import { useState } from "react";

export default function MostrarEsconder() {
    const [visivel, setVisivel] = useState(true);

    function alterar() {
        setVisivel(!visivel);
    }

    return (
        <div>
            <h2>Mostrar / Esconder</h2>
            <button onClick={alterar}>{visivel ? 'Esconder' : 'Mostrar'} Texto</button>
            {visivel && <p>Este é o texto que pode ser mostrado ou escondido.</p>}
        </div>
    );
}