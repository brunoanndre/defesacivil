<?php

class Endereco{
    private $id;
    private $cep;
    private $cidade;
    private $bairro;
    private $logradouro;
    private $numero;
    private $longitude;
    private $latitude;
    private $referencia;
    private $complemento;

    public function setComplemento($complemento){
        $this->complemento = trim($complemento);
    }
    public function getComplemento(){
        return $this->complemento;
    }

    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = trim($id);
    }

    public function getCep(){
        return $this->cep;
    }
    public function setCep($cep){
        $this->cep = trim($cep);
    }

    public function getCidade(){
        return $this->cidade;
    }
    public function setCidade($cidade){
        $this->cidade = ucwords($cidade);
    }

    public function getBairro(){
        return $this->bairro;
    }
    public function setBairro($bairro){
        $this->bairro = ucwords($bairro);
    }

    public function getLogradouro(){
        return $this->logradouro;
    }
    public function setLogradouro($logradouro){
        $logradouro = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($logradouro)));
        $this->logradouro = strtoupper($logradouro);
    }

    public function getNumero(){
        return $this->numero;
    }
    public function setNumero($numero){
        $this->numero = $numero;
    }

    public function getReferencia(){
        return $this->referencia;
    }
    public function setReferencia($referencia){
        $this->referencia = strtoupper($referencia);
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
}

interface EnderecoDAO{
    public function buscarEndereco($logradouro,$numero);
    public function buscarPeloId($id);
    public function adicionar(Endereco $e);
    public function buscarCoordenada($latitude,$longitude);
    public function editarLogradouro(Endereco $e);
    public function editarCoordenada(Endereco $e);
    public function buscarIdCoordenada($id);
    public function adicionarCoordenada(Endereco $e);
    public function adicionarLog($logradouro_id, $id_usuario, $dataAtual);
}