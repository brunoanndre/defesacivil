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
            
            return $linha['id_logradouro'];
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
            $e->setComplemento($linha['complemento']);

            return $e;
        }else{
            return false;
        }
    }

    public function adicionar(Endereco $e){
        $sql = $this->pdo->prepare("INSERT INTO endereco_logradouro (cep,cidade,bairro,logradouro,numero,referencia,complemento) 
		VALUES (:cep, :cidade, :bairro, :logradouro, :numero, :referencia, :complemento)");
        $sql->bindValue(":cep", $e->getCep());
        $sql->bindValue(":cidade", $e->getCidade());
        $sql->bindValue(":bairro", $e->getBairro());
        $sql->bindValue(":logradouro", $e->getLogradouro());
        $sql->bindValue(":numero", $e->getNumero());
        $sql->bindValue(":referencia", $e->getReferencia());
        $sql->bindValue(":complemento", $e->getComplemento());

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

    public function buscarCoordenada($latitude, $longitude){
        $sql = $this->pdo->prepare("SELECT * FROM endereco_coordenada WHERE latitude = :latitude AND longitude = :longitude");
        $sql->bindValue(":latitude", $latitude);
        $sql->bindValue(":longitude", $longitude);
        $sql->execute();

        if($sql->rowCount() > 0){
            $linha = $sql->fetch(PDO::FETCH_ASSOC);
            return $linha;
        }else{
            return false;
        }
    }

    public function adicionarCoordenada(Endereco $e){
        $sql = $this->pdo->prepare("INSERT INTO endereco_coordenada (latitude, longitude) VALUES
         (:latitude, :longitude)");
         $sql->bindValue(":latitude", $e->getLatitude());
         $sql->bindValue(":longitude", $e->getLongitude());
         
         if($sql->execute()){
             $id = $this->pdo->lastInsertId();
             return $id;
         }else{
             return false;
         }
    }

    public function buscarIdCoordenada($id){
        $sql = $this->pdo->prepare("SELECT * FROM endereco_coordenada WHERE id_coordenada = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $linha = $sql->fetch(PDO::FETCH_ASSOC);
            $e = New Endereco();
            $e->setLatitude($linha['latitude']);
            $e->setLongitude($linha['longitude']);

            return $e;
        }else{
            return false;
        }
    }

    public function editarLogradouro(Endereco $e){
        $sql = $this->pdo->prepare("UPDATE endereco_logradouro SET cep = :cep, cidade = :cidade, bairro = :bairro, logradouro = :logradouro, numero = :numero, referencia = :referencia, complemento = :complemento
        WHERE id_logradouro = :id_logradouro");
        $sql->bindValue(":cep", $e->getCep());
        $sql->bindValue(":cidade", $e->getCidade());
        $sql->bindValue(":bairro", $e->getBairro());
        $sql->bindValue(":logradouro", $e->getLogradouro());
        $sql->bindValue(":numero", $e->getNumero());
        $sql->bindValue(":referencia", $e->getReferencia());
        $sql->bindValue(":id_logradouro", $e->getId());
        $sql->bindValue(":complemento", $e->getComplemento());

        
            if($sql->execute()){
                return true;
            }else{
                return false;
            }
    }

    public function editarCoordenada(Endereco $e){
        $sql = $this->pdo->prepare("UPDATE endereco_coordenada SET latitude = :latitude, longitude = :longitude
        WHERE id_coordenada = :idCoordenada");
        $sql->bindValue(":latitude", $e->getLatitude());
        $sql->bindValue(":longitude", $e->getLongitude());
        $sql->bindValue(":idCoordenada", $e->getId());
  
        try{
            if($sql->execute()){
                return true;
            }else{
                return false;
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }
}