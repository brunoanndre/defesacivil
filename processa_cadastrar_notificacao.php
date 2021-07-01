<?php

    require_once 'dao/NotificacaoDaoPgsql.php';
    require_once 'dao/EnderecoDaoPgsql.php';
    require_once 'dao/UsuarioDaoPgsql.php';

    $usuariodao = New UsuarioDaoPgsql($pdo);
    $notificacaodao = New NotificacaoDaoPgsql($pdo);
    $enderecodao = New EnderecoDaoPgsql($pdo);
    $cidade = filter_input(INPUT_POST, 'cidade');
    $bairro = filter_input(INPUT_POST, 'bairro');
    $logradouro = filter_input(INPUT_POST, 'logradouro');
    $numero = filter_input(INPUT_POST, 'numero');
    $referencia = filter_input(INPUT_POST, 'referencia');
    $id_ocorrencia = filter_input(INPUT_POST, 'id_ocorrencia');
    $descricao = filter_input(INPUT_POST, 'descricao');
    $id_endereco = filter_input(INPUT_POST, 'id_endereco');
    $data_emissao = filter_input(INPUT_POST, 'data_emissao');
    $notificado = filter_input(INPUT_POST, 'notificado');
    $notificante = filter_input(INPUT_POST, "notificante");
    $dataVencimento = filter_input(INPUT_POST, 'data_vencimento');


    $linhaEndereco = $enderecodao->buscarPeloId($id_endereco);

    if($cidade && $bairro && $logradouro && $numero && $descricao){
        if($linhaEndereco->getCidade() == $cidade && $linhaEndereco->getBairro() == $bairro && $linhaEndereco->getLogradouro() == $logradouro && $linhaEndereco->getNumero() == $numero){
            $novoEndereco = false;
        }else{
            $novoEndereco = true;
        }
        if($data_emissao == ''){
            $erros = '&data';
        }
        if($novoEndereco == true){
            $e = New Endereco;
            $e->setCidade($cidade);
            $e->setBairro($bairro);
            $e->setLogradouro($logradouro);
            $e->setNumero($numero);
            $e->setReferencia($referencia);

           $id_endereco =  $enderecodao->adicionar($e);
        }
    }else{
        $erros = '&campos';
    }


    if(strlen($erros) > 0 ){
        header('Location:index.php?pagina=cadastrarNotificacao&id='. $id_ocorrencia .$erros);
    }else{
        $n = New Notificacao;
        $n->setIdEndereco($id_endereco);
        $n->setIdOcorrencia($id_ocorrencia);
        $n->setDescricao($descricao);
        $n->setDataEmissao($data_emissao);
        $n->setNotificado($notificado);
        $n->setRepresentante($notificante);
        $n->setDataVencimento($dataVencimento);


        $id_notificacao = $notificacaodao->adicionar($n);
        
        if($id_notificacao){
            header('Location:index.php?pagina=exibirNotificacao&id=' . $id_notificacao.'&sucesso');
        }else{
            header('Location:index.php?pagina=cadastrarNotificacao&id='. $id_ocorrencia . '&erroDB');
        }
    }