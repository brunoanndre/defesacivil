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
            $sql = $this->pdo->prepare("SELECT chamado.id_chamado,TO_CHAR(chamado.data_hora, 'dd/mm/yyyy hh:ii') as dataa) as dataa,
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
            $sql = $this->pdo->prepare("SELECT chamado.id_chamado,TO_CHAR(chamado.data_hora, 'dd/mm/yyyy hh:ii') as dataa,
            chamado.origem,chamado.descricao, chamado.prioridade, chamado.nome_pessoa, chamado_logradouro_id,
            chamado.usado, chamado.cancelado, usuario.nome as usuario, chamado.distribuicao, logradouro
            FROM chamado 
            INNER JOIN usuario ON (chamado.agente_id = usuario.id_usuario)
            INNER JOIN endereco_logradouro el ON chamado_logradouro_id = el.id_logradouro WHERE chamado.usado = false  ORDER BY id_chamado DESC ");
            
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
            $sql = $this->pdo->prepare("SELECT chamado.id_chamado,TO_CHAR(chamado.data_hora, 'dd/mm/yyyy hh:ii') as dataa) as dataa,
            chamado.origem,chamado.descricao, chamado.prioridade, chamado.nome_pessoa, chamado_logradouro_id,
            chamado.usado, chamado.cancelado, usuario.nome as usuario, chamado.distribuicao, logradouro
            FROM chamado 
            INNER JOIN usuario ON (chamado.agente_id = usuario.id_usuario)
            INNER JOIN endereco_logradouro el ON chamado_logradouro_id = el.id_logradouro WHERE chamado.usado = true OR chamado.cancelado = true ORDER BY dataa DESC");
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

        if($p == 'usado_true_cancelado_false'){
            $sql = $this->pdo->prepare("SELECT chamado.id_chamado,TO_CHAR(chamado.data_hora, 'dd/mm/yyyy hh:ii') as dataa,
            chamado.origem,chamado.descricao, chamado.prioridade, chamado.nome_pessoa,chamado_logradouro_id,logradouro,
            chamado.usado, chamado.cancelado, usuario.nome as usuario, chamado.distribuicao
            FROM chamado 
            INNER JOIN usuario ON (chamado.agente_id = usuario.id_usuario) 
            INNER JOIN endereco_logradouro el ON chamado_logradouro_id = el.id_logradouro WHERE chamado.usado = true AND chamado.cancelado = false  ORDER BY dataa DESC");
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

        if($p == 'usado_cancelado_true'){
            $sql = $this->pdo->prepare("SELECT chamado.id_chamado,TO_CHAR(chamado.data_hora, 'dd/mm/yyyy hh:ii') as dataa,
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
                    die;
                }
        }
    }


    public function adicionar(Chamado $c){
        $sql = $this->pdo->prepare("INSERT INTO chamado (data_hora,origem,pessoa_id,chamado_logradouro_id,
        descricao,endereco_principal,latitude,longitude, agente_id, prioridade, distribuicao, nome_pessoa, fotos)
        VALUES (:timestamp,:origem,:pessoa_atendida,:logradouro_id,:descricao,
        :endereco_principal,:latitude,:longitude, :id_usuario, :prioridade, :distribuicao, :nome,:fotos)");
        $sql->bindValue(":timestamp", $c->getData());
        $sql->bindValue(":origem", $c->getOrigem());
        if($c->getPessoaId() == false){
            $sql->bindValue(":pessoa_atendida", null, PDO::PARAM_NULL);
        }else{
            $sql->bindValue(":pessoa_atendida", $c->getPessoaId());
        }
        $sql->bindValue(":logradouro_id", $c->getLogradouroId());
        $sql->bindValue(":descricao", $c->getDescricao());
        $sql->bindValue(":endereco_principal", $c->getEnderecoPrincipal());
        if($c->getLatitude() == false){
            $sql->bindValue(":latitude", null, PDO::PARAM_NULL);
        }else{
            $sql->bindValue(":latitude", $c->getLatitude());
        }
        if($c->getLongitude() == false){
            $sql->bindValue(":longitude", null, PDO::PARAM_NULL);
        }else{
            $sql->bindValue(":longitude", $c->getLongitude());
        } 
        $sql->bindValue(":id_usuario", $c->getAgenteId());
        $sql->bindValue(":prioridade", $c->getPrioridade());
        $sql->bindValue(":distribuicao", $c->getDistribuicao());
        $sql->bindValue(":nome", $c->getNomePessoa());
        if($c->getFotos() == "{}"){
            $sql->bindValue(":fotos",null,PDO::PARAM_NULL);
        }else{
            $sql->bindValue(":fotos", $c->getFotos());
        }

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
            $c->setLatitude($linha['latitude']);
            $c->setLongitude($linha['longitude']);
            $c->setPessoaId($linha['pessoa_id']);
            $c->setUsado($linha['usado']);
            $c->setAgenteId($linha['agente_id']);
            $c->setPrioridade($linha['prioridade']);
            $c->setDistribuicao($linha['distribuicao']);
            $c->setNomePessoa($linha['nome_pessoa']);
            $c->setCancelado($linha['cancelado']);
            $c->setMotivo($linha['motivo']);
            $c->setFotos($linha['fotos']);

            return $c;
        }else{
            return false;
        }

    }
}