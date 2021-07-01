<?php
    require_once 'database.php';


class Notificacao{
    private $id;
    private $dataEmissao;
    private $idEndereco;
    private $idOcorrencia;
    private $representante;
    private $notificado;
    private $descricao;
    private $dataVencimento;
    private $documentoAssinado;

    public function setDocumentoAssinado($documentoAssinado){
        $this->documentoAssinado = trim($documentoAssinado);
    }
    public function getDocumentoAssinado(){
        return $this->documentoAssinado;
    }

    public function setDataVencimento($dataVencimento){
        $this->dataVencimento = trim($dataVencimento);
    }
    public function getDataVencimento(){
        return $this->dataVencimento;
    }

    public function getIdEndereco(){
        return $this->idEndereco;
    }
    public function setIdEndereco($idEndereco){
        $this->idEndereco = trim($idEndereco);
    }

    public function getIdOcorrencia(){
        return $this->idOcorrencia;
    }
    public function setIdOcorrencia($idOcorrencia){
        $this->idOcorrencia = trim($idOcorrencia);
    }

    public function getRepresentante(){
        return $this->representante;
    }
    public function setRepresentante($representante){
        $this->representante = trim($representante);
    }

    public function getNotificado(){
        return $this->notificado;
    }
    public function setNotificado($notificado){
        $this->notificado = $notificado;
    }

    public function getDescricao(){
        return $this->descricao;
    }
    public function setDescricao($descricao){
        $this->descricao = $descricao;
    }
    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }

    public function getDataEmissao(){
        return $this->dataEmissao;
    }
    public function setDataEmissao($dataEmissao){
        $this->dataEmissao = $dataEmissao;
    }
}

interface NotificacaoDAO{
    public function adicionar(Notificacao $n);
    public function editar(Notificacao $n);
    public function buscarIdNotificacao($id_ocorrencia);
    public function buscarPeloId($id);
    public function buscarConsulta();
}