<?php
    include 'database.php';
    require_once 'dao/UsuarioDaoPgsql.php';

    $usuariodao = new UsuarioDaoPgsql($pdo);
    session_start();

    $id_usuario_modificador = $_SESSION['id_usuario'];
    $id_usuario_alterado = $_POST['id'];
    $data = date('Y-m-d H:i:s');

    if($_SESSION['nivel_acesso'] == 1){
        $usuariodao->deleteDadosLogin($id_usuario_alterado);
        $usuariodao->deleteUsuario($id_usuario_alterado);
        $usuariodao->alterarUsuarioExcluido($id_usuario_modificador,$id_usuario_alterado,$data);
            header('location:index.php?pagina=consultarUsuario&sucesso');
    }
    