import { useState } from 'react'

export default function VerificadorParImpar({ onBack }) {
  const [numero, setNumero] = useState('')
  const [resultado, setResultado] = useState('')

  function verificarParImpar(valor) {
    const n = Number(valor)
    setNumero(valor)

    if (valor === '') {
      setResultado('')
      return
    }

    if (isNaN(n)) {
      setResultado('Por favor, introduz um número válido.')
    } else {
      if (n % 2 === 0) {
        setResultado(`${n} é um número par`)
      } else {
        setResultado(`${n} é um número ímpar`)
      }
    }
  }

  return (
    <div>
      <h2>Verificador de Par ou Ímpar</h2>

      <input
        type="number"
        placeholder="Escreve um número..."
        value={numero}
        onChange={(e) => verificarParImpar(e.target.value)}
      />

      {resultado && <p>{resultado}</p>}

    </div>
  )
}
