import { useState } from "react";

export default function DashboardMini(){
    const [utilizadores,setUtilizadores] = useState(12);
    const [tarefas,setTarefas] = useState(8);
    const [projetos, setProjetos] = useState(4);

    const cartoes = [
        {
            titulo : "Utilizadores",
            valor : utilizadores,
            setValor : setUtilizadores,
            cor : "border-indigo-500 bg-indigo-600 hover:bg-indigo-500",
        },
        {
            titulo : "Tarefas",
            valor : tarefas,
            setValor : setTarefas,
            cor : "border-emerald-500 bg-emerald-600 hover:bg-emerald-500"
        },
        {
            titulo :"Projetos" ,
            valor : projetos,
            setValor : setProjetos , 
            cor : "border-amber-500 bg-amber-600 hover:bg-amber-500"
        },
    ];

    return (
        <div className="min-h-screen bg-slate-900 text-slate-100 p-8">
            <h2 className="text-2xl font-semibold mb-8 text-center">
                Mini Dashboard de Contadores
            </h2>
            <div className="grid gap-6 sm:grid-cols-2 md:grid-cols-3">
                {cartoes.map((item,i)=>(
                    <div key={i} className={`bg-slate-800 ${item.cor.split(" ")[0]} rounded-xl shadow-md p-6 text-center transition`}>
                <div className="w-12 h-12 mx-auto mb-4 bg-slate-700 rounded-full flex items-center justify-center text-slate-400 text-sm">
                    Icone
                </div>
                <h3 className="text-lg font-semibold mb-2">{item.titulo}</h3>
                <p className="text-3xl font-bold mb-4">{item.valor}</p>

                <div className="flex justify-center gap-4">
                    <button onClick={()=>item.setValor(item.valor+1)}
                            className={`${item.cor.split(" ")[1]} ${item.cor.split(" ")[2]} px-3 py-1 rounded-md text-sm font-medium transition`}
                            >+</button>
                    <button onClick={()=>item.setValor(item.valor >0 ? item.valor-1 : 0)}
                            className={`${item.cor.split(" ")[1]} ${item.cor.split(" ")[2]} px-3 py-1 rounded-md text-sm font-medium transition`}
                            >-</button>
                </div>
                    </div>))}
        </div>
        </div>
    )


}