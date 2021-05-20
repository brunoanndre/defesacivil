<?php

    require_once 'dao/NotificacaoDaoPgsql.php';
    require_once 'dao/EnderecoDaoPgsql.php';
    
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

    $linhaEndereco = $enderecodao->buscarPeloId($id_endereco);
    echo '<pre>';
    var_dump($linhaEndereco);
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

           $id_endereco =  $enderecodao->adicionar($e);
        }
    }else{
        $erros = 'campos';
    }

    if(strlen($erros) > 0 ){
        header('Location:index.php?pagina=cadastrarNotificacao&id');
    }else{
        $n = New Notificacao;
        $n->setIdEndereco($id_endereco);
        $n->setIdOcorrencia($id_ocorrencia);
        $n->setDescricao($descricao);
        $n->setDataEmissao($data_emissao);

        $id_notificacao = $notificacaodao->adicionar($n);
        
        if($id_notificacao){
            header('Location:index.php?pagina=exibirNotificacao&id=' . $id_notificacao);
        }
    }