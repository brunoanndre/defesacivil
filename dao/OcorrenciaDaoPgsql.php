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
                $o->setIdCoordenada($linha['id_coordenada']);
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
            (chamado_id,ocorr_endereco_principal,id_coordenada,
            ocorr_logradouro_id,agente_principal,agente_apoio_1,agente_apoio_2,
            data_ocorrencia,ocorr_titulo,ocorr_descricao,ocorr_origem,atendido_1,atendido_2,ocorr_cobrade,
            ocorr_fotos,ocorr_prioridade,ocorr_analisado,ocorr_congelado,ocorr_encerrado,
            usuario_criador,data_alteracao,ocorr_referencia, fotos, nome_pessoa1, nome_pessoa2, usuario_editor,ativo)
            VALUES (:chamado_id, :ocorr_endereco_principal, :id_coordenada, :logradouro_id, :agente_principal,
            :agente_apoio_1,:agente_apoio_2, :data_ocorrencia, :titulo, :descricao, :origem, :atendido_1, :atendido_2,
            :cobrade, :possui_fotos, :prioridade, :analisado, :congelado, :encerrado, :criador, :data_alteracao, :referencia,
            :fotos,:nome_pessoa1,:nome_pessoa2,:usuario_editor, :ativo)");
            if($o->getChamadoId() != null){
                $sql->bindValue(":chamado_id", $o->getChamadoId(), PDO::PARAM_INT);
            }else{
                $sql->bindValue(":chamado_id", null, PDO::PARAM_NULL);
            }
            $sql->bindValue(":ocorr_endereco_principal", $o->getEnderecoPrincipal());
            if($o->getIdCoordenada() == "" || $o->getIdCoordenada() == null){
                $sql->bindValue(":id_coordenada", null, PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":id_coordenada", $o->getIdCoordenada());
            }
            if($o->getLogradouroid() == "" || $o->getLogradouroid() == null){
                $sql->bindValue(":logradouro_id", null, PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":logradouro_id", $o->getLogradouroid());
            }
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

            try{
                $sql->execute();
                $id = $this->pdo->lastInsertId();
                return $id;
            }catch(PDOException $e){
                echo $e->getMessage();
            }

            die;
            if($sql->execute()){
      
                return $id;
            }else{
                return false;
            }  
        }

        public function editarOcorrencia(Ocorrencia $o){

            $sql = $this->pdo->prepare("UPDATE ocorrencia SET
            chamado_id = :chamado_id, ocorr_endereco_principal = :endereco_principal,
            id_coordenada = :id_coordenada,
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
            if($o->getIdCoordenada() == "" || $o->getIdCoordenada() == null){
                $sql->bindValue(":id_coordenada", null, PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":id_coordenada", $o->getIdCoordenada());
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

            try{
                if($sql->execute()){
                    return true;
                }else{
                    return false;
                }
            }catch(PDOException $e){
                echo $e->getMessage();
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
                $sql = $this->pdo->prepare("SELECT ocorrencia.ocorr_titulo, ocorrencia.ocorr_endereco_principal, ocorrencia.ocorr_logradouro_id, ocorrencia.id_coordenada,ocorrencia.id_ocorrencia,ocorrencia.ocorr_prioridade, 
                TO_CHAR(ocorrencia.data_ocorrencia, 'DD/MM/YYYY HH24:MI') as data_ocorrencia,
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
                        $o->setTitulo($item['ocorr_titulo']);
                        $o->setEnderecoPrincipal($item['ocorr_endereco_principal']);
                        $o->setLogradouroId($item['ocorr_logradouro_id']);
                        $o->setIdCoordenada($item['id_coordenada']);

                        $array[] = $o;
                    }
                    return $array;
                }else{
                    return false;
                }
            }

            if($parametro == 'encerrada_false'){
                $sql = $this->pdo->prepare("SELECT ocorrencia.ocorr_titulo,ocorrencia.ocorr_endereco_principal, ocorrencia.ocorr_logradouro_id, ocorrencia.id_coordenada,ocorrencia.id_ocorrencia,ocorrencia.ocorr_prioridade, 
                TO_CHAR(ocorrencia.data_ocorrencia, 'DD/MM/YYYY HH24:MI') as data_ocorrencia,
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
                        $o->setTitulo($item['ocorr_titulo']);
                        $o->setEnderecoPrincipal($item['ocorr_endereco_principal']);
                        $o->setLogradouroId($item['ocorr_logradouro_id']);
                        $o->setIdCoordenada($item['id_coordenada']);

                        $array[] = $o;
                    }
                    return $array;
                }else{
                    return false;
                }
            }
            if($parametro == 'ativo_true'){
                $sql = $this->pdo->prepare("SELECT ocorrencia.ocorr_titulo,ocorrencia.id_ocorrencia,ocorrencia.ocorr_endereco_principal, ocorrencia.ocorr_logradouro_id, ocorrencia.id_coordenada,ocorrencia.ocorr_prioridade, TO_CHAR(ocorrencia.data_ocorrencia, 'DD/MM/YYYY') as data_ocorrencia,
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
                        $o->setTitulo($item['ocorr_titulo']);
                        $o->setEnderecoPrincipal($item['ocorr_endereco_principal']);
                        $o->setLogradouroId($item['ocorr_logradouro_id']);
                        $o->setIdCoordenada($item['id_coordenada']);

                        $array[] = $o;
                    }
                    return $array;
                }else{
                    return false;
                }
            }

            if($parametro == 'ativo_encerrada_false'){
                $sql = $this->pdo->prepare("SELECT ocorrencia.ocorr_titulo,ocorrencia.ocorr_endereco_principal, ocorrencia.ocorr_logradouro_id, ocorrencia.id_coordenada,ocorrencia.id_ocorrencia,ocorrencia.ocorr_prioridade, TO_CHAR(ocorrencia.data_ocorrencia, 'DD/MM/YYYY') as data_ocorrencia,
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
                        $o->setTitulo($item['ocorr_titulo']);
                        $o->setEnderecoPrincipal($item['ocorr_endereco_principal']);
                        $o->setLogradouroId($item['ocorr_logradouro_id']);
                        $o->setIdCoordenada($item['id_coordenada']);

                        $array[] = $o;
                    }
                    return $array;
                }else{
                    return false;
                }
            }
            if($parametro == 'ativo_encerrada_true'){
                $sql = $this->pdo->prepare("SELECT ocorrencia.ocorr_titulo,ocorrencia.ocorr_endereco_principal, ocorrencia.ocorr_logradouro_id, ocorrencia.id_coordenada,ocorrencia.id_ocorrencia,ocorrencia.ocorr_prioridade, TO_CHAR(ocorrencia.data_ocorrencia, 'DD/MM/YYYY') as data_ocorrencia,
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
                        $o->setTitulo($item['ocorr_titulo']);
                        $o->setEnderecoPrincipal($item['ocorr_endereco_principal']);
                        $o->setLogradouroId($item['ocorr_logradouro_id']);
                        $o->setIdCoordenada($item['id_coordenada']);
                        
                        $array[] = $o;
                    }
                    return $array;
                }else{
                    return false;
                }
            }

            if($parametro == 'ativo_congelada_true'){
                $sql = $this->pdo->prepare("SELECT ocorrencia.ocorr_titulo,ocorrencia.ocorr_endereco_principal, ocorrencia.ocorr_logradouro_id, ocorrencia.id_coordenada,ocorrencia.id_ocorrencia,ocorrencia.ocorr_prioridade, TO_CHAR(ocorrencia.data_ocorrencia, 'DD/MM/YYYY') as data_ocorrencia,
                usuario.nome,cobrade.subgrupo, ocorrencia.nome_pessoa1, ocorr_descricao
                FROM ocorrencia 
                INNER JOIN usuario ON ocorrencia.agente_principal = usuario.id_usuario 
                INNER JOIN cobrade ON ocorrencia.ocorr_cobrade = cobrade.codigo 
                WHERE ocorrencia.ativo = true AND ocorrencia.ocorr_congelado = true ORDER BY id_ocorrencia DESC");
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
                        $o->setTitulo($item['ocorr_titulo']);
                        $o->setEnderecoPrincipal($item['ocorr_endereco_principal']);
                        $o->setLogradouroId($item['ocorr_logradouro_id']);
                        $o->setIdCoordenada($item['id_coordenada']);
                        
                        $array[] = $o;
                    }
                    return $array;
                }else{
                    return false;
                }
            }
        }

        public function excluirFoto($id, $fotos,$possui_fotos){
            $sql = $this->pdo->prepare('UPDATE ocorrencia SET fotos = :fotos, ocorr_fotos= :possui_fotos WHERE id_ocorrencia = :id');
            if($fotos == 'null'){
                $sql->bindValue(":fotos", null, PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":fotos", $fotos);
            }
            $sql->bindValue(":possui_fotos", $possui_fotos);
            $sql->bindValue(":id", $id);
            
            if($sql->execute()){
                return true;
            }else{
                return false;
            }
        }
    }
