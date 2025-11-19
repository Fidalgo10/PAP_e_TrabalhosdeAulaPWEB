import { useState } from "react";

export default function CalcularIMC({ onBack }) {
  const [peso, setPeso] = useState("");
  const [altura, setAltura] = useState("");
  const [imc, setIMC] = useState(null);
  const [mensagem, setMensagem] = useState("");

  function calcularIMC() {
    const p = Number(peso);
    const a = Number(altura);

    // ❌ Estava com parênteses incorretos e OR fora do lugar
    if (p <= 0 || a <= 0 || isNaN(p) || isNaN(a)) {
      setMensagem("Por favor, insira valores válidos para peso e altura.");
      setIMC(null);
      return;
    }

    // ❌ .toFixed(2) estava aplicado ao denominador, o que é incorreto
    const resultadoIMC = p / (a * a);
    const resultadoFormatado = resultadoIMC.toFixed(2);
    setIMC(resultadoFormatado);

    // ✅ Corrigido para comparar com o número original (não formatado)
    if (resultadoIMC < 18.5) {
      setMensagem("Abaixo do peso");
    } else if (resultadoIMC < 24.9) {
      setMensagem("Peso normal");
    } else if (resultadoIMC < 29.9) {
      setMensagem("Sobrepeso");
    } else {
      setMensagem("Obesidade");
    }
  }

  return (
    <div>
      <h2>Calculadora de IMC</h2>

      <div>
        <label>Peso (kg): </label>
        <input
          type="number"
          value={peso}
          onChange={(e) => setPeso(e.target.value)}
        />
      </div>

      <div>
        <label>Altura (m): </label>
        <input
          type="number"
          value={altura}
          onChange={(e) => setAltura(e.target.value)}
        />
      </div>

      <button onClick={calcularIMC}>Calcular IMC</button>

      {imc !== null && (
        <div>
          <h3>O seu IMC é: {imc}</h3>
          <p>{mensagem}</p>
        </div>
      )}
    </div>
  );
}
