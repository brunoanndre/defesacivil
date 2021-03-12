<?php 

class Interdicao{
    private $id;
    private $data;
    private $tipo;
    private $id_ocorrencia;
    private $motivo;
    private $descricao;
    private $danos_aparentes;
    private $bens_afetados;
    private $ativa;

    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = trim($id);
    }

    public function getData(){
        return $this->data;
    }
    public function setData($data){
        $this->data = trim($data);
    }

    public function getTipo(){
        return $this->tipo;
    }
    public function setTipo($tipo){
        $this->tipo = trim($tipo);
    }

    public function getIdOcorrencia(){
        return $this->id_ocorrencia;
    }
    public function setIdOcorrencia($id_ocorrencia){
        $this->id_ocorrencia = $id_ocorrencia;
    }

    public function getMotivo(){
        return $this->motivo;
    }
    public function setMotivo($motivo){
        $this->motivo = $motivo;
    }

    public function getDescricao(){
        return $this->descricao;
    }
    public function setDescricao($descricao){
        $this->descricao = $descricao;
    }

    public function getDanos(){
        return $this->danos_aparentes;
    }
    public function setDanos($danos_aparentes){
        $this->danos_aparentes = $danos_aparentes;
    }

    public function getBensAfetados(){
        return $this->bens_afetados;
    }
    public function setBensAfetados($bens_afetados){
        $this->bens_afetados = $bens_afetados;
    }

    public function getAtiva(){
        return $this->ativa;
    }
    public function setAtiva($ativa){
        $this->ativa = $ativa;
    }
    }

    interface InterdicaoDAO{
        public function adicionar(Interdicao $i);
        public function adicionarLog($d,$iu, $a, $i);
        public function remover($i);
        public function buscarPeloId($id);
        public function buscarInterdicaoEOcorrencia($id);
    }