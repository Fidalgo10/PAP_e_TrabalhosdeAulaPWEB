<?php
    // Conectar ao banco de dados
    include "../include/aceder_base_dados.inc.php";
?>


// logout.php
<?php
session_start();
session_destroy();
header("Location: ../index.php");
exit;
?>
<?php
