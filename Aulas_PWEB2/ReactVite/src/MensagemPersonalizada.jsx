export default function MensagemPersonalizada(props) {
    return (
        <div>
            <h2>{props.titulo}</h2>
            <p>{props.texto}</p>
        </div>
    );
}