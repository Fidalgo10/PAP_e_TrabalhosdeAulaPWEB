// import { useState } from "react";

// export default function PesquisaSimples() {
//   const [termoPesquisa, setTermoPesquisa] = useState("");

//   const nomes = [
//     "Simão Fidalgo",
//     "Maria Silva",
//     "João Pereira",
//     "Ana Costa",
//     "Pedro Oliveira",
//     "Simão Pedro",
//     "Carla Fernandes",
//     "Rita Pinto",
//     "Liliana Ferreira",
//   ];

//   const resultados = nomes.filter((nome) =>
//     nome.toLowerCase().includes(termoPesquisa.toLowerCase())
//   );

//   return (
//     <div className="min-h-screen bg-slate-900 flex flex-col items-center justify-center text-slate-100 p-6">
//       <h2 className="text-2xl font-semibold mb-6">Caixa de Pesquisa</h2>

//       {/* Campo de pesquisa */}
//       <input
//         type="text"
//         placeholder="Pesquisar nomes..."
//         value={termoPesquisa}
//         onChange={(e) => setTermoPesquisa(e.target.value)}
//         className="mb-6 p-2 rounded border border-slate-700 bg-slate-800 text-slate-100 w-full max-w-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
//       />

//       {/* Lista de resultados */}
//       <ul className="w-full max-w-md space-y-2">
//         {resultados.length > 0 ? (
//           resultados.map((nome, index) => (
//             <li
//               key={index}
//               className="bg-slate-800 p-3 rounded hover:bg-slate-700 transition"
//             >
//               {nome}
//             </li>
//           ))
//         ) : (
//           <li className="text-slate-400 text-center">
//             Nenhum resultado encontrado.
//           </li>
//         )}
//       </ul>
//     </div>
//   );
// }
import { useState } from "react";

export default function PesquisaSimples() {
    const [termo, setTermo] = useState("");

    const nomes = [
        "Simão Fidalgo",
        "João Silva",
        "Maria Costa",
        "Ana Santos",
        "Rui Fernandes",
        "Tiago Oliveira",
        "Beatriz Nunes",
        "Maria Silva",
        "João Pereira",
        "Pedro Oliveira",
        "Simão Pedro",
        "Carla Fernandes",
        "Rita Pinto",
        "Liliana Ferreira",
    ];

    const resultados = nomes.filter((nome) =>
        nome.toLowerCase().includes(termo.toLowerCase())
    );

    return (
        <div className="min-h-screen bg-slate-900 flex flex-col items-center justify-center text-slate-100 p-6">
            <h2 className="text-2xl font-semibold mb-6">Caixa de Pesquisa</h2>

            <input
                type="text"
                placeholder="Procurar nome..."
                value={termo}
                onChange={(e) => setTermo(e.target.value)}
                className="w-80 p-3 rounded-md bg-slate-800 border border-slate-700 placeholder-slate-400
                    focus:ring-indigo-500 transition"
            />

            <ul className="mt-6 w-80 divide-y divide-slate-700 rounded-md bg-slate-800/50 shadow-md">
                {resultados.length > 0 ? (
                    resultados.map((nome, i) => (
                        <li key={i} className="p-3 hover:bg-slate-700 transition">
                            {nome}
                        </li>
                    ))
                ) : (
                    <li className="p-3 text-slate-400 text-center">
                        Nenhum resultado encontrado.
                    </li>
                )}
            </ul>
        </div>
    );
}