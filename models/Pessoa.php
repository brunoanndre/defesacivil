<?php


class Pessoa{
    private $id;
    private $nome;
    private $cpf;
    private $outros_documentos;
    private $telefone;
    private $celular;
    private $email;

    public function getID(){
        return $this->id;
    }
    public function setID($i){
        $this->id = trim($i);
    }

    public function getNome(){
        return $this->nome;
    }
    public function setNome($n){
        $this->nome = ucwords(trim($n));
    }

    public function getCPF(){
        return $this->cpf;
    }
    public function setCPF($cpf){
        $this->cpf = trim($cpf);
    }

    public function getOutrosDocumentos(){
        return $this->outros_documentos;
    }
    public function setOutrosDocumentos($o){
        $this->outros_documentos = trim($o);
    }

    public function getTelefone(){
        return $this->telefone;
    }
    public function setTelefone($t){
        $this->telefone = $t;
    }

    public function getCelular(){
        return $this->celular;
    }
    public function setCelular($c){
        $this->celular = trim($c);
    }

    public function getEmail(){
        return $this->email;
    }
    public function setEmail($e){
        $this->email = strtolower(trim($e));
    }
}

interface PessoaDAO{
    public function adicionar(Pessoa $p);
    public function buscarPeloID($i);
    public function adicionarLogPessoa($ip,$iu,$d);
}