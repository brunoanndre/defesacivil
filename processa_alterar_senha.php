<?php
include 'database.php';
require_once 'dao/UsuarioDaoPgsql.php';

$usuariodao = New UsuarioDaoPgsql($pdo);
session_start();
$id_usuario = $_SESSION['id_usuario'];

$senha_anterior = filter_input(INPUT_POST,'senha_anterior');
$nova_senha = filter_input(INPUT_POST,'nova_senha');
$senha_confirma = filter_input(INPUT_POST,'senha_confirma');

$linha = $usuariodao->findById($id_usuario);

if(!$linha){ //caso ocorra algum erro na conexao
    //echo pg_last_error();
    header('location:alterarSenha.php?erroBD');
}else{
    $hash = $linha->getSenha(); //pega a senha do banco de dados

    if(crypt($senha_anterior, $hash) === $hash){ //compara com a criptografia
        //inicia uma sessao, e salva o id do usuario logado
        $custo = '08';
        $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $salt = '';
        for ($i = 0; $i < 22; $i++){
            $salt = $salt.$string[rand(0,61)];
        }
        $salt = str_shuffle($salt);
        $novo_hash = crypt($nova_senha, '$2a$' . $custo . '$' . $salt . '$');

       $usuariodao->alterarSenha($novo_hash,$id_usuario);

        if($usuariodao){
            header('location:index.php?pagina=alterarSenha&sucesso');
        }else{
            header('location:alterarSenha.php?erroBD');
        }
    }else{
        //retorna o erro
        header('location:index.php?pagina=alterarSenha&erroSenha');
    }
}
