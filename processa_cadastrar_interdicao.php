<?php
    //inclui a conexao com o banco de dados
    include 'database.php';
    require_once 'dao/IntedicaoDaoPgsql.php';

    $interdicaodao = new IntedicaoDaoPgsql($pdo);

    //recebe dados do $_POST
    $id_ocorrencia = filter_input(INPUT_POST, 'id_ocorrencia');
    $data = filter_input(INPUT_POST, 'data');
    $hora = filter_input(INPUT_POST, 'horario');
    $motivo = filter_input(INPUT_POST, 'motivo');
    $descricao_interdicao = filter_input(INPUT_POST, 'descricao_interdicao');
    $danos_aparentes = filter_input(INPUT_POST, 'danos_aparentes');
    $bens_afetados = filter_input(INPUT_POST, 'bens_afetados');
    $tipo = filter_input(INPUT_POST, 'tipo');

    session_start();
    $id_usuario = $_SESSION['id_usuario'];
    $dataAtual = date('Y-m-d H:i:s');

    $timestamp = $data.' '.$hora.':00';

    $novaintedicao = new Interdicao();
    $novaintedicao->setData($timestamp);
    $novaintedicao->setIdOcorrencia($id_ocorrencia);
    $novaintedicao->setMotivo($motivo);
    $novaintedicao->setDescricao($descricao_interdicao);
    $novaintedicao->setDanos($danos_aparentes);
    $novaintedicao->setBensAfetados($bens_afetados);
    $novaintedicao->setTipo($tipo);

    if($id = $interdicaodao->adicionar($novaintedicao)){
    $acao = "interditar";
    $id_interdicao = $id->getId(); 
        $interdicaodao->adicionarLog($dataAtual, $id_usuario, $acao, $id_interdicao);
 
        header('location:index.php?pagina=exibirInterdicao&id='.$id_interdicao .'&sucesso' );
    }else{
        //echo pg_last_error();
        header('location:index.php?pagina=cadastrarInterdicao&erroDB');
    }
