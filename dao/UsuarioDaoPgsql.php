<?php
require 'models/Usuario.php';
require_once 'database.php';

class UsuarioDaoPgsql implements UsuarioDAO {
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function addDadosLogin(Usuario $u){
        $sql = $this->pdo->prepare("INSERT INTO dados_login (email,senha) VALUES (:email,:hash) RETURNING id_usuario");
        $sql->bindValue(":email", $u->getEmail());
        $sql->bindValue(":hash", $u->getSenha());
        $sql->execute();

        return $u;
    }

    public function findId($email){
        $sql = $this->pdo->prepare("SELECT * FROM dados_login WHERE email = :email and ativo = true");
        $sql->bindValue(":email", $email);
        $sql->execute();

        $findId = $sql->fetch(PDO::FETCH_ASSOC)['id_usuario'];
        return $findId;
    }

    public function addUsuario(Usuario $u){
        $sql = $this->pdo->prepare("INSERT INTO usuario (id_usuario, nome, cpf, telefone, nivel_acesso,foto) 
		VALUES (:id, :nome, :cpf, :telefone, :acesso, :foto)");
        $sql->bindValue(":id", $u->getId());
        $sql->bindValue(":nome", $u->getNome());
        $sql->bindValue(":cpf",$u->getCPF());
        $sql->bindValue(":telefone", $u->getTelefone());
        $sql->bindValue(":acesso", $u->getAcesso());
        $sql->bindValue(":foto", $u->getFoto());
        $sql->execute();

        return $u;
    }

    public function alterarUsuarioAdicionado($ic, $i, $d){
        $sql = $this->pdo->prepare("INSERT INTO log_alteracao_usuario (id_usuario_modificador, id_usuario_alterado, data_hora, acao) 
        VALUES (:id_criador, :id, :data, 'cadastrar')");
        $sql->bindValue(":id_criador", $ic);
        $sql->bindValue(":id", $i);
        $sql->bindValue(":data", $d);
        $sql->execute();
    }

    public function findAll(){
        $array = [];

        $sql = $this->pdo->query("SELECT dl.id_usuario,dl.email,u.nome,u.telefone FROM dados_login dl
        INNER JOIN usuario U ON dl.id_usuario = u.id_usuario WHERE ativo = true
        ORDER BY id_usuario");
        if($sql->rowCount() > 0){
            $lista = $sql->fetchAll(PDO::FETCH_ASSOC);

            foreach($lista as $item){
                $u = new Usuario();
                $u->setId($item['id_usuario']);
                $u->setNome($item['nome']);
                $u->setEmail($item['email']);
                $u->setTelefone($item['telefone']);

                $array[] = $u;
            }
            return $array;
        }else{
            return false;
            die;
        }
    }

    public function findById($id){
        $sql = $this->pdo->prepare("SELECT * FROM usuario u
        INNER JOIN dados_login dl ON u.id_usuario = dl.id_usuario WHERE u.id_usuario = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0 ){
            $linha = $sql->fetch(PDO::FETCH_ASSOC);

            $u = new Usuario();
            $u->setNome($linha['nome']);
            $u->setCPF($linha['cpf']);
            $u->setEmail($linha['email']);
            $u->setTelefone($linha['telefone']);
            $u->setAcesso($linha['nivel_acesso']);
            $u->setFoto($linha['foto']);
            $u->setSenha($linha['senha']);
            $u->setId($linha['id_usuario']);
            
            return $u;
        }else{
            return false;
        }
    }

    public function findByEmail($email){
        $sql = $this->pdo->prepare("SELECT * FROM dados_login WHERE email = :email");
        $sql->bindValue(":email", $email);
        $sql->execute();

        if($sql->rowCount() > 0 ){
            return true;
        }else{
            return false;
        }
    }

    public function updateEmail($email, $id){ 
        $sql =$this->pdo->prepare("UPDATE dados_login SET email = :email WHERE id_usuario = :id");
        $sql->bindValue(":email",$email);
        $sql->bindValue(":id", $id);
        $sql->execute();

        return true;
    }

    public function updateComFoto(Usuario $u){
        $sql = $this->pdo->prepare("UPDATE usuario SET nome = :nome, cpf = :cpf, telefone = :telefone, foto = :foto WHERE id_usuario = :id");
        $sql->bindValue(":nome", $u->getNome());
        $sql->bindValue(":cpf", $u->getCPF());
        $sql->bindValue(":telefone", $u->getTelefone());
        $sql->bindValue(":foto", $u->getFoto());
        $sql->bindValue(":id", $u->getId());
        $sql->execute();

        return true;
    }

    public function updateSemFoto(Usuario $u){
        $sql = $this->pdo->prepare("UPDATE usuario SET nome = :nome, cpf = :cpf, telefone = :telefone WHERE id_usuario = :id");
        $sql->bindValue(":nome", $u->getNome());
        $sql->bindValue(":cpf", $u->getCPF());
        $sql->bindValue(":telefone", $u->getTelefone());
        $sql->bindValue(":id", $u->getId());
        $sql->execute();

        return true;
    }

    public function delete($id){
        $sql = $this->pdo->prepare("UPDATE dados_login SET ativo = false WHERE id_usuario = :id_usuario_alterado");
        $sql->bindValue(":id_usuario_alterado", $id);
        $sql->execute();
        
        return true;
    }

    public function alterarUsuarioExcluido($im, $ia, $d){
        $sql = $this->pdo->prepare("INSERT INTO log_alteracao_usuario (id_usuario_modificador, id_usuario_alterado, data_hora, acao)
        VALUES (:id_usuario_modificador,:id_usuario_alterado, :data, 'excluir')");
        $sql->bindValue(":id_usuario_modificador", $im);
        $sql->bindValue(":id_usuario_alterado", $ia);
        $sql->bindValue(":data", $d);
        $sql->execute();

        return true;
    }

    public function consultaUsuarioNumeroPaginas($p){
        $sql = $this->pdo->prepare("SELECT dl.id_usuario,dl.email,u.nome,u.telefone FROM dados_login dl 
        INNER JOIN usuario u ON dl.id_usuario = u.id_usuario WHERE nome ILIKE '%$p%' AND ativo = true
        ORDER BY nome");
        $sql->execute();

        return $sql->rowCount();
        }

    public function alterarSenha($h, $i){
        $sql = $this->pdo->prepare("UPDATE dados_login SET senha = :novo_hash WHERE id_usuario = :id_usuario");
        $sql->bindValue(":novo_hash", $h);
        $sql->bindValue(":id_usuario", $i);
        $sql->execute();

        return true;
    }

    public function buscarPeloNome($n){
        $sql = $this->pdo->prepare("SELECT * FROM usuario WHERE nome = :nome");
        $sql->bindValue(":nome", $n);
        $sql->execute();

        if($sql->rowCount() > 0){
            $linha = $sql->fetch(PDO::FETCH_ASSOC);

            $u = new Usuario();
            $u->setNome($linha['nome']);
            $u->setCPF($linha['cpf']);
            $u->setEmail($linha['email']);
            $u->setTelefone($linha['telefone']);
            $u->setAcesso($linha['nivel_acesso']);
            $u->setFoto($linha['foto']);
            $u->setSenha($linha['senha']);
            $u->setId($linha['id_usuario']);
            return $u;
        }else{
            return false;
        }
    }

    public function buscarUsuariosAtivos(){
        $sql = $this->pdo->prepare("SELECT * FROM usuario u INNER JOIN dados_login dl ON u.id_usuario = dl.id_usuario WHERE dl.ativo = true");
        $sql->execute();

        if($sql->rowCount() > 0){
            $lista = $sql->fetchAll(PDO::FETCH_ASSOC);
          
            foreach($lista as $item){
                $u = new Usuario;
                $u->setId($item['id_usuario']);
                $u->setNome($item['nome']);

                $array[] = $u;
            }
            return $array;
        }else{
            return false;
        }
    }

}
