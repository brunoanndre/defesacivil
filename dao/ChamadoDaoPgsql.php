<?php

require_once 'database.php';
require_once 'models/Chamado.php';

date_default_timezone_set('America/Sao_Paulo');

class ChamadoDaoPgsql implements ChamadoDAO{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function buscarConsulta($p){
        if($p == 'normal'){
            $sql = $this->pdo->prepare("SELECT chamado.id_chamado,TO_CHAR(chamado.data_hora, 'dd/mm/yyyy HH24:MI') as dataa) as dataa,
            chamado.origem,chamado.descricao, chamado.prioridade, chamado.nome_pessoa,chamado_logradouro_id, 
            chamado.usado, chamado.cancelado, usuario.nome as usuario, chamado.distribuicao, logradouro
            FROM chamado 
            INNER JOIN usuario ON (chamado.agente_id = usuario.id_usuario)
            INNER JOIN endereco_logradouro el ON chamado_logradouro_id = el.id_logradouro ORDER BY chamado.id_chamado DESC ");
            $sql->execute();

            if($sql->rowCount() > 0 ){
                $linha = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach($linha as $item){
                    $c = new Chamado();
                    $c->setId($item['id_chamado']);
                    $c->setData($item['dataa']);
                    $c->setOrigem($item['origem']);
                    $c->setDescricao($item['descricao']);
                    $c->setPrioridade($item['prioridade']);
                    $c->setNomePessoa($item['nome_pessoa']);
                    $c->setUsado($item['usado']);
                    $c->setCancelado($item['cancelado']);
                    $c->setNomeAgente($item['usuario']);
                    $c->setDistribuicao($item['distribuicao']);
                    $c->setLogradouro($item['logradouro']);

                    $array[] = $c;
                }
                return $array;
            }else{
                    return false;
                    die;
                }
        }
        if($p == 'usado_false'){
            $sql = $this->pdo->prepare("SELECT chamado.id_chamado,TO_CHAR(chamado.data_hora, 'dd/mm/yyyy HH24:MI') as dataa,
            chamado.origem,chamado.descricao, chamado.prioridade, chamado.nome_pessoa, chamado_logradouro_id, chamado.endereco_principal,
            chamado.usado, chamado.cancelado, usuario.nome as usuario, chamado.distribuicao, logradouro
            FROM chamado 
            INNER JOIN usuario ON (chamado.agente_id = usuario.id_usuario)
            FULL OUTER JOIN endereco_logradouro el ON chamado_logradouro_id = el.id_logradouro WHERE chamado.usado = false
             ORDER BY id_chamado DESC ");
            
            $sql->execute();

            if($sql->rowCount() > 0 ){
                $linha = $sql->fetchAll(PDO::FETCH_ASSOC);
                foreach($linha as $item){
                    $c = new Chamado();
                    $c->setId($item['id_chamado']);
                    $c->setLogradouroId($item['chamado_logradouro_id']);
                    $c->setData($item['dataa']);
                    $c->setOrigem($item['origem']);
                    $c->setDescricao($item['descricao']);
                    $c->setPrioridade($item['prioridade']);
                    $c->setNomePessoa($item['nome_pessoa']);
                    $c->setUsado($item['usado']);
                    $c->setCancelado($item['cancelado']);
                    $c->setEnderecoPrincipal($item['endereco_principal']);
                    $c->setNomeAgente($item['usuario']);
                    $c->setDistribuicao($item['distribuicao']);
                    $c->setLogradouro($item['logradouro']);

                    $array[] = $c;
                }
                return $array;
            }else{
                    return false;
                    die;
                }
        }
        if($p == 'usado_true'){
            $sql = $this->pdo->prepare("SELECT chamado.id_chamado,TO_CHAR(chamado.data_hora, 'dd/mm/yyyy HH24:MI') as dataa,
            chamado.origem,chamado.descricao, chamado.prioridade, chamado.nome_pessoa, chamado_logradouro_id,
            chamado.usado, chamado.cancelado, usuario.nome as usuario, chamado.distribuicao, logradouro
            FROM chamado 
            INNER JOIN usuario ON (chamado.agente_id = usuario.id_usuario)
            INNER JOIN endereco_logradouro el ON chamado_logradouro_id = el.id_logradouro WHERE chamado.usado = true OR chamado.cancelado = true ORDER BY id_chamado DESC");
            $sql->execute();

            if($sql->rowCount() > 0 ){
                $linha = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach($linha as $item){
                    $c = new Chamado();
                    $c->setId($item['id_chamado']);
                    $c->setData($item['dataa']);
                    $c->setOrigem($item['origem']);
                    $c->setDescricao($item['descricao']);
                    $c->setPrioridade($item['prioridade']);
                    $c->setNomePessoa($item['nome_pessoa']);
                    $c->setUsado($item['usado']);
                    $c->setCancelado($item['cancelado']);
                    $c->setNomeAgente($item['usuario']);
                    $c->setDistribuicao($item['distribuicao']);
                    $c->setLogradouro($item['logradouro']);

                    $array[] = $c;
                }
                return $array;
            }else{
                    return false;
                }
        }

        if($p == 'usado_true_cancelado_false'){
            $sql = $this->pdo->prepare("SELECT chamado.id_chamado,TO_CHAR(chamado.data_hora, 'dd/mm/yyyy HH24:MI') as dataa,
            chamado.origem,chamado.descricao, chamado.prioridade, chamado.nome_pessoa,chamado_logradouro_id,logradouro,
            chamado.usado, chamado.cancelado, usuario.nome as usuario, chamado.distribuicao
            FROM chamado 
            INNER JOIN usuario ON (chamado.agente_id = usuario.id_usuario) 
            INNER JOIN endereco_logradouro el ON chamado_logradouro_id = el.id_logradouro WHERE chamado.usado = true AND chamado.cancelado = false  ORDER BY id_chamado DESC");
            $sql->execute();

            if($sql->rowCount() > 0 ){
                $linha = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach($linha as $item){
                    $c = new Chamado();
                    $c->setId($item['id_chamado']);
                    $c->setData($item['dataa']);
                    $c->setOrigem($item['origem']);
                    $c->setDescricao($item['descricao']);
                    $c->setPrioridade($item['prioridade']);
                    $c->setNomePessoa($item['nome_pessoa']);
                    $c->setUsado($item['usado']);
                    $c->setCancelado($item['cancelado']);
                    $c->setNomeAgente($item['usuario']);
                    $c->setDistribuicao($item['distribuicao']);
                    $c->setLogradouro($item['logradouro']);

                    $array[] = $c;
                }
                return $array;
            }else{
                    return false;
                }
        }

        if($p == 'usado_cancelado_true'){

            $sql = $this->pdo->prepare("SELECT chamado.id_chamado,TO_CHAR(chamado.data_hora, 'dd/mm/yyyy HH24:MI') as dataa,
            chamado.origem,chamado.descricao, chamado.prioridade, chamado.nome_pessoa, chamado_logradouro_id,logradouro,
            chamado.usado, chamado.cancelado, usuario.nome as usuario, chamado.distribuicao
            FROM chamado 
            INNER JOIN usuario ON (chamado.agente_id = usuario.id_usuario)
            INNER JOIN endereco_logradouro el ON chamado_logradouro_id = el.id_logradouro WHERE chamado.usado = true AND chamado.cancelado = true  ORDER BY dataa DESC");
            $sql->execute();

            if($sql->rowCount() > 0 ){
                $linha = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach($linha as $item){
                    $c = new Chamado();
                    $c->setId($item['id_chamado']);
                    $c->setData($item['dataa']);
                    $c->setOrigem($item['origem']);
                    $c->setDescricao($item['descricao']);
                    $c->setPrioridade($item['prioridade']);
                    $c->setNomePessoa($item['nome_pessoa']);
                    $c->setUsado($item['usado']);
                    $c->setCancelado($item['cancelado']);
                    $c->setNomeAgente($item['usuario']);
                    $c->setDistribuicao($item['distribuicao']);
                    $c->setLogradouro($item['logradouro']);

                    $array[] = $c;
                }
                return $array;
            }else{
                    return false;
                }
        }
    }


    public function adicionar(Chamado $c){
        $sql = $this->pdo->prepare("INSERT INTO chamado (data_hora,origem,pessoa_id,chamado_logradouro_id,
        descricao,endereco_principal, agente_id, prioridade, distribuicao, nome_pessoa, fotos, id_coordenada,possui_fotos)
        VALUES (:timestamp,:origem,:pessoa_atendida,:logradouro_id,:descricao,
        :endereco_principal, :id_usuario, :prioridade, :distribuicao, :nome,:fotos, :id_coordenada,:possui_fotos)");
        $sql->bindValue(":timestamp", $c->getData());
        $sql->bindValue(":origem", $c->getOrigem());
        if($c->getPessoaId() == false){
            $sql->bindValue(":pessoa_atendida", null, PDO::PARAM_NULL);
        }else{
            $sql->bindValue(":pessoa_atendida", $c->getPessoaId());
        }
        if($c->getEnderecoPrincipal() == 'Logradouro'){
            $sql->bindValue(":logradouro_id", $c->getLogradouroId());
        }else{
            $sql->bindValue(":logradouro_id", null, PDO::PARAM_NULL);
        }
        if($c->getEnderecoPrincipal() == 'Coordenada'){
            $sql->bindValue(':id_coordenada', $c->getIdCoordenada());
        }else{
            $sql->bindValue(":id_coordenada", null, PDO::PARAM_NULL);
        }
        $sql->bindValue(":descricao", $c->getDescricao());
        $sql->bindValue(":endereco_principal", $c->getEnderecoPrincipal());
        $sql->bindValue(":id_usuario", $c->getAgenteId());
        $sql->bindValue(":prioridade", $c->getPrioridade());
        $sql->bindValue(":distribuicao", $c->getDistribuicao());
        $sql->bindValue(":nome", $c->getNomePessoa());
        if($c->getFotos() == "{}"){
            $sql->bindValue(":fotos",null,PDO::PARAM_NULL);
        }else{
            $sql->bindValue(":fotos", $c->getFotos());
        }
        $sql->bindValue(":possui_fotos", $c->getPossuiFotos());


        if($sql->execute()){
                $id = $this->pdo->lastInsertId();
                return $id;
        }else{
            return false;
        }
            
               
            
    }

    public function adicionarLog($id_usuario, $id_chamado, $dataAtual){
        $sql = $this->pdo->prepare("INSERT INTO log_chamado (id_usuario, id_chamado, data_hora, acao)
		VALUES (:id_usuario,:id_chamado,:dataAtual,'cadastrar')");
        $sql->bindValue(":id_usuario", $id_usuario);
        $sql->bindValue(":id_chamado", $id_chamado);
        $sql->bindValue(":dataAtual", $dataAtual);
        $sql->execute();
    }

    public function buscarPeloId($id){
        $sql = $this->pdo->prepare("SELECT * FROM chamado WHERE id_chamado = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $linha = $sql->fetch(PDO::FETCH_ASSOC);

            $c = new Chamado;
            $c->setId($linha['id_chamado']);
            $c->setData($linha['data_hora']);
            $c->setOrigem($linha['origem']);
            $c->setDescricao($linha['descricao']);
            $c->setEnderecoPrincipal($linha['endereco_principal']);
            $c->setLogradouroId($linha['chamado_logradouro_id']);
            $c->setPessoaId($linha['pessoa_id']);
            $c->setUsado($linha['usado']);
            $c->setAgenteId($linha['agente_id']);
            $c->setPrioridade($linha['prioridade']);
            $c->setDistribuicao($linha['distribuicao']);
            $c->setNomePessoa($linha['nome_pessoa']);
            $c->setCancelado($linha['cancelado']);
            $c->setMotivo($linha['motivo']);
            $c->setFotos($linha['fotos']);
            $c->setIdCoordenada($linha['id_coordenada']);
            $c->setPossuiFotos($linha['possui_fotos']);
            $c->setUsado($linha['usado']);
            $c->setDataAtendimento($linha['data_atendimento']);

            return $c;
        }else{
            return false;
        }

    }

    public function buscaFotos($i){
            $sql = $this->pdo->prepare("SELECT fotos FROM chamado WHERE id_chamado = :id");
            $sql->bindValue(":id", $i);
            $sql->execute();

            return $sql->fetch()['fotos'];
    }

    public function excluirFoto($id,$fotos, $possui_fotos){
        $sql = $this->pdo->prepare('UPDATE chamado SET fotos = :fotos, possui_fotos = :possui_fotos WHERE id_chamado = :id');
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

    public function editar(Chamado $c){

        $sql = $this->pdo->prepare("UPDATE chamado SET origem = :origem, descricao = :descricao, 
        endereco_principal = :enderecoPrincipal, chamado_logradouro_id = :idLogradouro, 
        pessoa_id = :idPessoa, usado =:usado ,fotos= :fotos, possui_fotos = :possui_fotos, prioridade = :prioridade, distribuicao = :distribuicao, nome_pessoa = :nomePessoa, data_atendimento = :dataAtendimento ,id_coordenada = :idCoordenada
        WHERE id_chamado = :idChamado");
            $sql->bindValue(":origem", $c->getOrigem());
            $sql->bindValue(":idChamado", $c->getId());
            $sql->bindValue(":descricao", $c->getDescricao());
            $sql->bindValue(":usado", $c->getUsado());
            if($c->getDataAtendimento() == true){
                $sql->bindValue(":dataAtendimento", $c->getDataAtendimento());
            }else{
                $sql->bindValue(":dataAtendimento", null, pdo::PARAM_NULL);
            }
            $sql->bindValue(":possui_fotos", $c->getPossuiFotos());
            $sql->bindValue(":enderecoPrincipal", $c->getEnderecoPrincipal());
            if($c->getLogradouroId() == "" || $c->getLogradouroId() == null){
                $sql->bindValue(":idLogradouro", null , PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":idLogradouro", $c->getLogradouroId());
            }
            if($c->getPessoaId() == ""){
                $sql->bindValue(":idPessoa", null, PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":idPessoa", $c->getPessoaId());
            }
            $sql->bindValue(":fotos", $c->getFotos());
            $sql->bindValue(":prioridade", $c->getPrioridade());
            $sql->bindValue(":distribuicao", $c->getDistribuicao());
            $sql->bindValue(":nomePessoa", $c->getNomePessoa());
            if($c->getIdCoordenada() == "" || $c->getIdCoordenada() == null){
                $sql->bindValue(":idCoordenada", null, PDO::PARAM_NULL);
            }else{
                $sql->bindValue(":idCoordenada", $c->getIdCoordenada());
            }

                if($sql->execute()){
                    return true;
                }else{
                    return false;
                }


    }
    
}