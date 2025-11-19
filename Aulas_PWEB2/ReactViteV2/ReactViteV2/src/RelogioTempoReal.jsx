import { useState, useEffect } from "react";

export default function RelogioTempoReal({ onBack }) {
  const [hora, setHora] = useState(new Date().toLocaleTimeString());

  useEffect(() => {
    const intervalo = setInterval(() => {
      setHora(new Date().toLocaleTimeString());
    }, 1000);
    return () => clearInterval(intervalo);
  }, []);

  return (
    <div>
      <h2>Relógio em Tempo Real</h2>
      <p style={{ fontSize: "24px" }}>Hora Atual: {hora}</p>
    </div>
  );
}
