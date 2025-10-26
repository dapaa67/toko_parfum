<?php
$password_asli = '123'; // GANTI password ini dengan yang lo mau
$hash_baru = password_hash($password_asli, PASSWORD_DEFAULT);
echo "Password Asli: " . $password_asli . "<br>";
echo "Hash Baru (Copy ini ke DB): " . $hash_baru;
?>