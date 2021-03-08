<?php

include 'database.php';
require_once 'dao/PessoaDaoPgsql.php';

class PessoaDaoPgsql implements PessoaDAO{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function adicionar(Pessoa $p){
        $sql = $this->pdo->prepare("INSERT INTO pessoa (nome,cpf,outros_documentos,telefone,celular,email) 
        VALUES (':nome',':cpf',':outros_documentos',':telefone', ':celular',':email')");
        $sql->bindValue(":nome", $p->getNome());
        $sql->bindValue(":cpf", $p->getCPF());
        $sql->bindValue(":outros_documentos", $p->getOutrosDocumentos());
        $sql->bindValue(":telefone", $p->getTelefone());
        $sql->bindValue(":celular", $p->getCelular());
        $sql->bindValue(":email", $p->getEmail());
        $sql->execute();

        if($sql){
           $linha = $sql->fetch(PDO::FETCH_ASSOC);

            return $linha['id_pessoa'];
        }else{
            return false;
        }
    }

    public function buscarPeloID($i){
        $sql = $this->pdo->prepare("SELECT nome FROM pessoa WHERE id_pessoa = :id_pessoa");
        $sql->bindValue(":id_pessoa", $i);
        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function adicionarLogPessoa($ip, $iu, $d){
        $sql = $this->pdo->prepare("INSERT INTO log_pessoa (id_pessoa_cadastrada, id_usuario_criador, data_hora)
        VALUES (:id_pessoa, :id_usuario, ':data')");
        $sql->bindValue(":id_pessoa", $ip);
        $sql->bindValue(":id_usuario", $iu);
        $sql->bindValue(":data", $d);
        $sql->execute();
    }
}