import { useState } from "react";

export default function CalculadoraComEscolha() {
    const [numero1, setNumero1] = useState('');
    const [numero2, setNumero2] = useState('');
    const [operacao, setOperacao] = useState(''); 

    const num1 = Number(numero1);
    const num2 = Number(numero2);
    
    let resultado = null;

    switch (operacao) {
        case 'soma':
            resultado = num1 + num2;
            break;
        case 'subtracao':
            resultado = num1 - num2;
            break;
        case 'multiplicacao':
            resultado = num1 * num2;
            break;
        case 'divisao':
            if (num2 === 0) {
                resultado = "Erro: Divisão por zero!";
            } else {
                resultado = num1 / num2;
            }
            break;
        default:
            resultado = null;
    }

    const podeMostrarResultado = numero1 !== '' && numero2 !== '' && operacao !== '';

    return (
        <div style={{ textAlign: 'center' }}>
            <h2>Calculadora</h2>

            <div style={{ display: 'flex', justifyContent: 'center', gap: '10px', marginBottom: '15px' }}>
                <input
                    type="number"
                    value={numero1}
                    onChange={(e) => setNumero1(e.target.value)}
                    placeholder="Número 1"
                    style={{ padding: '8px' }}
                />
                <input
                    type="number"
                    value={numero2}
                    onChange={(e) => setNumero2(e.target.value)}
                    placeholder="Número 2"
                    style={{ padding: '8px' }}
                />
            </div>

            {/* Botões de operação */}
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(2, 1fr)', gap: '10px', width: '200px', margin: '0 auto 20px' }}>
                <button
                    onClick={() => setOperacao('soma')}
                    style={{
                        padding: '10px',
                        backgroundColor: operacao === 'soma' ? 'cyan' : '#e0e0e0',
                        color: operacao === 'soma' ? 'white' : 'black',
                        border: 'none',
                        borderRadius: '5px',
                        cursor: 'pointer'
                    }}
                >
                    ➕ 
                </button>

                <button
                    onClick={() => setOperacao('subtracao')}
                    style={{
                        padding: '10px',
                        backgroundColor: operacao === 'subtracao' ? 'cyan' : '#e0e0e0',
                        color: operacao === 'subtracao' ? 'white' : 'black',
                        border: 'none',
                        borderRadius: '5px',
                        cursor: 'pointer'
                    }}
                >
                    ➖ 
                </button>

                <button
                    onClick={() => setOperacao('multiplicacao')}
                    style={{
                        padding: '10px',
                        backgroundColor: operacao === 'multiplicacao' ? 'cyan' : '#e0e0e0',
                        color: operacao === 'multiplicacao' ? 'white' : 'black',
                        border: 'none',
                        borderRadius: '5px',
                        cursor: 'pointer'
                    }}
                >
                    ✖️ 
                </button>

                <button
                    onClick={() => setOperacao('divisao')}
                    style={{
                        padding: '10px',
                        backgroundColor: operacao === 'divisao' ? 'cyan' : '#e0e0e0',
                        color: operacao === 'divisao' ? 'white' : 'black',
                        border: 'none',
                        borderRadius: '5px',
                        cursor: 'pointer'
                    }}
                >
                    ➗ 
                </button>
            </div>

            {podeMostrarResultado && (
                <div style={{ padding: '15px', border: '2px solid #007bff', borderRadius: '5px' }}>
                    <h3>Resultado da Operação Escolhida</h3>
                    <p>
                        {numero1} {
                            operacao === 'soma' ? '+' :
                            operacao === 'subtracao' ? '-' :
                            operacao === 'multiplicacao' ? '×' :
                            operacao === 'divisao' ? '/' : ''
                        } {numero2} = {resultado}
                    </p>
                </div>
            )}

            {!podeMostrarResultado && (
                <p style={{ color: 'gray' }}>
                    Insira os dois números e escolha a operação para ver o resultado.
                </p>
            )}
        </div>
    );
}



// import { useState } from "react";

// export default function CalculadoraComEscolha() {
//     const [numero1, setNumero1] = useState('');
//     const [numero2, setNumero2] = useState('');
//     const [operacao, setOperacao] = useState('');

//     const num1 = Number(numero1);
//     const num2 = Number(numero2);

//     let resultado = null; // Variável para guardar o resultado final

//     switch (operacao) {
//         case 'soma':
//             resultado = num1 + num2;
//             break;
//         case 'subtracao':
//             resultado = num1 - num2;
//             break;
//         case 'multiplicacao':
//             resultado = num1 * num2;
//             break;
//         case 'divisao':
//             if (num2 === 0) {
//                 resultado = "Erro: Divisão por zero!";
//             } else {
//                 resultado = num1 / num2;
//             }
//             break;
//         default:
//             resultado = null;
//     }

//     const podeMostrarResultado = numero1 !== '' && numero2 !== '' && operacao !== '';

//     return (
//         <div>
//             <h2>Calculadora</h2>

//             <div>
//                 <input
//                     type="number"
//                     value={numero1}
//                     onChange={(e) => setNumero1(e.target.value)}
//                     placeholder="Número 1"
//                     style={{ flex: 1, padding: '8px' }}
//                 />

//                 <input
//                     type="number"
//                     value={numero2}
//                     onChange={(e) => setNumero2(e.target.value)}
//                     placeholder="Número 2"
//                     style={{ flex: 1, padding: '8px' }}
//                 />
//             </div>

//             <select
//                 value={operacao}
//                 onChange={(e) => setOperacao(e.target.value)}
//                 style={{ padding: '8px', marginBottom: '20px', width: '27.7%' }}
//             >
//                 <option value="">-- Escolha a Operação --</option>
//                 <option value="soma">➕ Soma</option>
//                 <option value="subtracao">➖ Subtração</option>
//                 <option value="multiplicacao">✖️ Multiplicação</option>
//                 <option value="divisao">➗ Divisão</option>
//             </select>

//             {podeMostrarResultado && (
//                 <div style={{ padding: '15px', border: '2px solid #007bff', borderRadius: '5px' }}>
//                     <h3>Resultado da Operação Escolhida</h3>
//                     <p>
//                         {numero1}{' '}
//                         {operacao === 'soma' ? '+' :
//                          operacao === 'subtracao' ? '-' :
//                          operacao === 'multiplicacao' ? 'X' :
//                          operacao === 'divisao' ? '/' : ''}
//                         {' '}{numero2} = {resultado}
//                     </p>
//                 </div>
//             )}

//             {!podeMostrarResultado && (
//                 <p style={{ color: 'gray' }}>
//                     Insira os dois números e escolha a operação para ver o resultado.
//                 </p>
//             )}
//         </div>
//     );
// }
