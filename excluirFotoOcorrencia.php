<?php

    require_once 'dao/OcorrenciaDaoPgsql.php';

    $ocorrenciadao = New OcorrenciaDaoPgsql($pdo);

    $idFoto = $_POST['idFotoExcluir'];
    $id_ocorrencia = filter_input(INPUT_POST, 'id_ocorrencia');


    $linhaOcorrencia = $ocorrenciadao->buscarPeloId($id_ocorrencia);
    $fotos = $linhaOcorrencia->getFotos();

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

    if($ocorrenciadao->excluirFoto($id_ocorrencia,$fotosString,$possui_fotos) == true){
        header('Location:index.php?pagina=editarOcorrencia&id='.$id_ocorrencia);
    }