import { useState } from 'react'; 
import ContadorAutomatico from './ContadorAutomatico';
import ConvertorTemperatura from './ConvertorTemperatura';
import VerificadorParImpar from './VerificadorParImpar';
import CalcularIMC from './CalcularIMC';
import GeradorCores from './GeradorCores';
import ListaCompras from './ListaCompras';
import ContadorCliques from './ContadorCliques';
import FasesAleatorias from './FrasesAleatorias';
import RelogioTempoReal from './RelogioTempoReal';
import SimuladorNotas from './SimuladorNotas';

export default function App() { 
  const [opcao, setOpcao] = useState('menu'); 

  function renderizarConteudo() {
    switch (opcao) {
      case 'ContadorAutomatico':
        return <ContadorAutomatico onBack={() => setOpcao('menu')} />;
      case 'ConvertorTemperatura':
        return <ConvertorTemperatura onBack={() => setOpcao('menu')} />;  
      case 'VerificadorParImpar':
        return <VerificadorParImpar onBack={() => setOpcao('menu')} />;
      case 'CalcularIMC':
        return <CalcularIMC onBack={() => setOpcao('menu')} />; 
      case 'GeradorCores':
        return <GeradorCores onBack={() => setOpcao('menu')} />; 
      case 'ListaCompras':
        return <ListaCompras onBack={() => setOpcao('menu')} />;
      case 'ContadorCliques':
        return <ContadorCliques onBack={() => setOpcao('menu')} />;
      case 'FasesAleatorias':
        return <FasesAleatorias onBack={() => setOpcao('menu')} />;
      case 'RelogioTempoReal':
        return <RelogioTempoReal onBack={() => setOpcao('menu')} />;
      case 'SimuladorNotas':
        return <SimuladorNotas onBack={() => setOpcao('menu')} />;
      default: 
        return (
          <div>
            <h1>Exemplos React + Vite - Projeto 2</h1>
            <nav>
              <button onClick={() => setOpcao('ContadorAutomatico')}>Contador Automático</button>
              <button onClick={() => setOpcao('ConvertorTemperatura')}>Convertor de Temperatura</button>
              <button onClick={() => setOpcao('VerificadorParImpar')}>Verificador Par ou Ímpar</button>
              <button onClick={() => setOpcao('CalcularIMC')}>Calculadora de IMC</button>
              <button onClick={() => setOpcao('GeradorCores')}>Gerador de Cores</button>
              <button onClick={() => setOpcao('ListaCompras')}>Lista de Compras</button>
              <button onClick={() => setOpcao('ContadorCliques')}>Contador de Cliques</button>
              <button onClick={() => setOpcao('FasesAleatorias')}>Gerador de Frases Aleatórias</button>
              <button onClick={() => setOpcao('RelogioTempoReal')}>Relógio em Tempo Real</button>
              <button onClick={() => setOpcao('SimuladorNotas')}>Simulador de Notas</button>
            </nav>  
          </div>
        )
    }
  }

  return (
    <div>
      {renderizarConteudo()}
      <br/>
      {opcao !== 'menu' && (
        <button onClick={() => setOpcao('menu')}>Voltar ao Menu</button>
      )}
    </div>
  )
}