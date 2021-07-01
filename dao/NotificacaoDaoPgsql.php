<?php 

    require_once 'models/Notificacao.php';

    class NotificacaoDaoPgsql implements NotificacaoDAO{
        private $pdo;
        
        public function __construct(PDO $driver){
            $this->pdo = $driver;
        }

        public function adicionar(Notificacao $n){
            $sql = $this->pdo->prepare("INSERT INTO notificacao (id_endereco, id_ocorrencia, descricao, data_emissao, representante, notificado, ativo, data_vencimento) VALUES
            (:id_endereco, :id_ocorrencia, :descricao, :data_emissao, :representante, :notificado, 'true', :vencimento)");
            $sql->bindValue(":id_endereco", $n->getIdEndereco());
            $sql->bindValue(":id_ocorrencia", $n->getIdOcorrencia());
            $sql->bindValue(":descricao", $n->getDescricao());
            $sql->bindValue(":data_emissao", $n->getDataEmissao());
            $sql->bindValue(":representante", $n->getRepresentante());
            $sql->bindValue(":notificado", $n->getNotificado());
            $sql->bindValue(":vencimento", $n->getDataVencimento());

            if($sql->execute()){
                $id = $this->pdo->lastInsertId();
                return $id;
            }else{
                return false;
            }
        }

        public function buscarConsulta(){
            $sql = $this->pdo->prepare("SELECT * FROM notificacao WHERE ativo = true");
            $sql->execute();

            if($sql->rowCount() > 0){
                $lista = $sql->fetchAll(PDO::FETCH_ASSOC);
                foreach($lista as $item){
                    $n = New Notificacao;
                    $n->setId($item['id_notificacao']);
                    $n->setIdEndereco($item['id_endereco']);
                    $n->setDataEmissao($item['data_emissao']);
                    $n->setNotificado($item['notificado']);
                    $n->setDataVencimento($item['data_vencimento']);

                    $array[] = $n;
                }
                return $array;
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

        public function buscarPeloId($id){
            $sql = $this->pdo->prepare("SELECT * FROM notificacao WHERE id_notificacao = :id");
            $sql->bindValue(":id", $id);
            $sql->execute();

            if($sql->rowCount() > 0){
                $linha = $sql->fetch(PDO::FETCH_ASSOC);
                $n = New Notificacao;
                $n->setId($linha['id_notificacao']);
                $n->setIdEndereco($linha['id_endereco']);
                $n->setIdOcorrencia($linha['id_ocorrencia']);
                $n->setDescricao($linha['descricao']);
                $n->setDataEmissao($linha['data_emissao']);
                $n->setRepresentante($linha['representante']);
                $n->setNotificado($linha['notificado']);
                $n->setDataVencimento($linha['data_vencimento']);
                $n->setDocumentoAssinado($linha['arquivo_assinado']);

        
                return $n;
            }else{
                return false;
            }
        }

        public function editar(Notificacao $n){
            $sql = $this->pdo->prepare("UPDATE notificacao SET id_endereco = :id_endereco, descricao = :descricao, 
            data_emissao = :data_emissao, representante = :representante, notificado = :notificado, data_vencimento = :vencimento, arquivo_assinado = :arquivo_assinado 
            WHERE id_notificacao = :id_notificacao");
            $sql->bindValue(":id_endereco", $n->getIdEndereco());
            $sql->bindValue(":descricao", $n->getDescricao());
            $sql->bindValue(":data_emissao", $n->getDataEmissao());
            $sql->bindValue(":representante", $n->getRepresentante());
            $sql->bindValue(":notificado", $n->getNotificado());
            $sql->bindValue(":id_notificacao", $n->getId());
            $sql->bindValue(":vencimento", $n->getDataVencimento());
            if($n->getDocumentoAssinado()){
                $sql->bindValue(":arquivo_assinado", $n->getDocumentoAssinado());
            }else{
                $sql->bindValue(":arquivo_assinado", null, PDO::PARAM_NULL);
            }


            if($sql->execute()){
                return true;
            }else{
                return false;
            }
        }
    }