<?php

    include('database.php');

    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $acesso = $_POST['acesso'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

$custo = '08';
$string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
$salt = '';
for ($i = 0; $i < 22; $i++){
	$salt = $salt.$string[rand(0,61)];
}
$salt = str_shuffle($salt);
$hash = crypt($senha, '$2a$' . $custo . '$' . $salt . '$');

$sql = $pdo->prepare("INSERT INTO dados_login (email, senha) VALUES (:email, :hash) RETURNING id_usuario");
$sql->bindValue(":email", $email);
$sql->bindValue(":hash", $hash);
$sql->execute();


$id = $sql->fetch()['id_usuario'];

$query = "INSERT INTO usuario (id_usuario, nome, cpf, telefone, nivel_acesso, foto) 
VALUES (:id, :nome, :cpf, :telefone', :acesso, '');";
$sql = $pdo->prepare($query);
$sql->bindValue(":id", $id);
$sql->bindValue(":nome", $nome);
$sql->bindValue(":cpf", $cpf);
$sql->bindValue(":telefone", $telefone);
$sql->bindValue(":acesso", $acesso);
$sql->execute();

if(!$sql){
	echo "Nao deu".'<br>';
	echo pg_last_error();
}else
	echo "Cadastro com sucesso";

?>