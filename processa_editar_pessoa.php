<?php

    require_once 'dao/PessoaDaoPgsql.php';

    $pessoadao = New PessoaDaoPgsql($pdo);

    $nome = filter_input(INPUT_GET, 'nome_pessoa');
    $email = filter_input(INPUT_GET, 'email_pessoa');
    $celular = filter_input(INPUT_GET, 'celular_pessoa');
    $telefone = filter_input(INPUT_GET, 'telefone_pessoa');
    $cpf = filter_input(INPUT_GET, 'cpf_pessoa');
    $outrosDocumentos = filter_input(INPUT_GET, 'outros_documentos');
    $id = filter_input(INPUT_GET, 'id_pessoa');

    if($nome){
        $p = New Pessoa();
        $p->setNome($nome);
        $p->setEmail($email);
        $p->setCelular($celular);
        $p->setTelefone($telefone);
        $p->setCPF($cpf);
        $p->setOutrosDocumentos($outrosDocumentos);
        $p->setID($id);


        if($pessoadao->editar($p)){
            $response = 'Sucesso';
        }else{
            $response = 'Falha';
        }
    }else{
        $response = 'Informe o nome da pessoa';
    }

    echo $response;


