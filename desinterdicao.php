<?php
    include 'database.php';

    session_start();
    $id_usuario = $_SESSION['id_usuario'];
    $dataAtual = date('Y-m-d H:i:s');

    $id_interdicao = $_POST['id_interdicao'];
    $id_ocorrencia = $_POST['id_ocorrencia'];

    
    $query = "UPDATE interdicao SET interdicao_ativa=false WHERE id_interdicao = :id_interdicao";
    $sql = $pdo->prepare($query);
    $sql->bindValue(":id_interdicao", $id_interdicao);
    $sql->execute();
    
    if($sql){

        $query = "INSERT INTO log_interdicao (data_hora, id_usuario, id_interdicao, acao)
                  VALUES (:dataAtual, :id_usuario, :id_interdicao, 'desinterditar')";
        $sql = $pdo->prepare($query);
        $sql->bindValue(":dataAtual", $dataAtual);
        $sql->bindValue(":id_usuario", $id_usuario);
        $sql->bindValue(":id_interdicao", $id_interdicao);
        $sql->execute();
        
        header('location:index.php?pagina=exibirOcorrencia&id='.$id_ocorrencia.'&sucessoInterdicao');
    }else
        header('location:index.php?pagina=exibirInterdicao&id='.$id_interdicao.'&errorDB');