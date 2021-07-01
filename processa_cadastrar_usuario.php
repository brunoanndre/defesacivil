<?php
include 'database.php';
require_once 'dao/UsuarioDaoPgsql.php';


$usuarioDao = new UsuarioDaoPgsql($pdo);


$nome = filter_input(INPUT_POST, 'nome');
$cpf = filter_input(INPUT_POST, 'cpf');
$telefone = filter_input(INPUT_POST, 'telefone');
$nivel_acesso = filter_input(INPUT_POST, 'nivel_acesso');
$email = filter_input(INPUT_POST, 'email_cadastro', FILTER_VALIDATE_EMAIL);
$senha = filter_input(INPUT_POST, 'senha_cadastro'); 
$senha_confirma = filter_input(INPUT_POST, 'senha_cadastro_confirma');
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

		$id = $usuarioDao->addDadosLogin($novoUsuario);
	}else{
		header('Location:index.php?pagina=cadastrarUsuario&erroEmail');
	}
}

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

if($nome && $telefone && $acesso){
	$novoUsuario = New Usuario();
	$novoUsuario->setId($id);
	$novoUsuario->setNome($nome);
	$novoUsuario->setCPF($cpf);
	$novoUsuario->setTelefone($telefone);
	$novoUsuario->setAcesso($acesso);
	$novoUsuario->setFoto($base64);

	$id = $usuarioDao->addUsuario($novoUsuario);

	$usuarioDao->alterarUsuarioAdicionado($id_criador,$id,$data);
	
	header('location:index.php?pagina=cadastrarUsuario&sucesso');
}else{
	header('location:index.php?pagina=cadastrarUsuario&erroDB');
}
