import {useState, useEffect} from 'react';

export default function ContadorAutomatico({onBack}) {
  const [contador, setContador] = useState(0);
  const [ativo, setAtivo] = useState(false);

    useEffect(() => {
        let intervalo;
        if (ativo) {
            intervalo = setInterval(() => {
                setContador((c) => c + 1);
            }, 1000);
        }
        return () => clearInterval(intervalo);
    }, [ativo]);

    function iniciar() {
        setAtivo(true);
    }
    function parar() {
        setAtivo(false);
    }
    function resetar() {
        setContador(0);
        setAtivo(false);
    }

    return (
        <div>
            <h2>Contador Automático</h2>
            <p>Contador: {contador} </p>
            <button onClick={iniciar}>Iniciar</button>
            <button onClick={parar}>Parar</button>
            <button onClick={resetar}>Resetar</button>
            <br />
        </div>
    );
}