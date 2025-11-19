export default function GrelhaCartoes() {
  const dados = [
    {
      titulo: "Gestão de Tarefas",
      descricao: "Organize e acompanhe suas tarefas diárias com facilidade.",
      // imagem: "./tarefas.jpg",
    },
    {
      titulo: "Análise de Vendas",
      descricao: "Visualize o desempenho de vendas com gráficos interativos.",
      // imagem: "./vendas.jpg",
    },
    {
      titulo: "Monitoramento de Saúde",
      descricao: "Acompanhe seus indicadores de saúde em tempo real.",
      // imagem: "./saude.jpg",
    },
    {
      titulo: "Planejamento Financeiro",
      descricao: "Gerencie seu orçamento e despesas de forma eficiente.",
      // imagem: "./financas.jpg",
    },
    {
      titulo: "Gerenciamento de Projetos",
      descricao: "Colabore com sua equipe e mantenha os projetos no caminho certo.",
      // imagem: "./projetos.jpg",
    },
    {
      titulo: "Relatórios de Marketing",
      descricao: "Avalie o impacto das suas campanhas de marketing.",
      // imagem: "./marketing.jpg",
    },
  ];

  return (
    <div className="min-h-screen bg-slate-900 p-6 text-slate-100">
      <h1 className="text-3xl font-bold mb-6 text-indigo-400 text-center">
        Grelha de Cartões Responsivos
      </h1>
      <div className="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        {dados.map((item, index) => (
          <div
            key={index}
            className="bg-slate-800 p-5 rounded-xl shadow-md hover:shadow-xl transition-shadow hover:scale-105 hover:shadow-indigo-100/20"
          >
            <h2 className="text-xl font-semibold mb-2 text-indigo-300">
              {item.titulo}
            </h2>
            <p className="text-slate-300">{item.descricao}</p>
          </div>
        ))}
      </div>
    </div>
  );
}
