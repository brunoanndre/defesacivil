<?php
    include 'database.php';

    //get the q parameter from URL
    $q=$_GET["q"];
    $id_input="'".$_GET['id']."'";

    if(substr($_GET['id'],0,1) == "a"){
        $sql = $pdo->prepare("SELECT nome, ativo FROM usuario u 
        INNER JOIN dados_login d ON u.id_usuario = d.id_usuario WHERE nome ILIKE '$q%' AND ativo = 'true' LIMIT 5");
    }else{
        $sql = $pdo->prepare("SELECT nome FROM pessoa WHERE nome ILIKE '$q%' LIMIT 5");
    }

    $sql->execute();

    $hint = "";
    $linha = $sql->fetchAll(PDO::FETCH_BOTH);
 
    foreach($linha as $item){
        $hint = $hint.'<input type="button" class="autocompleteBtn" value="'.$item['nome'].'" onclick="selecionaComplete(this.value,'.$id_input.')"><br>';
    }

    // Set output to "no suggestion" if no hint was found
    // or to the correct values
    if ($hint=="") {
        if(substr($_GET['id'],0,1) == "a"){
            $response="Usuário não encontrado.";
        }else{
            $response="Pessoa não encontrada.";
        }
        
    } else {
        $response=$hint;
    }

    //output the response
    echo $response;
?>