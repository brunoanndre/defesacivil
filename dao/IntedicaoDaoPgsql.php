<?php

require 'database.php';
require_once 'models/Interdicao.php';

class IntedicaoDaoPgsql implements InterdicaoDAO{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function adicionar(Interdicao $i){
        $sql = $this->pdo->prepare("INSERT INTO interdicao (data_hora, id_ocorrencia, motivo, descricao_interdicao, danos_aparentes, bens_afetados, tipo) 
        VALUES (:timestamp, :id_ocorrencia, :motivo, :descricao_interdicao, :danos_aparentes, :bens_afetados, :tipo)");
        $sql->bindValue(":timestamp", $i->getData());
        $sql->bindValue(":id_ocorrencia", $i->getIdOcorrencia());
        $sql->bindValue(":motivo", $i->getMotivo());
        $sql->bindValue(":descricao_interdicao", $i->getDescricao());
        $sql->bindValue(":danos_aparentes", $i->getDanos());
        $sql->bindValue(":bens_afetados", $i->getDanos());
        $sql->bindValue(":tipo", $i->getTipo());
        if($sql->execute()){
            $i->setId($this->pdo->lastInsertId());
            return $i;
        }else{
            return false;
        }
    }

    public function adicionarLog($d, $iu,$a, $i){
        $sql = $this->pdo->prepare("INSERT INTO log_interdicao (data_hora, acao,id_usuario, id_interdicao)
        VALUES (:dataAtual, :acao, :id_usuario, :id_interdicao)");
        $sql->bindValue(":dataAtual", $d);
        $sql->bindValue(":id_usuario", $iu);
        $sql->bindValue(":acao", $a);
        $sql->bindValue(":id_interdicao", $i);
        $sql->execute();
        return true;
    }

    public function remover($i){
        $sql = $this->pdo->prepare("UPDATE interdicao SET interdicao_ativa = false WHERE id_interdicao = :id_interdicao");
        $sql->bindValue(":id_interdicao", $i);
        if($sql->execute()){
        return true;
        }else{
        return false;
        }
    }

    public function buscarPeloId($id){
        $sql = $this->pdo->prepare();
    }

    public function buscarInterdicaoEOcorrencia($id){
        $sql = $this->pdo->prepare("SELECT interdicao.*,ocorrencia.id_ocorrencia,ocorrencia.ocorr_titulo,ocorrencia.ocorr_endereco_principal,
        ocorrencia.ocorr_coordenada_latitude,ocorrencia.ocorr_coordenada_longitude, ocorrencia.ocorr_logradouro_id 
        FROM interdicao 
        INNER JOIN ocorrencia ON (ocorrencia.id_ocorrencia=interdicao.id_ocorrencia) 
        WHERE id_interdicao=:id_interdicao");
        $sql->bindValue(":id_interdicao", $id);
        if($sql->execute()){
            $linha = $sql->fetch(PDO::FETCH_ASSOC);
            return $linha;
        }else{
            return false;
        }
        

        
    }
}