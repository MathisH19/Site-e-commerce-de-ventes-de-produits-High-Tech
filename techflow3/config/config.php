<?php
$host     = '54.38.84.29';
$port     = 3306;
$dbname   = 'techflow';
$username = 'jeff';
$password = 'jeff';

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
global $pdo;
/*
 * Config et la connexion PDO
 */
// 1. Définir l'URL racine de votre projet (Ajustez "techflow3" si le dossier change)
define('BASE_URL', 'http://localhost/techflow3');
try {
    $pdo = new PDO($dsn,$username,$password,[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}catch (Throwable $error){
    http_response_code(500);
    echo "ERREUR BDD : " . $error->getMessage();
    exit;
}
return $pdo;