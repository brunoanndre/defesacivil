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

    $fotosString = implode(",", $fotosArray);
    $fotosString = '{' . $fotosString . '}';


    if($chamadodao->excluirFoto($id_chamado,$fotosString) == true){
        header('Location:index.php?pagina=editarChamado&id='.$id_chamado);
    }