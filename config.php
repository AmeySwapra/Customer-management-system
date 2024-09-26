<?php
$host = 'sql111.infinityfree.com'; 
$db = 'if0_37390063_customercrudoperation'; 
$user = 'if0_37390063'; 
$pass = 'svDb5y1If4CM'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}
?>
