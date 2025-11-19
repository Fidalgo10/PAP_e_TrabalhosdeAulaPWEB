// import Cartao from "./Cartao.jsx"


// export default function App() {
//   return (
//     <div className="min-h-screen bg-slate-950 text-slate-100 flex flex-col">
//       {/* Cabeçalho */}
//       <header className="bg-slate-900 border-b border-slate-800 py-4">
//         <h1 className="text-center text-2xl font-bold text-indigo-400">
//           Projeto React + Vite + TailwindCSS
//         </h1>
//         <p className="text-center text-slate-400 text-sm">
//           Exemplo 1 - Cartão de Apresentação
//         </p>
//       </header>

//       {/* Conteúdo principal */}
//       <main className="flex-1 flex items-center justify-center">
//         <Cartao />
//       </main>

//       {/* Rodapé */}
//       <footer className="bg-slate-900 text-center text-xs py-3 text-slate-500 border-t border-slate-800">
//         &copy; {new Date().getFullYear()} | Programação Web II - Exemplo React + TailwindCSS
//       </footer>
//     </div>
//   );
// }

import { useState } from "react";
import Cartao from "./Cartao.jsx";
import BotaoToggle from "./BotaoToggle.jsx";
import Mensagem from "./Mensagem.jsx";
import PesquisaSimples from "./PesquisaSimples.jsx";
import GrelhaCartoes from "./GrelhaCartoes.jsx";
import DashboardMini from "./DashboardMini.jsx";

export default function App() {
  const [componenteAtivo, setComponenteAtivo] = useState("cartao");

  const botoes = [
    { id: "cartao", label: "Cartão de Apresentação" },
    { id: "botao", label: "Botão Interativo" },
    { id: "mensagem", label: "Cartão de Mensagem" },
    { id: "pesquisa", label: "Caixa de Pesquisa" },
    { id: "grelha", label: "Cartões Responsivos" },
    { id: "dashboard", label: "Mini Dashboard" },
  ];

  const renderComponente = () => {
    switch (componenteAtivo) {
      case "cartao":
        return <Cartao />;
      default:
        return (
          <div className="min-h-screen flex items-center justify-center text-slate-300">
            <p>Componente ainda não implementado.</p>
          </div>
        );
      
      case "botao":
        return <BotaoToggle />;

      case "mensagem":
        return (
          <div className="space-y-6">
            {/* <Mensagem tipo="sucesso" texto="Dados guardados com sucesso!" />
            <Mensagem tipo="aviso" texto="Preenche todos os campos antes de continuar." /> */}
            <Mensagem tipo="erro" texto="Erro ao carregar o conteúdo." />
          </div>
      );

      case "pesquisa":
        return <PesquisaSimples />;

      case "grelha":
        return <GrelhaCartoes />;

      case "dashboard":
        return <DashboardMini />;

    }
  };

  return (
    <div className="min-h-screen bg-slate-950 text-slate-100 flex flex-col">
      {/* NAVBAR */}
      <header className="bg-slate-900/80 border-b border-slate-800 backdrop-blur sticky top-0 z-10">
        <nav className="container mx-auto px-4 py-3 flex flex-wrap items-center justify-between">
          <h1 className="text-lg font-bold text-indigo-400">Mini Projetos React + Tailwind</h1>
          <div className="flex flex-wrap gap-3 mt-2 sm:mt-0">
            {botoes.map((b) => (
              <button
                key={b.id}
                onClick={() => setComponenteAtivo(b.id)}
                className={`px-3 py-1 rounded-md text-sm font-medium transition ${
                  componenteAtivo === b.id
                    ? "bg-indigo-600 text-white"
                    : "bg-slate-800 text-slate-300 hover:bg-slate-700"
                }`}
              >
                {b.label}
              </button>
            ))}
          </div>
        </nav>
      </header>

      {/* CONTEÚDO */}
      <main className="flex-1">{renderComponente()}</main>

      {/* FOOTER */}
      <footer className="bg-slate-900 text-center text-xs py-3 text-slate-500 border-t border-slate-800">
        &copy; {new Date().getFullYear()} | Desenvolvido em aula com React + TailwindCSS
      </footer>
    </div>
  );
}


