<?php
    include 'database.php';
    require_once 'dao/PessoaDaoPgsql.php';

    $pessoadao = new PessoaDaoPgsql($pdo);

    $nome = addslashes($_GET['nome_pessoa']);
    $cpf = addslashes($_GET['cpf_pessoa']);
    $outros_documentos = addslashes($_GET['outros_documentos']);
    $celular = addslashes($_GET['celular_pessoa']);
    $telefone = addslashes($_GET['telefone_pessoa']);
    $email = addslashes($_GET['email_pessoa']);

    if($nome != null){
        $response = 'Pessoa cadastrado com sucesso';
        if(strlen($erros) == 0){
            $novapessoa = new Pessoa();
            $novapessoa->setNome($nome);
            $novapessoa->setCPF($cpf);
            $novapessoa->setOutrosDocumentos($outros_documentos);
            $novapessoa->setTelefone($telefone);
            $novapessoa->setCelular($celular);
            $novapessoa->setEmail($email);

            $id_pessoa = $pessoadao->adicionar($novapessoa);

            if($id_pessoa == null){
                $response = 'Ocorreu um erro com o banco de dados';//'Erro ao cadastrar pessoa';
            }else{
                session_start();
                $id_usuario = $_SESSION['id_usuario'];
                $data = date('Y-m-d H:i:s');

                $pessoadao->adicionarLogPessoa($id_pessoa,$id_usuario,$data);
            }
        }else
            $response = $erros;//'Erro ao cadastrar pessoa';
    }else
        $response = 'Pessoa deve possuir pelo menos um nome';//'Erro ao cadastrar pessoa';

    echo $response;