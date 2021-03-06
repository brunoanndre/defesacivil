<?php
    include 'dao/ChamadoDaoPgsql.php';

    $chamadodao = new ChamadoDaoPgsql($pdo);

    $idFoto = $_POST['idFotoExcluir'];
    $id_chamado = filter_input(INPUT_POST, 'id_chamado');
 

    $linhaChamado = $chamadodao->buscarPeloId($id_chamado);
    $fotos = $linhaChamado->getFotos();

    $barras = array("{","}");
    $fotos = str_replace($barras,"",$fotos);
    $fotosArray = explode(",",$fotos);

    unset($fotosArray[$idFoto]);

    if(sizeof($fotosArray) == 0){
        $fotosString == 'null';
        $possui_fotos = 'false';
    }else{
        $fotosString = implode(",", $fotosArray);
        $fotosString = '{' . $fotosString . '}';
        $possui_fotos = 'true';
    }

    if($chamadodao->excluirFoto($id_chamado,$fotosString, $possui_fotos) == true){
        header('Location:index.php?pagina=editarChamado&id='.$id_chamado);
    }