<?php
session_start();

$response = [
    'logged_in' => isset($_SESSION['id_utilizador'])
];

header('Content-Type: application/json');
echo json_encode($response);
?>
