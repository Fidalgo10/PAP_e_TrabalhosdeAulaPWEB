import { useState } from "react";

export default function Contador() {
  const [numero, setnumero] = useState(0);

  function aumentar() {
    setnumero(numero + 1);
  }
  function diminuir() {
    setnumero(numero - 1);
  }
  function reset() {
    setnumero(0);
  }

    return (
        <div>
            <h2>Contador: {numero}</h2>
            <button onClick={diminuir}>-</button>
            <button onClick={aumentar}>+</button>
            <button onClick={reset}>Reset</button>
        </div>
    );
}