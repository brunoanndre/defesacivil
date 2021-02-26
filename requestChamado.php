<?php
    include 'database.php';

    $consultaChamado = $pdo->prepare("SELECT * FROM chamado WHERE usado = false ORDER BY chamado.data_hora DESC LIMIT 5");
    $consultaChamado->execute();



    $response = "";
    $linha = $consultaChamado->fetchAll();
    foreach($linha as $linhaChamado){
        if($linhaChamado['endereco_principal'] == "Logradouro"){
            $id_logradouro = $linhaChamado['chamado_logradouro_id'];
            $sql = $pdo->prepare("SELECT * FROM endereco_logradouro WHERE id_logradouro = :id_logradouro");
            $sql->bindValue(":id_logradouro", $id_logradouro);
            $sql->execute();
            $linhaLogradouro = $sql->fetch();
        }

        $id_pessoa = $linhaChamado['pessoa_id'];
        $nomePessoa = "Não cadastrada.";
        if($id_pessoa != NULL){
            $sql = $pdo->prepare("SELECT nome FROM pessoa WHERE id_pessoa = :id_pessoa");
            $sql->bindValue(":id_pessoa", $id_pessoa);
            $sql->execute();

            $linhaPessoa = $sql->fetch();
            $nomePessoa = $linhaPessoa['nome'];
        }

        $id_agente = $linhaChamado['agente_id'];
        $sql = $pdo->prepare("SELECT nome FROM usuario WHERE id_usuario = :id_agente");
        $sql->bindValue(":id_agente", $id_agente);
        $sql->execute();
        $linhaAgente = $sql->fetch();

        $color = '#88ff50';
        if($linhaChamado['prioridade'] == "Alta"){
            $color = '#ff5050';
        }else if($linhaChamado['prioridade'] == "Média"){
            $color = '#fff050';
        }

        $response = $response.'<tr style="background-color:'.$color.';"><td>'.$linhaChamado['id_chamado'].'</td>';
        $response = $response.'<td>'.$linhaChamado['data_hora'].'</td>';
        $response = $response.'<td>'.$linhaChamado['origem'].'</td>';
        $response = $response.'<td>'.$nomePessoa.'</td>';
        $response = $response.'<td>'.$linhaAgente['nome'].'</td>';
        $response = $response.'<td>'.$linhaChamado['distribuicao'].'</td>';
        $response = $response.'<td>'.$linhaLogradouro['logradouro'].'</td>';
        $response = $response.'<td>'.$linhaChamado['descricao'].'</td></tr>';
    
        $i += 1;
    }

    if($response == ""){
        $response = $response.'<tr><td colspan="6" class="text-center">Nenhum Chamado</td></tr>';
    }

    //output the response
    echo $response;
?>