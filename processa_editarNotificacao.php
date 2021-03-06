<?php

    require_once 'dao/NotificacaoDaoPgsql.php';
    require_once 'dao/EnderecoDaoPgsql.php';
    require_once 'dao/UsuarioDaoPgsql.php';

    $notificacaodao = New NotificacaoDaoPgsql($pdo);
    $enderecodao = New EnderecoDaoPgsql($pdo);
    $usuariodao = New UsuarioDaoPgsql($pdo);

    $cidade = filter_input(INPUT_POST, 'cidade');
    $bairro = filter_input(INPUT_POST,'bairro');
    $logradouro = filter_input(INPUT_POST, 'logradouro');
    $numero = filter_input(INPUT_POST, 'numero');
    $referencia = filter_input(INPUT_POST, 'referencia');
    $id_notificacao = filter_input(INPUT_POST, 'id_notificacao');
    $id_endereco = filter_input(INPUT_POST, 'id_endereco');
    $data_emissao = filter_input(INPUT_POST, 'data_emissao');
    $representante = filter_input(INPUT_POST, 'representante');
    $notificado = filter_input(INPUT_POST, 'notificado');
    $descricao = filter_input(INPUT_POST, 'descricao');
    $data_vencimento = filter_input(INPUT_POST, 'data_vencimento');
    $complemento = filter_input(INPUT_POST, 'complemento');
    
    $base64_array = array();

    foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
        $temp = $_FILES["files"]["tmp_name"][$key];

        if (empty($temp))
            break;

        $binary = file_get_contents($temp);
        $base64 = base64_encode($binary);
        array_push($base64_array, $base64);
    }

    $pg_array = '{' . join(',', $base64_array) . '}';

    
    $linhaEndereco = $enderecodao->buscarPeloId($id_endereco);

    $id_representante = $usuariodao->buscarPeloNome($representante)->getId();

    if($cidade && $bairro && $logradouro && $numero && $descricao){
        if($linhaEndereco->getCidade() == $cidade && $linhaEndereco->getBairro() == $bairro && $linhaEndereco->getLogradouro() == $logradouro && $linhaEndereco->getNumero() == $numero){
            $novoEndereco = false;
        }else{
            $novoEndereco = true;
        }

        if($novoEndereco == true){
            $e = New Endereco;
            $e->setCidade($cidade);
            $e->setBairro($bairro);
            $e->setLogradouro($logradouro);
            $e->setNumero($numero);
            $e->setReferencia($referencia);
            $e->setComplemento($complemento);

           $id_endereco =  $enderecodao->adicionar($e);
           

        }
    }else{
        $erros = '&campos';
    }


    if(strlen($erros) > 0){
        header('Location:index.php?pagina=exibirNotificacao&id='.$id_notificacao.$erros);
    }else{
        $n = New Notificacao;
        $n->setId($id_notificacao);   
        $n->setIdEndereco($id_endereco);
        $n->setDescricao($descricao);
        $n->setDataEmissao($data_emissao); 
        $n->setRepresentante($id_representante);
        $n->setNotificado($notificado);
        $n->setDataVencimento($data_vencimento);
        $n->setDocumentoAssinado($pg_array);

        if($notificacaodao->editar($n)){
            header('Location:index.php?pagina=exibirNotificacao&id='. $id_notificacao . '&sucessoEdit');
        }else{
            header('Location:index.php?pagina=exibirNotificacao&id=' . $id_notificacao . '&erroDB');
        }

    }