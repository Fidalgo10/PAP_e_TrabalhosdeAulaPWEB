<?php
// Conexão à base de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pap_simao";

// Criar a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>