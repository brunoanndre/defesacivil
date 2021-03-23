<?php


class Chamado{
    private $id;
    private $data;
    private $origem;
    private $descricao;
    private $endereco_principal;
    private $logradouro_id;
    private $latitude;
    private $longitude;
    private $pessoa_id;
    private $usado;
    private $agente_id;
    private $nome_agente;
    private $prioridade;
    private $distribuicao;
    private $nome_pessoa;
    private $cancelado;
    private $motivo;
    private $fotos;

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
        $this->data = $data;
    }

    public function getOrigem(){
        return $this->origem;
    }
    public function setOrigem($origem){
        $this->origem = trim($origem);
    }

    public function getDescricao(){
        return $this->descricao;
    }
    public function setDescricao($descricao){
        $this->descricao = $descricao;
    }

    public function getEnderecoPrincipal(){
        return $this->endereco_principal;
    }
    public function setEnderecoPrincipal($endereco_principal){
        $this->endereco_principal = $endereco_principal;
    }

    public function getLogradouroId(){
        return $this->logradouro_id;
    }
    public function setLogradouroId($logradouro_id){
        $this->logradouro_id = trim($logradouro_id);
    }

    public function getLatitude(){
        return $this->latitude;
    }
    public function setLatitude($latitude){
        $this->latitude = trim($latitude);
    }

    public function getLongitude(){
        return $this->longitude;
    }
    public function setLongitude($longitude){
        $this->longitude = trim($longitude);
    }

    public function getPessoaId(){
        return $this->pessoa_id;
    }
    public function setPessoaId($pessoa_id){
        $this->pessoa_id = trim($pessoa_id);
    }

    public function getUsado(){
        return $this->usado;
    }
    public function setUsado($usado){
        $this->usado = $usado;
    }

    public function getAgenteId(){
        return $this->agente_id;
    }
    public function setAgenteId($agente_id){
        $this->agente_id = $agente_id;
    }

    public function getNomeAgente(){
        return $this->nome_agente;
    }
    public function setNomeAgente($agente_nome){
        $this->nome_agente = $agente_nome;    
    }

    public function getPrioridade(){
        return $this->prioridade;
    }
    public function setPrioridade($prioridade){
        $this->prioridade = $prioridade;
    }
    
    public function getDistribuicao(){
        return $this->distribuicao;
    }
    public function setDistribuicao($distribuicao){
        $this->distribuicao = $distribuicao;
    }

    public function getNomePessoa(){
        return $this->nome_pessoa;
    }
    public function setNomePessoa($nome_pessoa){
        $this->nome_pessoa = $nome_pessoa;
    }

    public function getCancelado(){
        return $this->cancelado;
    }
    public function setCancelado($cancelado){
        $this->cancelado = $cancelado;
    }

    public function getMotivo(){
        return $this->motivo;
    }
    public function setMotivo($motivo){
        $this->motivo = $motivo;
    }

    public function getFotos(){
        return $this->fotos;
    }
    public function setFotos($fotos){
        $this->fotos = $fotos;
    }
}

interface ChamadoDAO{
    public function buscarConsulta($p);
    public function buscarPeloId($id);
    public function adicionar(Chamado $c);
    public function adicionarLog($id_usuario,$id_chamado, $dataAtual);
}