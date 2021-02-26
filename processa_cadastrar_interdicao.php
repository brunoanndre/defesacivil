<?php
    //inclui a conexao com o banco de dados
    include 'database.php';

    //recebe dados do $_POST
    $id_ocorrencia = addslashes($_POST['id_ocorrencia']);
    $data = addslashes($_POST['data']);
    $hora = addslashes($_POST['horario']);
    $motivo = addslashes($_POST['motivo']);
    $descricao_interdicao = addslashes($_POST['descricao_interdicao']);
    $danos_aparentes = addslashes($_POST['danos_aparentes']);
    $bens_afetados = addslashes($_POST['bens_afetados']);
    $tipo = addslashes($_POST['tipo']);

    session_start();
    $id_usuario = $_SESSION['id_usuario'];
    $dataAtual = date('Y-m-d H:i:s');

    $timestamp = $data.' '.$hora.':00';

    $sql = $pdo->prepare("INSERT INTO interdicao (data_hora, id_ocorrencia, motivo, descricao_interdicao, danos_aparentes, bens_afetados, tipo) 
    VALUES (:timestamp, :id_ocorrencia, :motivo, :descricao_interdicao, :danos_aparentes, :bens_afetados, :tipo)
    RETURNING id_interdicao");
    $sql->bindValue(":timestamp", $timestamp);
    $sql->bindValue(":id_ocorrencia", $id_ocorrencia);
    $sql->bindValue(":motivo", $motivo);
    $sql->bindValue(":descricao_interdicao", $descricao_interdicao);
    $sql->bindValue(":danos_aparentes", $danos_aparentes);
    $sql->bindValue(":bens_afetados", $bens_afetados);
    $sql->bindValue(":tipo", $tipo);
    $sql->execute();


    if($sql){
        $id_interdicao = $sql->fetch()['id_interdicao'];

        $sql = $pdo->prepare( "INSERT INTO log_interdicao (data_hora, id_usuario, id_interdicao)
        VALUES (:dataAtual, :id_usuario, :id_interdicao)");
        $sql->bindValue(":dataAtual", $dataAtual);
        $sql->bindValue(":id_usuario", $id_usuario);
        $sql->bindValue(":id_interdicao", $id_interdicao);
        $sql->execute();

        header('location:index.php?pagina=exibirInterdicao&id='.$id_interdicao);
    }else{
        //echo pg_last_error();
        header('location:index.php?pagina=cadastrarInterdicao&erroDB');
    }
