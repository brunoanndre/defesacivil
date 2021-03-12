<?php
    include 'database.php';
    require_once 'dao/IntedicaoDaoPgsql.php';

    $interdicaodao = new IntedicaoDaoPgsql($pdo);

    session_start();
    $id_usuario = $_SESSION['id_usuario'];
    $dataAtual = date('Y-m-d H:i:s');

    $id_interdicao = $_POST['id_interdicao'];
    $id_ocorrencia = $_POST['id_ocorrencia'];

    if($interdicaodao->remover($id_interdicao) ){
    $acao = "desinterditar";
    $interdicaodao->adicionarLog($dataAtual,$id_usuario,$acao,$id_interdicao);

        header('location:index.php?pagina=exibirOcorrencia&id='.$id_ocorrencia.'&sucessoInterdicao');
    }else
        header('location:index.php?pagina=exibirInterdicao&id='.$id_interdicao.'&errorDB');