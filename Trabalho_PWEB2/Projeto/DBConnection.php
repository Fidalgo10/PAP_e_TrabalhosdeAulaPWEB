<?php

$db_name = 'bdgestaodetarefas';

$link = mysqli_connect('localhost', 'root', '', $db_name);

if (!$link) {
    die('Não foi possível ligar à base de dados: ' . mysqli_connect_error());
}
?>