import { useState } from "react";

export default function BotaoToggle() {
    const [ativo, setAtivo] = useState(false);

    return (
        <div className="min-h-screen bg-slate-900 flex items-center justify-center text-slate-100">
            <div className="text-center">
                <h2 className="text-2xl mb-6 font-semibold">Botão Interativo</h2>
                <button
                    onClick={() => setAtivo(!ativo)}
                    className={`px-6 py-3 rounded-full font-medium transition-colors ${
                        ativo
                            ? "bg-green-500 hover:bg-green-600"
                            : "bg-red-500 hover:bg-red-600"
                    }`}
                >
                    {ativo ? "Ativo" : "Inativo"}
                </button>
                <p className="mt-6 text-sm text-slate-400">
                    Estado Atual:{" "}
                    <span
                        className={`font-semibold ${
                            ativo ? "text-green-400" : "text-indigo-400"
                        }`}
                    >
                        {ativo ? "Ativo" : "Inativo"}
                    </span>
                </p>
            </div>
        </div>
    );
}
