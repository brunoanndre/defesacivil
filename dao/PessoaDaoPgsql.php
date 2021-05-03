<?php

require 'database.php';
require 'models/Pessoa.php';

class PessoaDaoPgsql implements PessoaDAO{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function adicionar(Pessoa $p){
        $sql = $this->pdo->prepare("INSERT INTO pessoa (nome,cpf,outros_documentos,telefone,celular,email) 
        VALUES (:nome,:cpf,:outros_documentos,:telefone, :celular,:email)");

        $sql->bindValue(":nome", $p->getNome());
        $sql->bindValue(":cpf", $p->getCPF());
        $sql->bindValue(":outros_documentos", $p->getOutrosDocumentos());
        $sql->bindValue(":telefone", $p->getTelefone());
        $sql->bindValue(":celular", $p->getCelular());
        $sql->bindValue(":email", $p->getEmail());
        $sql->execute();

        if($sql){
           
           $id = $this->pdo->lastInsertId();

            return $id;
        }else{
            return false;
        }
    }

    public function editar(Pessoa $p){
        $sql = $this->pdo->prepare('UPDATE pessoa SET nome = :nome, cpf = :cpf, outros_documentos = :outros, celular = :celular, email = :email, telefone = :telefone
        WHERE id_pessoa = :id');
        $sql->bindValue(':nome', $p->getNome());
        $sql->bindValue(':cpf', $p->getCPF());
        $sql->bindValue(":outros", $p->getOutrosDocumentos());
        $sql->bindValue(":celular", $p->getCelular());
        $sql->bindValue(':email', $p->getEmail());
        $sql->bindValue(':telefone', $p->getTelefone());
        $sql->bindValue(':id', $p->getID());

        if($sql->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function buscarPeloID($i){
        $sql = $this->pdo->prepare("SELECT * FROM pessoa WHERE id_pessoa = :id_pessoa");
        $sql->bindValue(":id_pessoa", $i);
        $sql->execute();

        if($sql->execute()){
            $linha = $sql->fetch(PDO::FETCH_ASSOC);
            $p = new Pessoa;
            $p->setNome($linha['nome']);
            $p->setCPF($linha['cpf']);
            $p->setOutrosDocumentos($linha['outros_documentos']);
            $p->setCelular($linha['celular']);
            $p->setEmail($linha['email']);
            $p->setTelefone($linha['telefone']);

            return $p;
        }else{
            return false;
        }
    }

    public function buscarPeloNome($n){
        $sql = $this->pdo->prepare("SELECT * FROM pessoa WHERE nome = :pessoa_atendida_1");
        $sql->bindValue(":pessoa_atendida_1", $n);
        $sql->execute();

        if($sql->rowCount() > 0){
            $linha = $sql->fetch(PDO::FETCH_ASSOC);
            return $linha['id_pessoa'];
        }else{
            return false;
        }
    }

    public function adicionarLogPessoa($ip, $iu, $d){
        $sql = $this->pdo->prepare("INSERT INTO log_pessoa (id_pessoa_cadastrada, id_usuario_criador, data_hora)
        VALUES (:id_pessoa, :id_usuario, :data)");
        $sql->bindValue(":id_pessoa", $ip);
        $sql->bindValue(":id_usuario", $iu);
        $sql->bindValue(":data", $d);
        $sql->execute();
    }
}