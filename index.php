<?php
session_start();
include 'database.php';

//echo pg_last_error();

if($_SESSION['login']){
    $pagina = $_GET['pagina'];
}else{
    if($_GET['pagina'] == 'esqueceuSenha')
        $pagina = 'esqueceuSenha';
    else
        $pagina = 'login';
}

if($pagina != 'login' && $pagina != 'esqueceuSenha')
    include 'header.php';

if(isset($_SESSION['nivel_acesso'])){
    if($_SESSION['nivel_acesso'] == 1){
        switch($pagina){
            case 'esqueceuSenha': include 'view/esqueceuSenha.php'; break;
            case 'cadastrarOcorrencia': include 'view/cadastrarOcorrencia.php'; break;
            case 'cadastrarUsuario': include 'view/cadastrarUsuario.php'; break; 
            case 'consultarUsuario': include 'view/consultarUsuario.php'; break;
            case 'exibirUsuario': include 'view/exibirUsuario.php'; break;
            case 'perfil': include 'view/perfil.php'; break;
            case 'EditarUsuario': include 'view/EditarUsuario.php'; break;
            case 'exibirOcorrencia': include 'view/exibirOcorrencia.php'; break;
            case 'editarOcorrencia' : include 'view/editarOcorrencia.php'; break;
            case 'cadastrarChamado' : include 'view/cadastrarChamado.php'; break;
            default: include 'view/consultarChamado.php'; break;
            case 'exibirChamado' : include 'view/exibirChamado.php'; break;
            case 'exibirPessoa' : include 'view/exibirPessoa.php'; break;
            case 'cadastrarInterdicao' : include 'view/cadastrarInterdicao.php'; break;
            case 'exibirInterdicao' : include 'view/exibirInterdicao.php'; break;
            case 'visualizarSensores' : include 'view/visualizarSensores.php'; break;
            case 'monitorarChamado' : include 'view/monitorarChamado.php'; break;
            case 'alterarSenha' : include 'view/alterarSenha.php'; break;
            case 'EditarPerfil' : include 'view/EditarPerfil.php'; break;
            case 'editarInterdicao' : include 'view/editarInterdicao.php'; break;
            case 'consultarInterdicao' : include 'view/consultarInterdicao.php'; break;
            case 'consultarOcorrencia' : include 'view/consultarOcorrencia.php'; break;
            case 'editarChamado' : include 'view/editarChamado.php'; break;
            case 'cadastrarNotificacao' : include 'view/cadastrarNotificacao.php'; break;
            case 'exibirNotificacao' : include 'view/exibirNotificacao.php'; break;
            case 'consultarNotificacao' : include 'view/consultarNotificacao.php'; break;
        }
    }else if($_SESSION['nivel_acesso'] == 2){
        switch($pagina){
            case 'esqueceuSenha': include 'view/esqueceuSenha.php'; break;
            case 'cadastrarOcorrencia': include 'view/cadastrarOcorrencia.php'; break;
            case 'perfil': include 'view/perfil.php'; break;
            case 'exibirOcorrencia': include 'view/exibirOcorrencia.php'; break;
            case 'editarOcorrencia' : include 'view/editarOcorrencia.php'; break;
            case 'exibirUsuario': include 'view/exibirUsuario.php'; break;
            case 'cadastrarChamado' : include 'view/cadastrarChamado.php'; break;
            default : include 'view/consultarChamado.php'; break;
            case 'exibirChamado' : include 'view/exibirChamado.php'; break;
            case 'exibirPessoa' : include 'view/exibirPessoa.php'; break;
            case 'cadastrarInterdicao' : include 'view/cadastrarInterdicao.php'; break;
            case 'exibirInterdicao' : include 'view/exibirInterdicao.php'; break;
            case 'visualizarSensores' : include 'view/visualizarSensores.php'; break;
            case 'monitorarChamado' : include 'view/monitorarChamado.php'; break;
            case 'alterarSenha' : include 'view/alterarSenha.php'; break;
            case 'EditarPerfil' : include 'view/EditarPerfil.php'; break;
            case 'editarInterdicao' : include 'view/editarInterdicao.php'; break;
            case 'consultarInterdicao' : include 'view/consultarInterdicao.php'; break;
            case 'consultarOcorrencia' : include 'view/consultarOcorrencia.php'; break;
            case 'editarChamado' : include 'view/editarChamado.php'; break;
            case 'cadastrarNotificacao' : include 'view/cadastrarNotificacao.php'; break;
            case 'exibirNotificacao' : include 'view/exibirNotificacao.php'; break;
            case 'consultarNotificacao' : include 'view/consultarNotificacao.php'; break;
        }
    }else if($_SESSION['nivel_acesso'] == 3){
        switch($pagina){
            case 'esqueceuSenha': include 'view/esqueceuSenha.php'; break;
            case 'cadastrarOcorrencia': include 'view/cadastrarOcorrencia.php'; break;
            case 'perfil': include 'view/perfil.php'; break;
            case 'exibirOcorrencia': include 'view/exibirOcorrencia.php'; break;
            case 'editarOcorrencia' : include 'view/editarOcorrencia.php'; break;
            case 'exibirUsuario': include 'view/exibirUsuario.php'; break;
            case 'cadastrarChamado' : include 'view/cadastrarChamado.php'; break;
            default : include 'view/consultarChamado.php'; break;
            case 'exibirChamado' : include 'view/exibirChamado.php'; break;
            case 'exibirPessoa' : include 'view/exibirPessoa.php'; break;
            case 'cadastrarInterdicao' : include 'view/cadastrarInterdicao.php'; break;
            case 'exibirInterdicao' : include 'view/exibirInterdicao.php'; break;
            case 'visualizarSensores' : include 'view/visualizarSensores.php'; break;
            case 'monitorarChamado' : include 'view/monitorarChamado.php'; break;
            case 'alterarSenha' : include 'view/alterarSenha.php'; break;
            case 'EditarPerfil' : include 'view/EditarPerfil.php'; break;
            case 'editarInterdicao' : include 'view/editarInterdicao.php'; break;
            case 'consultarInterdicao' : include 'view/consultarInterdicao.php'; break;
            case 'consultarOcorrencia' : include 'view/consultarOcorrencia.php'; break;
            case 'editarChamado' : include 'view/editarChamado.php'; break;
            case 'cadastrarNotificacao' : include 'view/cadastrarNotificacao.php'; break;
            case 'exibirNotificacao' : include 'view/exibirNotificacao.php'; break;
            case 'consultarNotificacao' : include 'view/consultarNotificacao.php'; break;
        }
    }else{
        include 'view/monitorarChamado.php';
    }
}else{
    switch($pagina){
        case 'esqueceuSenha': include 'view/esqueceuSenha.php'; break;
        default: include 'view/login.php'; break;
    }
}

include 'footer.php';   