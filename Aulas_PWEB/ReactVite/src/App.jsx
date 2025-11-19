import { useState } from 'react';
import MensagemInicial from './MensagemInicial';
import MensagemPersonalizada from './MensagemPersonalizada';
import Contador from './Contador';
import MudarTexto from './MudarTexto';
import MostrarEsconder from './MostrarEsconder';
import AlterarCor from './AlterarCor';
import Tema from './AlterarTema';
import AlterarNome from './AlterarNome';
import MostrarHora from './MostrarHora';
import Calculadora from './calculadora';

export default function App() {
  const [opcao, setOpcao] = useState('menu');

  function renderizarConteudo() {
    if (opcao === 'mensagemInicial') {
      return <MensagemInicial />;
    } else if (opcao === 'mensagemPersonalizada') {
      return (
        <div>
          <MensagemPersonalizada
            titulo="React é modular"
            texto="Cada componente pode receber dados diferentes através das props."
          />
          <MensagemPersonalizada
            titulo="Reutilização é a chave"
            texto="Com o mesmo componente, conseguimos mostrar conteúdos diferentes."
          />
        </div>
      );
    } else if (opcao === 'contador') {
      return <Contador />;
    } else if (opcao === 'mudarTexto') {
      return <MudarTexto />;
    } else if (opcao === 'mostrarEsconder') {
      return <MostrarEsconder />;
    } else if (opcao === 'alterarCor') {
      return <AlterarCor />;
    } else if (opcao === 'alterarTema') {
      return <Tema />;
    } else if (opcao === 'alterarNome') {
      return <AlterarNome />;
    } else if (opcao === 'mostrarHora') {
      return <MostrarHora />;
    } else if (opcao === 'calculadora') {
      return <Calculadora />;
    } else {
      return <p>Escolhe um exemplo no menu.</p>;
    }
  }

  return (
    <div>
      <h1>Exemplos React + Vite</h1>

      <nav style={{ display: 'flex', gap: '10px', flexWrap: 'wrap', marginBottom: '20px' }}>
        <button onClick={() => setOpcao('mensagemInicial')}>Mensagem Inicial</button>
        <button onClick={() => setOpcao('mensagemPersonalizada')}>Mensagem Personalizada</button>
        <button onClick={() => setOpcao('contador')}>Contador</button>
        <button onClick={() => setOpcao('mudarTexto')}>Mudar Texto</button>
        <button onClick={() => setOpcao('mostrarEsconder')}>Mostrar / Esconder</button>
        <button onClick={() => setOpcao('alterarCor')}>Alterar Cor</button>
        <button onClick={() => setOpcao('alterarTema')}>Alterar Tema</button>
        <button onClick={() => setOpcao('alterarNome')}>Alterar Nome</button>
        <button onClick={() => setOpcao('mostrarHora')}>Mostrar Hora</button>
        <button onClick={() => setOpcao('calculadora')}>Calculadora</button>
      </nav>

      {renderizarConteudo()}
    </div>
  );
}
