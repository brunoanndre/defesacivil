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

            if($sql->rowCount() > 0){
                $linha = $sql->fetch(PDO::FETCH_ASSOC);   

                $o = New Ocorrencia();
                $o->setId($linha['id_ocorrencia']);
                $o->setChamadoId($linha['chamado_id']);
                $o->setEnderecoPrincipal($linha['ocorr_endereco_principal']);
                $o->setLatitude($linha['ocorr_coordenada_latitude']);
                $o->setLongitude($linha['ocorr_coordenada_longitude']);
                $o->setLogradouroId($linha['ocorr_logradouro_id']);
                $o->setIdCriador($linha['agente_principal']);
                $o->setApoio1($linha['agente_apoio_1']);
                $o->setApoio2($linha['agente_apoio_2']);
                $o->setData($linha['data_ocorrencia']);
                $o->setTitulo($linha['ocorr_titulo']);
                $o->setDescricao($linha['ocorr_descricao']);
                $o->setOrigem($linha['ocorr_origem']);
                $o->setIdPessoa1($linha['atendido_1']);
                $o->setIdPessoa2($linha['atendido_2']);
                $o->setCobrade($linha['ocorr_cobrade']);
                $o->setPossuiFotos($linha['ocorr_fotos']);
                $o->setPrioridade($linha['ocorr_prioridade']);
                $o->setAnalisado($linha['ocorr_analisado']);
                $o->setCongelado($linha['ocorr_congelado']);
                $o->setEncerrado($linha['ocorr_encerrado']);
                $o->setIdCriador($linha['usuario_criador']);
                $o->setDataAlteracao($linha['data_alteracao']);
                $o->setReferencia($linha['ocorr_referencia']);
                $o->setFotos($linha['fotos']);
                $o->setPessoa1($linha['nome_pessoa1']);
                $o->setPessoa2($linha['nome_pessoa2']);
                $o->setAtivo($linha['ativo']);
                $o->setUsuarioEditor($linha['usuario_editor']);

                return $o;
            }else{
                return false;
            }

        }

        public function adicionar(Ocorrencia $o){
            $sql = $this->pdo->prepare("INSERT INTO ocorrencia 
            (chamado_id,ocorr_endereco_principal,ocorr_coordenada_latitude,ocorr_coordenada_longitude,
            ocorr_logradouro_id,agente_principal,agente_apoio_1,agente_apoio_2,
            data_ocorrencia,ocorr_titulo,ocorr_descricao,ocorr_origem,atendido_1,atendido_2,ocorr_cobrade,
            ocorr_fotos,ocorr_prioridade,ocorr_analisado,ocorr_congelado,ocorr_encerrado,
            usuario_criador,data_alteracao,ocorr_referencia, fotos, nome_pessoa1, nome_pessoa2, usuario_editor,ativo)
            VALUES (:chamado_id, :ocorr_endereco_principal, :latitude, :longitude, :logradouro_id, :agente_principal,
            :agente_apoio_1,:agente_apoio_2, :data_ocorrencia, :titulo, :descricao, :origem, :atendido_1, :atendido_2,
            :cobrade, :possui_fotos, :prioridade, :analisado, :congelado, :encerrado, :criador, :data_alteracao, :referencia,
            :fotos,:nome_pessoa1,:nome_pessoa2,:usuario_editor, :ativo)");
            if($o->getChamadoId() != null){
                $sql->bindValue(":chamado_id", $o->getChamadoId(), PDO::PARAM_INT);
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
            $sql->bindValue(":data_alteracao", $o->getDataAlteracao());
            if($o->getReferencia() != null || $o->getReferencia() != ''){
                $sql->bindValue(":referencia", $o->getReferencia());
            }else{
                $sql->bindValue(":referencia", null, PDO::PARAM_NULL);
            }
            $sql->bindValue(":fotos", $o->getFotos());
            $sql->bindValue(":nome_pessoa1", $o->getPessoa1());
            $sql->bindValue(":nome_pessoa2", $o->getPessoa2());
            $sql->bindValue(":usuario_editor", $o->getUsuarioEditor());
            $sql->bindValue(":ativo", $o->getAtivo());

            if($sql->execute()){
                return true;
            }else{
                return false;
            }  
        }

        public function remover($id){
            
        }

        public function editarOcorrencia(Ocorrencia $o){
            $sql = $this->pdo->prepare("UPDATE ocorrencia SET
            chamado_id = :chamado_id, ocorr_endereco_principal = :endereco_principal,
            ocorr_coordenada_latitude = :latitude, ocorr_coordenada_longitude = :longitude,
            ocorr_logradouro_id = :logradouro_id, agente_principal = :agente_principal,
            agente_apoio_1 = :agente_apoio_1, agente_apoio_2 = :agente_apoio_2,
            data_ocorrencia = :data_ocorrencia, 
            ocorr_titulo = :titulo, ocorr_descricao = :descricao, ocorr_origem = :ocorr_origem,
            atendido_1 = :atendida_1, atendido_2 = :atendida_2,
            ocorr_cobrade = :cobrade, ocorr_fotos = :possui_fotos,
            ocorr_prioridade = :prioridade, ocorr_analisado = :analisado, ocorr_congelado = :congelado,
            ocorr_encerrado = :encerrado, usuario_criador = :id_criador, data_alteracao =:dataAtual, fotos = :pg_array, 
            nome_pessoa1 = :pessoa_atendida_1, nome_pessoa2 = :pessoa_atendida_2, usuario_editor = :usuario_editor 
            WHERE id_ocorrencia = :id_ocorrencia;");
            if($o->getChamadoId() == null || $o->getChamadoId() == ''){
                $sql->bindValue(":chamado_id", null, PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":chamado_id", $o->getChamadoId());
            }
            $sql->bindValue(":endereco_principal", $o->getEnderecoPrincipal());
            if($o->getLatitude() == null || $o->getLatitude() == ''){
                $sql->bindValue(":latitude", null, PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":latitude", $o->getLatitude());
            }
            if($o->getLongitude() == null || $o->getLongitude() == ''){
                $sql->bindValue(":longitude", null, PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":longitude", $o->getLongitude());
            }
            if($o->getLogradouroid() == null || $o->getLogradouroid() == ''){
                $sql->bindValue(":logradouro_id", null, PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":logradouro_id", $o->getLogradouroid());
            }
            $sql->bindValue(":agente_principal", $o->getIdCriador());
            if($o->getApoio1() == null || $o->getApoio1() == ''){
                $sql->bindValue(":agente_apoio_1", null, PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":agente_apoio_1", $o->getApoio1());
            }
            if($o->getApoio2() == null || $o->getApoio2() == ''){
                $sql->bindValue(":agente_apoio_2", null, PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":agente_apoio_2", $o->getApoio2());
            }
            $sql->bindValue(":data_ocorrencia", $o->getData());
            $sql->bindValue(":titulo", $o->getTitulo());
            $sql->bindValue(":descricao", $o->getDescricao());
            $sql->bindValue(":ocorr_origem", $o->getOrigem());
            if($o->getIdPessoa1() == null || $o->getIdPessoa1() == ''){
                $sql->bindValue(":atendida_1", null, PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":atendida_1", $o->getIdPessoa1());
            }
            if($o->getIdPessoa2() == null || $o->getIdPessoa2() == ''){
                $sql->bindValue(":atendida_2", null, PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":atendida_2", $o->getIdPessoa2());
            }
            $sql->bindValue(":cobrade", $o->getCobrade());
            $sql->bindValue(":possui_fotos", $o->getPossuiFotos());
            $sql->bindValue(":prioridade", $o->getPrioridade());
            $sql->bindValue(":analisado", $o->getAnalisado());
            $sql->bindValue(":congelado", $o->getCongelado());
            $sql->bindValue(":encerrado", $o->getEncerrado());
            $sql->bindValue(":id_criador", $o->getIdCriador());
            $sql->bindValue(":dataAtual", $o->getDataAlteracao());
            $sql->bindValue(":pg_array", $o->getFotos());
            $sql->bindValue(":pessoa_atendida_1",$o->getPessoa1());
            $sql->bindValue(":pessoa_atendida_2",$o->getPessoa2());
            $sql->bindValue(":usuario_editor", $o->getUsuarioEditor());
            $sql->bindValue(":id_ocorrencia", $o->getId());
            if($sql->execute()){
                return true;
            }else{
                return false;
            }

        }

        public function editarEndereco(Ocorrencia $o){
            $sql = $this->pdo->prepare("UPDATE endereco_logradouro SET cep = :cepbd, cidade = :cidade, bairro = :bairro, 
            logradouro = :logradouro, numero = :numero, referencia = :referencia");
            $sql->bindValue(":cepbd", $o->getCep());
            $sql->bindValue(":cidade", $o->getCidade());
            $sql->bindValue(":bairro", $o->getBairro());
            $sql->bindValue(":logradouro", $o->getLogradouro());
            $sql->bindValue(":numero", $o->getNumero());
            $sql->bindValue(":referencia", $o->getReferencia());
            if($sql->execute()){
                return true;
            }else{
                return false;
            }


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

        public function buscaOcorrenciaUsuarioEndereco($i){
            $sql = $this->pdo->prepare("SELECT * FROM ocorrencia o INNER JOIN 
            usuario u ON o.agente_principal = u.id_usuario
            INNER JOIN endereco_logradouro el ON o.ocorr_logradouro_id = el.id_logradouro
            WHERE id_ocorrencia = :id");
            $sql->bindValue(":id", $i);
            $sql->execute();

            $linha = $sql->fetch(PDO::FETCH_ASSOC);

            return $linha;
        }

        public function buscaFotos($i){
            $sql = $this->pdo->prepare("SELECT fotos FROM ocorrencia WHERE id_ocorrencia = :id_ocorrencia");
            $sql->bindValue(":id_ocorrencia", $i);
            $sql->execute();

            return $sql->fetch()['fotos'];
        }

        public function buscarConsulta($parametro){
            if($parametro == 'normal'){
                $sql = $this->pdo->prepare("SELECT ocorrencia.id_ocorrencia,ocorrencia.ocorr_prioridade, 
                TO_CHAR(ocorrencia.data_ocorrencia, 'DD/MM/YYYY') as data_ocorrencia,
                usuario.nome, cobrade.subgrupo, ocorrencia.nome_pessoa1, ocorr_descricao 
                FROM ocorrencia 
                INNER JOIN usuario ON ocorrencia.agente_principal = usuario.id_usuario
                INNER JOIN cobrade ON ocorrencia.ocorr_cobrade = cobrade.codigo ORDER BY data_ocorrencia DESC");
                $sql->execute();

                if($sql->rowCount() > 0){
                    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);

                    foreach($lista as $item){
                        $o = new Ocorrencia;
                        $o->setId($item['id_ocorrencia']);
                        $o->setPrioridade($item['ocorr_prioridade']);
                        $o->setData($item['data_ocorrencia']);
                        $o->setNomeAgentePrincipal($item['nome']);
                        $o->setCobrade($item['subgrupo']);
                        $o->setPessoa1($item['nome_pessoa1']);
                        $o->setDescricao($item['ocorr_descricao']);

                        $array[] = $o;
                    }
                    return $array;
                }else{
                    return false;
                }
            }

            if($parametro == 'encerrada_false'){
                $sql = $this->pdo->prepare("SELECT ocorrencia.id_ocorrencia,ocorrencia.ocorr_prioridade, 
                TO_CHAR(ocorrencia.data_ocorrencia, 'YYYY/MM/DD') as data_ocorrencia,
                usuario.nome, cobrade.subgrupo, ocorrencia.nome_pessoa1, ocorr_descricao
                FROM ocorrencia 
                INNER JOIN usuario ON ocorrencia.agente_principal = usuario.id_usuario
                INNER JOIN cobrade ON ocorrencia.ocorr_cobrade = cobrade.codigo  WHERE ocorrencia.ocorr_encerrado = FALSE ORDER BY id_ocorrencia DESC");
                $sql->execute();

                if($sql->rowCount() > 0){
                    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);

                    foreach($lista as $item){
                        $o = new Ocorrencia;
                        $o->setId($item['id_ocorrencia']);
                        $o->setPrioridade($item['ocorr_prioridade']);
                        $o->setData($item['data_ocorrencia']);
                        $o->setNomeAgentePrincipal($item['nome']);
                        $o->setCobrade($item['subgrupo']);
                        $o->setPessoa1($item['nome_pessoa1']);
                        $o->setDescricao($item['ocorr_descricao']);

                        $array[] = $o;
                    }
                    return $array;
                }else{
                    return false;
                }
            }
            if($parametro == 'ativo_true'){
                $sql = $this->pdo->prepare("SELECT ocorrencia.id_ocorrencia,ocorrencia.ocorr_prioridade, TO_CHAR(ocorrencia.data_ocorrencia, 'YYYY/MM/DD') as data_ocorrencia,
                usuario.nome,cobrade.subgrupo, ocorrencia.nome_pessoa1, ocorr_descricao
                FROM ocorrencia 
                INNER JOIN usuario ON ocorrencia.agente_principal = usuario.id_usuario 
                INNER JOIN cobrade ON ocorrencia.ocorr_cobrade = cobrade.codigo 
                WHERE ocorrencia.ativo = TRUE ORDER BY id_ocorrencia DESC");
                $sql->execute();

                if($sql->rowCount() > 0){
                    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);

                    foreach($lista as $item){
                        $o = new Ocorrencia;
                        $o->setId($item['id_ocorrencia']);
                        $o->setPrioridade($item['ocorr_prioridade']);
                        $o->setData($item['data_ocorrencia']);
                        $o->setNomeAgentePrincipal($item['nome']);
                        $o->setCobrade($item['subgrupo']);
                        $o->setPessoa1($item['nome_pessoa1']);
                        $o->setDescricao($item['ocorr_descricao']);

                        $array[] = $o;
                    }
                    return $array;
                }else{
                    return false;
                }
            }

            if($parametro == 'ativo_encerrada_false'){
                $sql = $this->pdo->prepare("SELECT ocorrencia.id_ocorrencia,ocorrencia.ocorr_prioridade, TO_CHAR(ocorrencia.data_ocorrencia, 'YYYY/MM/DD') as data_ocorrencia,
                usuario.nome,cobrade.subgrupo, ocorrencia.nome_pessoa1, ocorr_descricao
                FROM ocorrencia 
                INNER JOIN usuario ON ocorrencia.agente_principal = usuario.id_usuario 
                INNER JOIN cobrade ON ocorrencia.ocorr_cobrade = cobrade.codigo 
                WHERE ocorrencia.ativo = TRUE AND ocorrencia.ocorr_encerrado = FALSE ORDER BY id_ocorrencia DESC");
                $sql->execute();

                if($sql->rowCount() > 0){
                    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);

                    foreach($lista as $item){
                        $o = new Ocorrencia;
                        $o->setId($item['id_ocorrencia']);
                        $o->setPrioridade($item['ocorr_prioridade']);
                        $o->setData($item['data_ocorrencia']);
                        $o->setNomeAgentePrincipal($item['nome']);
                        $o->setCobrade($item['subgrupo']);
                        $o->setPessoa1($item['nome_pessoa1']);
                        $o->setDescricao($item['ocorr_descricao']);

                        $array[] = $o;
                    }
                    return $array;
                }else{
                    return false;
                }
            }
            if($parametro == 'ativo_encerrada_true'){
                $sql = $this->pdo->prepare("SELECT ocorrencia.id_ocorrencia,ocorrencia.ocorr_prioridade, TO_CHAR(ocorrencia.data_ocorrencia, 'YYYY/MM/DD') as data_ocorrencia,
                usuario.nome,cobrade.subgrupo, ocorrencia.nome_pessoa1, ocorr_descricao
                FROM ocorrencia 
                INNER JOIN usuario ON ocorrencia.agente_principal = usuario.id_usuario 
                INNER JOIN cobrade ON ocorrencia.ocorr_cobrade = cobrade.codigo 
                WHERE ocorrencia.ativo = true AND ocorrencia.ocorr_encerrado = true ORDER BY id_ocorrencia DESC");
                $sql->execute();

                if($sql->rowCount() > 0){
                    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);

                    foreach($lista as $item){
                        $o = new Ocorrencia;
                        $o->setId($item['id_ocorrencia']);
                        $o->setPrioridade($item['ocorr_prioridade']);
                        $o->setData($item['data_ocorrencia']);
                        $o->setNomeAgentePrincipal($item['nome']);
                        $o->setCobrade($item['subgrupo']);
                        $o->setPessoa1($item['nome_pessoa1']);
                        $o->setDescricao($item['ocorr_descricao']);
                        
                        $array[] = $o;
                    }
                    return $array;
                }else{
                    return false;
                }
            }
        }
    }
