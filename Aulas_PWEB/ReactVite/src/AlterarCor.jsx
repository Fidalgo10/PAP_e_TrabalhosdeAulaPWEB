// import { useState } from "react";

// export default function AlterarCor() {
//     const [cor, setCor] = useState("white");

//     function mudarCor() {
//         setCor(cor === "white" ? "cyan" : "white");
//     }

//     return (
//         <div>
//             <h2>Alterar Cor</h2>
//             <p style={{ color: cor }}>Este texto muda de cor.</p>
//             <button onClick={mudarCor}>Mudar Cor</button>
//         </div>
//     );
// }

// Alterar varias cores o primeiro so muda duas cores 

import { useState } from "react";

export default function AlterarCor() {
    const cores = ["red", "Chartreuse", "lightblue", "orange", "DarkOrchid", "cyan"];
    const [indice, setIndice] = useState(0);

    function mudarCor() {
        const novoIndice = (indice + 1) % cores.length;
        setIndice(novoIndice);
    }

    const corAtual = cores[indice];
    
    return (
        <div>
            <h2>Alterar Cor</h2>
            <p style={{ color: corAtual }}>Este texto muda de cor.</p>
            <button onClick={mudarCor}>Mudar Cor</button>
        </div>
    );
}

