<?php
    include 'database.php';
    require 'models/Ocorrencia.php';

    class OcorrenciaDaoPgsql implements OcorrenciaDAO{
        private $pdo;

        public function __construct(PDO $driver){
            $this->pdo = $driver;
        }

        public function buscarPeloId($id){
            $sql = $this->pdo->prepare("SELECT * FROM ocorrencia WHERE id_ocorrencia = :id_ocorrencia");
            $sql->bindValue(":id_ocorrencia", $id);
            $sql->execute();

            $linha = $sql->fetch(PDO::FETCH_ASSOC);   
            if($sql->rowCount() > 0){
                return $linha;
            }else{
                return false;
            }
        }

        public function buscarTodos(){
            
        }

        public function adicionar(Ocorrencia $o){
            $sql = $this->pdo->prepare("INSERT INTO ocorrencia 
            (chamado_id,ocorr_endereco_principal,ocorr_coordenada_latitude,ocorr_coordenada_longitude,
            ocorr_logradouro_id,agente_principal,agente_apoio_1,agente_apoio_2,
            data_ocorrencia,ocorr_titulo,ocorr_descricao,ocorr_origem,atendido_1,atendido_2,ocorr_cobrade,
            ocorr_fotos,ocorr_prioridade,ocorr_analisado,ocorr_congelado,ocorr_encerrado,
            usuario_criador,data_alteracao,ocorr_referencia, fotos, nome_pessoa1, nome_pessoa2)
            VALUES (:chamado_id, :ocorr_endereco_principal, :latitude, :longitude, :logradouro_id, :agente_principal,
            :agente_apoio_1,:agente_apoio_2, :data_ocorrencia, :titulo, :descricao, :origem, :atendido_1, :atendido_2,
            :cobrade, :possui_fotos, :prioridade, :analisado, :congelado, :encerrado, :criador, :data_alteracao, :referencia,
            :fotos,:nome_pessoa1,:nome_pessoa2)");
            if($o->getChamadoId() != null){
                $sql->bindValue(":chamado_id", $o->getChamadoId(), PDO::PARAM_INT);
                var_dump($o->getChamadoId());
                die;
            }else{
                $sql->bindValue(":chamado_id", null, PDO::PARAM_NULL);
            }
            $sql->bindValue(":ocorr_endereco_principal", $o->getEnderecoPrincipal());
            if($o->getLatitude() != null){
                $sql->bindValue(":latitude", $o->getLatitude());
            }else{
                $sql->bindValue(":latitude", null, PDO::PARAM_NULL);
            }
            if($o->getLongitude() != null){
                $sql->bindValue(":longitude", $o->getLongitude());
            }else{
                $sql->bindValue(":longitude", null, PDO::PARAM_NULL);
            }
            $sql->bindValue(":logradouro_id", $o->getLogradouroid());
            $sql->bindValue(":agente_principal", $o->getIdCriador());
            if($o->getApoio1() != null){
                $sql->bindValue(":agente_apoio_1", $o->getApoio1());
            }else{
                $sql->bindValue(":agente_apoio_1", null, PDO::PARAM_NULL);
            }
            if($o->getApoio2() != null){
                $sql->bindValue(":agente_apoio_2", $o->getApoio2());
            }else{
                $sql->bindValue(":agente_apoio_2", null, PDO::PARAM_NULL);
            }
            $sql->bindValue(":data_ocorrencia", $o->getData());
            $sql->bindValue(":titulo", $o->getTitulo());
            $sql->bindValue(":descricao", $o->getDescricao());
            $sql->bindValue(":origem", $o->getOrigem());
            if($o->getIdPessoa1() != null){
                $sql->bindValue(":atendido_1", $o->getIdPessoa1());
            }else{
                $sql->bindValue(":atendido_1", null, PDO::PARAM_NULL);
            }
            if($o->getIdPessoa2() != null){
                $sql->bindValue(":atendido_2", $o->getIdPessoa2());
            }else{
                $sql->bindValue(":atendido_2", null, PDO::PARAM_NULL);
            }
            $sql->bindValue(":cobrade", $o->getCobrade());
            $sql->bindValue(":possui_fotos", $o->getPossuiFotos());
            $sql->bindValue(":prioridade", $o->getPrioridade());
            $sql->bindValue(":analisado", $o->getAnalisado());
            $sql->bindValue(":congelado", $o->getCongelado());
            $sql->bindValue(":encerrado", $o->getEncerrado());
            $sql->bindValue(":criador", $o->getIdCriador());
            $sql->bindValue(":data_alteracao", $o->getDataAtual());
            $sql->bindValue(":referencia", $o->getReferencia());
            $sql->bindValue(":fotos", $o->getFotos());
            $sql->bindValue(":nome_pessoa1", $o->getPessoa1());
            $sql->bindValue(":nome_pessoa2", $o->getPessoa2());

            $sql->execute();

            if($sql){
                return true;
            }else{
                return false;
            }
        }

        public function remover($id){
            
        }

        public function buscaEndereco($l, $n){
            $sql = $this->pdo->prepare("SELECT * FROM endereco_logradouro WHERE logradouro = :logradouro AND numero = :numero");
            $sql->bindValue(":logradouro", $l);
            $sql->bindValue(":numero", $n);
            $sql->execute();

            if($sql->rowCount() > 0){
                $linha = $sql->fetchAll(PDO::FETCH_ASSOC);
                return $linha['id_logradouro'];
            }else{
                return false;
            }
        }

        public function buscaEnderecoPeloId($id){
            $sql = $this->pdo->prepare("SELECT * FROM endereco_logradouro WHERE id_logradouro = :id_logradouro");
            $sql->bindValue(":id_logradouro", $id);
            $sql->execute();

            $linha = $sql->fetch(PDO::FETCH_ASSOC);
            return $linha;
        }

        public function adicionarEndereco(Ocorrencia $o){
            $sql = $this->pdo->prepare("INSERT INTO endereco_logradouro (cep,cidade,bairro,logradouro,numero,referencia)
            VALUES (:cep, :cidade, :bairro, :logradouro, :numero, :referencia) RETURNING id_logradouro");
            $sql->bindValue(":cep", $o->getCep());
            $sql->bindValue(":cidade", $o->getCidade());
            $sql->bindValue(":bairro", $o->getBairro());
            $sql->bindValue(":logradouro", $o->getLogradouro());
            $sql->bindValue(":numero", $o->getNumero());
            $sql->bindValue(":referencia", $o->getReferencia());
            $sql->execute();

            $linha = $sql->fetch(PDO::FETCH_ASSOC);
            
            return $linha['id_logradouro'];
        }

        public function adicionarLogEndereco($l, $i, $d){
            $sql = $this->pdo->prepare("INSERT INTO log_endereco (id_logradouro, id_usuario, data_hora) VALUES (:logradouro_id,:id_criador,:dataAtual)");
            $sql->bindValue(":logradouro_id", $l);
            $sql->bindValue(":id_criador", $i);
            $sql->bindValue(":dataAtual", $d);
            $sql->execute();

            return true;
        }

        public function buscaAgente($a){
        $sql = $this->pdo->prepare('SELECT * FROM usuario WHERE nome = :agente');
        $sql->bindValue(":agente", $a);
        $sql->execute();

        $linha = $sql->fetch(PDO::FETCH_ASSOC);
        if($sql->rowCount() > 0){
            return $linha['id_usuario'];
        }else{
            return false;
        }
        }

        public function buscaPessoa($p){
            $sql = $this->pdo->prepare("SELECT * FROM pessoa WHERE nome = :pessoa");
            $sql->bindValue(":pessoa", $p);
            $sql->execute();

            $linha = $sql->fetch();
            if($sql->rowCount() == 0){
                return false;
            }else{
                return $linha['id_pessoa'];
            }
        }

        public function buscaPessoaPeloId($i){
            $sql = $this->pdo->prepare("SELECT nome FROM pessoa WHERE id_pessoa = :id_pessoa");
            $sql->bindValue(":id_pessoa", $i);
            $sql->execute();

            return $sql->fetch(PDO::FETCH_ASSOC);
        }

        public function encerraChamadoAtivo($id){
            $sql = $this->pdo->prepare("UPDATE chamado SET usado = TRUE WHERE id_chamado = :chamado_id");
            $sql->bindValue(":chamado_id", $id);
            $sql->execute();

            if($sql){
                return true;
            }else{
                return false;
            }
        }

        public function buscaCobrade($c){
            $sql = $this->pdo->prepare("SELECT * FROM cobrade WHERE codigo = :cobrade");
            $sql->bindValue(":cobrade", $c);
            $sql->execute();

            return $sql->fetch(PDO::FETCH_ASSOC);
        }

        public function buscaInterdicao($i){
            $sql = $this->pdo->prepare("SELECT id_interdicao FROM interdicao WHERE id_ocorrencia = :id_ocorrencia");
            $sql->bindValue(":id_ocorrencia", $i);
            $sql->execute();

            return $sql->fetch(PDO::FETCH_ASSOC)['id_interdicao'];
        }
    }
