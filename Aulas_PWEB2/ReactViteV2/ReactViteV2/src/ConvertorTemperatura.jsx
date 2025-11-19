import { useState } from "react";

export default function ConvertorTemperatura({ onBack }) {
    const [celsius, setCelsius] = useState('');
    const [fahrenheit, setFahrenheit] = useState('');
    const [kelvin, setKelvin] = useState('');

    function converterDeCelsius(valor) {
        const c = Number(valor);
        setCelsius(valor);

        const f = ((c * 9) / 5 + 32).toFixed(2);
        const k = (c + 273.15).toFixed(2);

        setFahrenheit(f);
        setKelvin(k);
    }

    return (
        <div>
            <h2>Convertor de Temperatura</h2>
            <input
                type="number"
                placeholder="Temperatura em Celsius"
                value={celsius}
                onChange={(e) => converterDeCelsius(e.target.value)}
            />

            {celsius !== '' && (
                <div>
                    <p>{celsius}°C = {fahrenheit} °F (Fahrenheit)</p>
                    <p>{celsius}°C = {kelvin} K (Kelvin)</p>
                </div>
            )}
        </div>
    );
}
