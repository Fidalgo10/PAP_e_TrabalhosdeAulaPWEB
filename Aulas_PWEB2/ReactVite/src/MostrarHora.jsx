import { useState } from "react";

export default function MostrarHora() {
    const [hora, setHora] = useState('')

    function atualizarHora() {
        const agora = new Date().toLocaleTimeString();
        setHora(agora)
    }
    return (
        <div>
            <h2>Mostrar Hora</h2>
            <button onClick={atualizarHora}>Mostrar Hora</button>
            {hora && <p>Hora atual: {hora}</p>}
        </div>
    )
}