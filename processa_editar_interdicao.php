<?php

require_once 'database.php';
require_once 'dao/IntedicaoDaoPgsql.php';

$interdicaodao = new IntedicaoDaoPgsql($pdo);

//RECEBE OS DADOS DO POST
$data = filter_input(INPUT_POST,'data');
$horario = filter_input(INPUT_POST, 'horario');
$motivo = filter_input(INPUT_POST, 'motivo');
$descricao = filter_input(INPUT_POST, 'descricao_interdicao');
$danos = filter_input(INPUT_POST, 'danos_aparentes');
$bens_afetados = filter_input(INPUT_POST, 'bens_afetados');
$tipo = filter_input(INPUT_POST, 'tipo');
$id_interdicao = filter_input(INPUT_POST,'id_interdicao');
$timestamp = $data.' '.$horario.':00';

if($data && $horario && $motivo && $descricao && $danos && $bens_afetados && $tipo){
    $editarinterdicao = new Interdicao();
    $editarinterdicao->setData($timestamp);
    $editarinterdicao->setMotivo($motivo);
    $editarinterdicao->setDescricao($descricao);
    $editarinterdicao->setDanos($danos);
    $editarinterdicao->setBensAfetados($bens_afetados);
    $editarinterdicao->setTipo($tipo);
    $editarinterdicao->setId($id_interdicao);
    
    if($interdicaodao->editar($editarinterdicao) == true){
        header('Location:index.php?pagina=exibirInterdicao&id='. $id_interdicao . '&sucessoalt');
    }else{
        header('Location:index.php?pagina=editarInterdicao&id='. $id_interdicao . '&erroDB');
    }
    


}

var_dump($descricao);
die;


