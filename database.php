<?php
$host = 'localhost';
$dbname = 'defesa_civil';
$user = 'df_user';
$password = 'kaug6a38';
try{
$pdo = new PDO('pgsql:host=localhost;dbname=defesa_civil', $user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo "ERROR". $e->getMessage();
}
