<?php

require 'database.php';
require_once 'models/Endereco.php';

class EnderecoDaoPgsql implements EnderecoDAO{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function buscarEndereco($logradouro, $numero){
        $sql = $this->pdo->prepare("SELECT * FROM endereco_logradouro WHERE logradouro = :logradouro AND numero = :numero");
        $sql->bindValue(":logradouro", $logradouro);
        $sql->bindValue(":numero", $numero);
        $sql->execute();

        $sql->fetchAll();
        if($sql->rowCount() == 0 ){
            return false;
        }else{
            $linha = $sql->fetch(PDO::FETCH_ASSOC);
            
            return $linha;
        }
    }

    public function buscarPeloId($id){
        $sql = $this->pdo->prepare("SELECT * FROM endereco_logradouro WHERE id_logradouro = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $linha = $sql->fetch(PDO::FETCH_ASSOC);

            $e = new Endereco;
            $e->setId($linha['id_logradouro']);
            $e->setCep($linha['cep']);
            $e->setCidade($linha['cidade']);
            $e->setBairro($linha['bairro']);
            $e->setLogradouro($linha['logradouro']);
            $e->setNumero($linha['numero']);
            $e->setReferencia($linha['referencia']);

            return $e;
        }else{
            return false;
        }
    }

    public function adicionar(Endereco $e){
        $sql = $this->pdo->prepare("INSERT INTO endereco_logradouro (cep,cidade,bairro,logradouro,numero,referencia) 
		VALUES (:cep, :cidade, :bairro, :logradouro, :numero, :referencia)");
        $sql->bindValue(":cep", $e->getCep());
        $sql->bindValue(":cidade", $e->getCidade());
        $sql->bindValue(":bairro", $e->getBairro());
        $sql->bindValue(":logradouro", $e->getLogradouro());
        $sql->bindValue(":numero", $e->getNumero());
        $sql->bindValue(":referencia", $e->getReferencia());

        if($sql->execute()){
            $id = $this->pdo->lastInsertId();
            return $id;
        }else{
            return false;
        }
    }

    public function adicionarLog($logradouro_id, $id_usuario, $dataAtual){
        $sql = $this->pdo->prepare("INSERT INTO log_endereco (id_logradouro, id_usuario, data_hora)
        VALUES (:logradouro_id, :id_usuario, :dataAtual)");
        $sql->bindValue(":logradouro_id", $logradouro_id);
        $sql->bindValue(":id_usuario", $id_usuario);
        $sql->bindValue(":dataAtual", $dataAtual);
        $sql->execute();
    }
}