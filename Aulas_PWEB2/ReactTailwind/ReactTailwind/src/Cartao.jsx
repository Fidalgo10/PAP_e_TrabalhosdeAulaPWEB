// export default function Cartao() {
//   return (
//     <div className="min-h-screen bg-slate-900 text-slate-100 flex items-center justify-center">
//         <div bg-slate-800 p-8 rounded-2xl shadow-lg text-center w-80 hover:scale-105 transition-transform>
//             <img src="./src/assets/perfil.jpg" alt="Fotografia do utilizador" className="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-indigo-500 object-cover"/>
//         <h2 className="text-xl font-semibold">Simão Fidalgo</h2>
//         <p className="text-slate-400 text-sm mb-3">Aluno do CTESP Cantanhede</p>
//         <p className="text-slate-300 text-sm mb-6">Gosto de Programar.</p>
//         <button className="bg-indigo-600 text-white px-4 py-2 rounded-full hover:bg-indigo-500 transition-colors">Contactar</button>
//         </div>
//     </div>
//     );
// }

export default function Cartao() {
    return (
        <div className="min-h-screen bg-slate-900 flex items-center justify-center text-slate-100">
            <div className="bg-slate-800 p-8 rounded-2xl shandow-lg text-center w-80 hover:scale-105 transition">
                <img
                src="/src/assets/perfil.jpg"
                alt="Fotografia do utilizador"
                className="w-24 h-24 rounded-full mx-auto border-4 border-indigo-500 object-cover"
                />
                <h2 className="text-xl font-semibold">Simão Fidalgo</h2>
                <p className="text-slate-400 text-sm mb-3">Aluno do CTESP Cantanhede </p>
                <p className="text-slate-300 text-sm mb-6">
                    Gosto de Programar.
                </p>
                <button className="bg-indigo-600 hover:bg-indigo-500 px-4 py-2 rounded-lg text-sm font-medium transition">
                    Contactar
                </button>
            </div>
        </div>
    );
}