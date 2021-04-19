<?php

    require_once 'dao/ChamadoDaoPgsql.php';
    require_once 'dao/EnderecoDaoPgsql.php';
    require_once 'dao/PessoaDaoPgsql.php';

    $pessoadao = New PessoaDaoPgsql($pdo);
    $chamadodao = New ChamadoDaoPgsql($pdo);
    $enderecodao = New EnderecoDaoPgsql($pdo);

    $origem = filter_input(INPUT_POST, 'origem_chamado');
    if($origem == "Outros"){
        $origem = filter_input(INPUT_POST, 'origem_chamado2');
    }
    $nomePessoa = filter_input(INPUT_POST, 'nome_chamado');
    $distribuicao = filter_input(INPUT_POST, 'distribuicao');
    $enderecoPrincipal = filter_input(INPUT_POST, 'endereco_principal');
    $latitude = filter_input(INPUT_POST, 'latitude');
    $longitude = filter_input(INPUT_POST, 'longitude');
    $cep = filter_input(INPUT_POST,'cep');
    $cidade = filter_input(INPUT_POST, 'cidade');
    $bairro = filter_input(INPUT_POST, 'bairro');
    $logradouro = filter_input(INPUT_POST, 'logradouro');
    $numero = filter_input(INPUT_POST, 'numero');
    $referencia = filter_input(INPUT_POST, 'referencia');
    $descricao = filter_input(INPUT_POST, 'descricao');
    $prioridade = filter_input(INPUT_POST, 'prioridade');
    $idLogradouro = filter_input(INPUT_POST, 'id_logradouro');
    $idCoordenada = filter_input(INPUT_POST, 'id_coordenada');
    $idChamado = filter_input(INPUT_POST, 'id_chamado');

    $base64_array = array();


foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
	$temp = $_FILES["files"]["tmp_name"][$key];

	if (empty($temp))
		break;

	$binary = file_get_contents($temp);
	$base64 = base64_encode($binary);
	array_push($base64_array, $base64);
}

$pg_array = '{' . join(',', $base64_array) . '}';


    if($origem !== "" && $descricao !== ""){

        if($enderecoPrincipal == "Logradouro"){
            $verificaEndereco = $enderecodao->buscarEndereco($logradouro,$numero);

            if($verificaEndereco == false){

                $e = New Endereco();
                $e->setId($idLogradouro);
                $e->setCidade($cidade);
                $e->setCep($cep);
                $e->setBairro($bairro);
                $e->setLogradouro($logradouro);
                $e->setNumero($numero);
                $e->setReferencia($referencia);

                if($enderecodao->editarLogradouro($e) == true){
                    $erros = "";
                }else{
                    $erros = "logradouro";
                }
            }else{
                $idLogradouro = $verificaEndereco;
            }
        }else{
            $c = New Endereco();
            $c->setId($idCoordenada);
            $c->setLatitude($latitude);
            $c->setLongitude($longitude);

            if($enderecodao->editarCoordenada($e) == true){
                $erros .= "";
            }else{
                $erros .= "coordenada";
            }
        }


        if(strlen($nomePessoa) > 0){ //se a pessoa foi informada, busca a mesma no BD 
            $pessoa_atendida = $pessoadao->buscarPeloNome($nomePessoa);
            }

        if(strlen($erros) > 0){
            header('Location:index.php?pagina=editarChamado&id='.$idChamado .'& '.$erros.' ');
        }
            $c = New Chamado();
            $c->setFotos($pg_array);
            $c->setId($idChamado);
            $c->setOrigem($origem);
            $c->setDistribuicao($distribuicao);
            $c->setDescricao($descricao);
            $c->setEnderecoPrincipal($enderecoPrincipal);
            $c->setIdCoordenada($idCoordenada);
            $c->setLogradouroId($idLogradouro);
            $c->setPessoaId($pessoa_atendida);
            $c->setPrioridade($prioridade);
            $c->setNomePessoa($nomePessoa);

            if($chamadodao->editar($c)){
                header('Location:index.php?pagina=exibirChamado&id='. $idChamado .'&sucessoEdit');
            }else{
                header('Location:index.php?pagina=editarChamado' . $idChamado . '&erroDB');
            }

    }