<?php


class Ocorrencia{
    private $chamado_id;
    private $id;
    private $endereco_principal;
    private $latitude;
    private $longitude;
    private $logradouro_id;
    private $cep;
    private $cidade;
    private $bairro;
    private $logradouro;
    private $numero;
    private $referencia;
    private $id_criador;
    private $nome_agente_principal;
    private $agente_apoio1;
    private $agente_apoio2;
    private $data_ocorrencia;
    private $dataAtual;
    private $titulo;
    private $descricao;
    private $origem;
    private $id_pessoa1;
    private $id_pessoa2;
    private $nome_pessoa1;
    private $nome_pessoa2;
    private $cobrade;
    private $prioridade;
    private $analisado;
    private $congelado;
    private $encerrado;
    private $possui_fotos;
    private $fotos;
    private $ativo;
    private $usuario_editor;

    public function getChamadoId(){
        return $this->chamado_id;
    }
    public function setChamadoId($ch){
        $this->chamado_id = trim($ch);
    }

    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = trim($id);
    }

    public function getNomeAgentePrincipal(){
        return $this->nome_agente_principal;
    }
    public function setNomeAgentePrincipal($nome){
        $this->nome_agente_principal = $nome;
    }

    public function getEnderecoPrincipal(){
        return $this->endereco_principal;
    }
    public function setEnderecoPrincipal($e){
        $this->endereco_principal = trim($e);
    }

    public function getLatitude(){
        return $this->latitude;
    }
    public function setLatitude($la){
        $this->latitude = $la;
    }

    public function getLongitude(){
        return $this->longitude;
    }
    public function setLongitude($lo){
        $this->longitude = $lo;
    }
    
    public function getLogradouroid(){
        return $this->logradouro_id;
    }
    public function setLogradouroId($li){
        $this->logradouro_id = trim($li);
    }

    public function getCep(){
        return $this->cep;
    }
    public function setCep($c){
        $this->cep = $c;
    }

    public function getCidade(){
        return $this->cidade;
    }
    public function setCidade($c){
        $this->cidade = trim($c);
    }

    public function getBairro(){
        return $this->bairro;
    }
    public function setBairro($b){
        $this->bairro = ucwords(trim($b));
    }

    public function getLogradouro(){
        return $this->logradouro;
    }
    public function setLogradouro($l){
        $this->logradouro = ucwords(trim($l));
    }

    public function getNumero(){
        return $this->numero;
    }
    public function setNumero($n){
        $this->numero = trim($n);
    }

    public function getReferencia(){
        return $this->referencia;
    }
    public function setReferencia($r){
        $this->referencia = trim($r);
    }

    public function getIdCriador(){
        return $this->id_criador;
    }
    public function setIdCriador($ic){
        $this->id_criador = trim($ic);
    }

    public function getApoio1(){
        return $this->agente_apoio1;
    }
    public function setApoio1($a1){
        $this->agente_apoio1 = trim($a1);
    }

    public function getApoio2(){
        return $this->agente_apoio2;
    }
    public function setApoio2($a2){
        $this->agente_apoio2 = trim($a2);
    }

    public function getCobrade(){
        return $this->cobrade;
    }
    public function setCobrade($c){
        $this->cobrade = trim($c);
    }

    public function getData(){
        return $this->data_ocorrencia;
    }
    public function setData($d){
        $this->data_ocorrencia = trim($d);
    }

    public function getDataAlteracao(){
        return $this->dataAtual;
    }
    public function setDataAlteracao($da){
        $this->dataAtual = trim($da);
    }

    public function getTitulo(){
        return $this->titulo;
    }
    public function setTitulo($t){
        $this->titulo = trim($t);
    }

    public function getDescricao(){
        return $this->descricao;
    }
    public function setDescricao($d){
        $this->descricao = trim($d);
    }

    public function getOrigem(){
        return $this->origem;
    }
    public function setOrigem($o){
        $this->origem = trim($o);
    }

    public function getIdPessoa1(){
        return $this->id_pessoa1;
    }
    public function setIdPessoa1($ip){
        $this->id_pessoa1 = trim($ip);
    }

    public function getIdPessoa2(){
        return $this->id_pessoa2;
    }
    public function setIdPessoa2($ip){
        $this->id_pessoa2 = trim($ip);
    }

    public function getPessoa1(){
        return $this->nome_pessoa1;
    }
    public function setPessoa1($p1){
        $this->nome_pessoa1 = ucwords(trim($p1));
    }

    public function getPessoa2(){
        return $this->nome_pessoa2;
    }
    public function setPessoa2($p2){
        $this->nome_pessoa2 = ucwords(trim($p2));
    }

    public function getPrioridade(){
        return $this->prioridade;
    }
    public function setPrioridade($p){
        $this->prioridade = trim($p);
    }

    public function getAnalisado(){
        return $this->analisado;
    }
    public function setAnalisado($a){
        $this->analisado = $a;
    }

    public function getCongelado(){
        return $this->congelado;
    }
    public function setCongelado($c){
        $this->congelado = $c;
    }

    public function getEncerrado(){
        return $this->encerrado;
    }
    public function setEncerrado($e){
        $this->encerrado = $e;
    }

    public function getPossuiFotos(){
        return $this->possui_fotos;
    }
    public function setPossuiFotos($pf){
        $this->possui_fotos = $pf;
    }

    public function getFotos(){
        return $this->fotos;
    }
    public function setFotos($f){
        $this->fotos = trim($f);
    }

    public function getAtivo(){
        return $this->ativo;
    }
    public function setAtivo($a){
        $this->ativo = $a;
    }
    
    public function getUsuarioEditor(){
        return $this->usuario_editor;
    }
    public function setUsuarioEditor($u){
        $this->usuario_editor = $u;
    }
}

interface OcorrenciaDAO{
    public function buscarPeloId($id);
    public function adicionar(Ocorrencia $o);
    public function remover($id);
    public function editarOcorrencia(Ocorrencia $o);
    public function editarEndereco(Ocorrencia $o);
    public function buscaFotos($i);
    public function buscaCobrade($c);
    public function buscaInterdicao($i);
    public function buscarConsulta($parametro);
    public function encerraChamadoAtivo($id);
    public function buscaOcorrenciaUsuarioEndereco($i);
}