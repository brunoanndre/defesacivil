<?php
include 'database.php';
require_once 'dao/UsuarioDaoPgsql.php';

$usuarioDao = new UsuarioDaoPgsql($pdo);


$nome = addslashes($_POST['nome']);
$cpf = addslashes($_POST['cpf']);
$telefone = addslashes($_POST['telefone']);
$nivel_acesso = addslashes($_POST['nivel_acesso']);
$email = filter_input(INPUT_POST, 'email_cadastro', FILTER_VALIDATE_EMAIL);
$senha = ($_POST['senha_cadastro']);
$senha_confirma = ($_POST['senha_cadastro_confirma']);
$foto = $_FILES["foto"]["tmp_name"];

if($foto != ''){
$binary = file_get_contents($foto);
$base64 = base64_encode($binary);
}
$custo = '08';
$string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
$salt = '';
for ($i = 0; $i < 22; $i++){
	$salt = $salt.$string[rand(0,61)];
}
$salt = str_shuffle($salt);
$hash = crypt($senha, '$2a$' . $custo . '$' . $salt . '$');

if($email && $senha){
	if($usuarioDao->findByEmail($email) === false){
		$novoUsuario = New Usuario();
		$novoUsuario->setEmail($email);
		$novoUsuario->setSenha($hash);

		$usuarioDao->addDadosLogin($novoUsuario);
	}
}
$id= $usuarioDao->findId($email);

$acesso;
if($nivel_acesso == 'Diretor'){
	$acesso = 1;
}else if($nivel_acesso == 'Coordenador'){
	$acesso = 2;
}else if($nivel_acesso == 'Agente'){
	$acesso = 3;
}else{
	$acesso = 4;
}

session_start();
$id_criador = $_SESSION['id_usuario'];
$data = date('Y-m-d H:i:s');


if($nome && $cpf && $id && $telefone && $acesso){
	$novoUsuario = New Usuario();
	$novoUsuario->setId($id);
	$novoUsuario->setNome($nome);
	$novoUsuario->setCPF($cpf);
	$novoUsuario->setTelefone($telefone);
	$novoUsuario->setAcesso($acesso);
	$novoUsuario->setFoto($base64);

	$usuarioDao->addUsuario($novoUsuario);

	$usuarioDao->alterarUsuarioAdicionado($id_criador,$id,$data);
	
	header('location:index.php?pagina=cadastrarUsuario&sucesso');
}else{
	header('location:index.php?pagina=cadastrarUsuario&erroDB');
}
