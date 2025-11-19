<?php
$nova_pass = 'simao123'; // a password que queres usar
$hash = password_hash($nova_pass, PASSWORD_DEFAULT);
echo "Hash gerado: " . $hash;
?>
