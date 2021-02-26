<?php

class Usuario{
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $foto;
    private $telefone;
    private $nivel_acesso;

    public function getId(){
        return $this->id;
    }

    public function setId($i){
        $this->id =trim($i);
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($n){
        $this->nome = ucwords(trim($n));
    }

    public function setSenha($s){
        $this->senha = $s;
    }

    public function getSenha(){
        return $this->senha;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($e){
        $this->email = strtolower(trim($e));
    }

    public function getTelefone(){
        return $this->telefone;
    }

    public function getAcesso(){
        return $this->nivel_acesso;
    }

    public function setAcesso($a){
        $this->nivel_acesso = $a;
    }

    public function setTelefone($tel){
        $this->telefone = $tel;
    }

    public function getCPF(){
        return $this->cpf;
    }


    public function setCPF($c){
        $this->cpf = $c;
    }

    public function getFoto(){
        return $this->foto;
    }

    public function setFoto($f){
        $this->foto = $f;
    }
}

interface UsuarioDAO{
    public function addUsuario(Usuario $u);
    public function addDadosLogin(Usuario $u);
    public function findAll($p,$i,$o);
    public function findId($email);
    public function alterarUsuarioAdicionado($ic,$i,$d);
    public function alterarUsuarioExcluido($im,$ia,$d);
    public function alterarSenha($h,$i);
    public function consultaUsuarioNumeroPaginas($p);
    public function findById($id);
    public function findByEmail($email);
    public function updateEmail($email, $id);
    public function updateComFoto(Usuario $u);
    public function updateSemFoto(Usuario $u);
    public function delete($id);
}