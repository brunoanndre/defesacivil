<?php 

    require_once 'models/Notificacao.php';

    class NotificacaoDaoPgsql implements NotificacaoDAO{
        private $pdo;
        
        public function __construct(PDO $driver){
            $this->pdo = $driver;
        }

        public function adicionar(Notificacao $n){
            $sql = $this->pdo->prepare("INSERT INTO notificacao (id_endereco, id_ocorrencia, descricao, data_emissao) VALUES
            (:id_endereco, :id_ocorrencia, :descricao, :data_emissao)");
            $sql->bindValue(":id_endereco", $n->getIdEndereco());
            $sql->bindValue(":id_ocorrencia", $n->getIdOcorrencia());
            $sql->bindValue(":descricao", $n->getDescricao());
            $sql->bindValue(":data_emissao", $n->getDataEmissao());

            if($sql->execute()){
                $id = $this->pdo->lastInsertId();
                return $id;
            }else{
                return false;
            }
        }

        public function buscarIdNotificacao($id_ocorrencia){
            $sql = $this->pdo->prepare("SELECT id_notificacao FROM notificacao WHERE id_ocorrencia = :id_ocorrencia");
            $sql->bindValue(":id_ocorrencia", $id_ocorrencia);
            
            if( $sql->execute()){
                $linha = $sql->fetch();
                $id = $linha['id_notificacao'];
                return $id;
            }else{
                return false;
            }
            
        }

        public function editar(Notificacao $n){
            
        }
    }