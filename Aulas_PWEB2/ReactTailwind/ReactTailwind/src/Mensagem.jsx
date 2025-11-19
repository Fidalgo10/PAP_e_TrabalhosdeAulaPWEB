export default function Mensagem({ tipo = "info", texto = "Mensagem padrão" }) {
    const estilos = {
        sucesso: "bg-green-100 border-green-500 text-green-700",
        erro: "bg-red-100 border-red-500 text-red-700",
        aviso: "bg-yellow-100 border-yellow-500 text-yellow-700",
        info: "bg-blue-100 border-blue-500 text-blue-700",
    };

    return (
        <div className="min-h-screen bg-slate-900 flex items-center justify-center text-slate-100">
            <div className={`border-l-4 rounded-lg shadow-md p-4 w-96 transition ${estilos[tipo]}`}>
                <h2 className="font-semibold text-lg mb-2">Mensagem de {tipo}</h2>
                <p className="text-sm">{texto}</p>
            </div>
        </div>
    );
}
