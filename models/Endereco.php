<?php

class Endereco{
    private $id;
    private $cep;
    private $cidade;
    private $bairro;
    private $logradouro;
    private $numero;
    private $referencia;

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
        $this->cidade = $cidade;
    }

    public function getBairro(){
        return $this->bairro;
    }
    public function setBairro($bairro){
        $this->bairro = $bairro;
    }

    public function getLogradouro(){
        return $this->logradouro;
    }
    public function setLogradouro($logradouro){
        $this->logradouro = $logradouro;
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
        $this->referencia = $referencia;
    }
}

interface EnderecoDAO{
    public function buscarEndereco($logradouro,$numero);
    public function buscarPeloId($id);
    public function adicionar(Endereco $e);
    public function adicionarLog($logradouro_id, $id_usuario, $dataAtual);
}