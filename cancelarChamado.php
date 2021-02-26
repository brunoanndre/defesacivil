<?php
//inclui a conexao com o banco de dados
include 'database.php';

//recebe dados do $_POST
$id_chamado = addslashes($_POST['id_chamado']);
$motivo = addslashes($_POST['motivo']);
$erros='';

session_start();
$id_usuario = $_SESSION['id_usuario'];
$dataAtual = date('Y-m-d H:i:s');

$sql = $pdo->prepare("UPDATE chamado SET usado = true, cancelado = true, motivo = :motivo WHERE id_chamado = :id_chamado");
$sql->bindValue(":motivo", $motivo);
$sql->bindValue(":id_chamado", $id_chamado);
$sql->execute();
	
if($sql){
	$sql = $pdo->prepare("INSERT INTO log_chamado (id_usuario, id_chamado, data_hora, acao) VALUES
			(:id_usuario, :id_chamado, :dataAtual, 'cancelar')");
	$sql->bindValue(":id_usuario", $id_usuario);
	$sql->bindValue(":id_chamado", $id_chamado);
	$sql->bindValue(":dataAtual", $dataAtual);
	$sql->execute();
		
	header('location:index.php?pagina=consultarChamado&sucesso');
}else{
	//echo pg_last_error();
	header('location:index.php?pagina=consultarChamado&erroDB');
}
