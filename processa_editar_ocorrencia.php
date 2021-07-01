<?php
//inclui a conexao com o banco de dados
include 'database.php';
require_once 'dao/OcorrenciaDaoPgsql.php';
require_once 'dao/UsuarioDaoPgsql.php';
require_once 'dao/PessoaDaoPgsql.php';
require_once 'dao/EnderecoDaoPgsql.php';

$enderecodao = new EnderecoDaoPgsql($pdo);
$ocorrenciadao = new OcorrenciaDaoPgsql($pdo);
$usuariodao = new UsuarioDaoPgsql($pdo);
$pessoadao = new PessoaDaoPgsql($pdo);

//recebe dados do $_POST
$id_ocorrencia = filter_input(INPUT_POST, 'id_ocorrencia');
$chamado_id = filter_input(INPUT_POST, 'chamado_id');
$endereco_principal = filter_input(INPUT_POST, 'endereco_principal'); 
$longitude = filter_input(INPUT_POST, 'longitude');
$latitude = filter_input(INPUT_POST, 'latitude');
$cep = filter_input(INPUT_POST, 'cep');
$cidade = filter_input(INPUT_POST, 'cidade');
$bairro = filter_input(INPUT_POST, 'bairro');
$logradouro = filter_input(INPUT_POST, 'logradouro');
$numero = filter_input(INPUT_POST, 'numero');
$complemento = filter_input(INPUT_POST, 'complemento');
$referencia = filter_input(INPUT_POST, 'referencia');
$agente_principal = filter_input(INPUT_POST, 'agente_principal');
$agente_apoio_1 = filter_input(INPUT_POST, 'agente_apoio_1');
$agente_apoio_2 = filter_input(INPUT_POST, 'agente_apoio_2');
$ocorr_retorno = filter_input(INPUT_POST, 'ocorr_retorno');
$ocorr_referencia = filter_input(INPUT_POST, 'ocorr_referencia');
$data_ocorrencia = filter_input(INPUT_POST, 'data_ocorrencia');
$titulo = filter_input(INPUT_POST, 'titulo'); 
$descricao = filter_input(INPUT_POST, 'descricao');
$ocorr_origem = filter_input(INPUT_POST, 'ocorr_origem');
if($ocorr_origem == 'Outro'){
	$ocorr_origem = filter_input(INPUT_POST, 'ocorr_origem2');
}
$pessoa_atendida_1 = filter_input(INPUT_POST, 'pessoa_atendida_1');
$pessoa_atendida_2 = filter_input(INPUT_POST, 'pessoa_atendida_2');
$cobrade_categoria = filter_input(INPUT_POST, 'cobrade_categoria');
$cobrade_grupo = filter_input(INPUT_POST, 'cobrade_grupo');
$cobrade_subgrupo = filter_input(INPUT_POST, 'cobrade_subgrupo');
$cobrade_tipo = filter_input(INPUT_POST, 'cobrade_tipo');
$cobrade_subtipo = filter_input(INPUT_POST, 'cobrade_subtipo');   
$natureza = filter_input(INPUT_POST, 'natureza');
$possui_fotos = filter_input(INPUT_POST, 'possui_fotos');
$cobrade_descricao = filter_input(INPUT_POST, 'cobrade_descricao');
$prioridade = filter_input(INPUT_POST, 'prioridade');
$analisado = filter_input(INPUT_POST, 'analisado');
$congelado = filter_input(INPUT_POST, 'congelado');
$encerrado = filter_input(INPUT_POST, 'encerrado');
$id_logradouro = filter_input(INPUT_POST, 'id_logradouro');
session_start();
$usuario_editor = $_SESSION['id_usuario'];
$id_coordenada = filter_input(INPUT_POST,'id_coordenada');
$imginpt = filter_input(INPUT_POST,'files[]');
$base64_array = array();
try{

	foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name){
		$temp = $_FILES["files"]["tmp_name"][$key];
	
		if(empty($temp))
			break;
			
		$binary = file_get_contents($temp);
		$base64 = base64_encode($binary);
		array_push($base64_array, $base64);	
	}
	
	if(count($base64_array) > 0){
		if($possui_fotos == 1){
			$possui_fotos = 'true';
			$string = $ocorrenciadao->buscaFotos($id_ocorrencia);
			$barras = array("{","}");
			$fotos = str_replace($barras,"",$string);
			$fotosArray = explode(",",$fotos);
			for($i= 0; $i < sizeof($base64_array); $i++){
				array_push($fotosArray,$base64_array[$i]);
			}
			$fotosString = implode(",", $fotosArray);
			$pg_array = '{' . $fotosString . '}';
		}else{
			$fotosString = implode(",", $base64_array);
			$pg_array = '{' . $fotosString . '}';
			$possui_fotos = 'true';
		}
	}else{
	
		if($possui_fotos == 1){
			$possui_fotos = 'true';
	
			$string = $ocorrenciadao->buscaFotos($id_ocorrencia);
			
			$pg_array = $string;
		}else{
			$possui_fotos = 'false';
			$pg_array = '{'.join(',',$base64_array).'}';
		}
	}
	
	session_start();
	$id_criador = $_SESSION['id_usuario'];
	$dataAtual = date('Y-m-d H:i:s');
	
	
	//guarda possiveis erros na inserção do usuário
	$erros = '';
	
	if($cobrade_categoria == 0){
		$cobrade = '00000';
	}else{
		//verifica se os valores para formar o codigo do cobrade estao de acordo
		if(!preg_match("/^[0-5]$/", $cobrade_categoria))
			$cobrade_categoria = 0;
		if(!preg_match("/^[0-5]$/", $cobrade_grupo))
			$cobrade_grupo = 0;
		if(!preg_match("/^[0-5]$/", $cobrade_subgrupo))
			$cobrade_subgrupo = 0;
		if(!preg_match("/^[0-5]$/", $cobrade_tipo))
			$cobrade_tipo = 0;
		if(!preg_match("/^[0-5]$/", $cobrade_subtipo))
			$cobrade_subtipo = 0;
		$cobrade = $cobrade_categoria.$cobrade_grupo.$cobrade_subgrupo.$cobrade_tipo.$cobrade_subtipo;
		if(strlen($cobrade) > 5 || substr($cobrade, 0, 1) == '0' || substr($cobrade, 1, 2) == '0' || substr($cobrade, 2, 3) == '0')
			$erros = $erros.'&cobrade';
	}
	
	
	
	if($endereco_principal == "Logradouro"){
		$cep = str_replace("-", "", $cep);
	//VERIFICA SE TEM O ENDEREÇO NO BD, SE NÃO TIVE INSERE
	
		$id_coordenada = null;
	
		if($id_logradouro == "" || $id_logradouro == null){
			$id_logradouro = 0;
		}
	
		if($enderecodao->buscarPeloId($id_logradouro) == false){
			$novoendereco = new Endereco();
			$novoendereco->setCep($cep);
			$novoendereco->setCidade($cidade);
			$novoendereco->setBairro($bairro);
			$novoendereco->setLogradouro($logradouro);
			$novoendereco->setNumero($numero);
			$novoendereco->setReferencia($referencia);
			$novoendereco->setComplemento($complemento);
	
			$id_logradouro = $enderecodao->adicionar($novoendereco);
			
			if($id_logradouro == false){
				$erros = $erros.'&logradouro';
			}
		}else{
	
			$novoendereco = new Endereco();
			$novoendereco->setCep($cep);
			$novoendereco->setCidade($cidade);
			$novoendereco->setBairro($bairro);
			$novoendereco->setLogradouro($logradouro);
			$novoendereco->setNumero($numero);
			$novoendereco->setReferencia($referencia);
			$novoendereco->setId($id_logradouro);
			$novoendereco->setComplemento($complemento);
			
			$enderecodao->editarLogradouro($novoendereco);
	
		}
	}else{
		$linhaCordenada = $enderecodao->buscarCoordenada($latitude,$longitude);
	
		$id_logradouro = null;
		if($linhaCordenada == false){
			$e = New Endereco();
			$e->setLatitude($latitude);
			$e->setLongitude($longitude);
	
			$id_coordenada = $enderecodao->adicionarCoordenada($e);
		}else{
			if($linhaCordenada['latitude'] !== $latitude){
				$e = New Endereco();
				$e->setLatitude($latitude);
				$e->setLongitude($longitude);
		
				$id_coordenada = $enderecodao->adicionarCoordenada($e);
			}else{
				$id_coordenada = $linhaCordenada['id_coordenada'];
			}
	
		}
	}
	
	
	
	//busca o agente informado no banco de dados
	
	
	if($usuariodao->buscarPeloNome($agente_principal) == false){
		$erros = $erros.'&agente_principal'; //agente nao encontrado
	}else{ //agente encontrado, seleciona o id do mesmo
		$linha = $usuariodao->buscarPeloNome($agente_principal);
		$agente_principal = $linha->getId();
	}
	
	
	if(strlen($agente_apoio_1) > 0 && $agente_apoio_1 != null){ //se o agente foi informado, busca o mesmo no BD
		if($usuariodao->buscarPeloNome($agente_apoio_1) == false){//agente nao encontrado
				$erros = $erros.'&agente_apoio_1';
			}else{  //agente encontrado, seleciona o id do mesmo
				$linha = $usuariodao->buscarPeloNome($agente_apoio_1);
				$agente_apoio_1 = $linha->getId();
			}
	}else //agente nao foi informado
		$agente_apoio_1 = null;
	
	if(strlen($agente_apoio_2) > 0 && $agente_apoio_2 != null){ //se o agente foi informado, busca o mesmo no BD
		if($usuariodao->buscarPeloNome($agente_apoio_2) == false){//agente nao encontrado
			$erros = $erros.'&agente_apoio_1';
		}else{  //agente encontrado, seleciona o id do mesmo
			$linha = $usuariodao->buscarPeloNome($agente_apoio_2);
			$agente_apoio_2 = $linha->getId();
		}
	}else //agente nao foi informado
	$agente_apoio_2 = null;
	
		
	if(strlen($pessoa_atendida_1) > 0){ //se a pessoa foi informada, busca a mesma no BD 
		if($pessoadao->buscarPeloNome($pessoa_atendida_1) == false){//pessoa nao encontrada
				$erros = $erros.'&pessoa_atendida_1';
			}else{  //pessoa encontrada, seleciona o id da mesma
				$atendida_1 = $pessoadao->buscarPeloNome($pessoa_atendida_1);
			}
	}else //pessoa nao foi informada
		$pessoa_atendida_1 = '';
		
	if(strlen($pessoa_atendida_2) > 0){ //se a pessoa foi informada, busca a mesma no BD
		if($pessoadao->buscarPeloNome($pessoa_atendida_2) == false){//pessoa nao encontrada
			$erros = $erros.'&pessoa_atendida_2';
		}else{  //pessoa encontrada, seleciona o id da mesma
			$atendida_2 = $pessoadao->buscarPeloNome($pessoa_atendida_2);
		}
	}else //pessoa nao foi informada
	$pessoa_atendida_2 = '';
	
	if(strlen($chamado_id)==0)
		$chamado_id = null;
	
	//caso ocorra algum erro na validacao, entao volta para a pagina e indica onde esta o erro
	if(strlen($erros) > 0){
		header('location:index.php?pagina=editarOcorrencia&id='.$id_ocorrencia.$erros);
	//caso esteja tudo certo, procede com a inserção no banco de dados
	}else{
		//insere a ocorrencia no banco de dados
	
		$novaocorrencia = new Ocorrencia();
		$novaocorrencia->setChamadoId($chamado_id);
		$novaocorrencia->setEnderecoPrincipal($endereco_principal);
		$novaocorrencia->setIdCoordenada($id_coordenada);
		$novaocorrencia->setLogradouroId($id_logradouro);
		$novaocorrencia->setIdCriador($agente_principal);
		$novaocorrencia->setApoio1($agente_apoio_1);
		$novaocorrencia->setApoio2($agente_apoio_2);
		$novaocorrencia->setData($data_ocorrencia);
		$novaocorrencia->setTitulo($titulo);
		$novaocorrencia->setDescricao($descricao);
		$novaocorrencia->setOrigem($ocorr_origem);
		$novaocorrencia->setPessoa1($pessoa_atendida_1);
		$novaocorrencia->setPessoa2($pessoa_atendida_2);
		$novaocorrencia->setCobrade($cobrade);
		$novaocorrencia->setPossuiFotos($possui_fotos);
		$novaocorrencia->setPrioridade($prioridade);
		$novaocorrencia->setAnalisado($analisado);
		$novaocorrencia->setCongelado($congelado);
		$novaocorrencia->setEncerrado($encerrado);
		$novaocorrencia->setIdPessoa1($atendida_1);
		$novaocorrencia->setIdPessoa2($atendida_2);
		$novaocorrencia->setIdCriador($agente_principal);
		$novaocorrencia->setDataAlteracao($dataAtual);
		$novaocorrencia->setFotos($pg_array);
		$novaocorrencia->setId($id_ocorrencia);
		$novaocorrencia->setUsuarioEditor($usuario_editor);
		
		try{
			$atualizaocorrencia = $ocorrenciadao->editarOcorrencia($novaocorrencia);
	
			if(!$atualizaocorrencia){
				header('location:index.php?pagina=editarOcorrencia&id='.$id_ocorrencia.'&erroDB');
			}else{
				//echo pg_last_error();
				header('location:index.php?pagina=exibirOcorrencia&id='.$id_ocorrencia.'&sucesso');
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	
	
	}
}catch(PDOException $e){
	echo $e->getMessage();
}