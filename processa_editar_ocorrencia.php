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
$id_ocorrencia = $_POST['id_ocorrencia'];
$chamado_id = $_POST['chamado_id'];
$endereco_principal = addslashes($_POST['endereco_principal']);
$longitude = addslashes($_POST['longitude']);
$latitude = addslashes($_POST['latitude']);
$cep = addslashes($_POST['cep']);
$cidade = addslashes($_POST['cidade']);
$bairro = addslashes($_POST['bairro']);
$logradouro = addslashes($_POST['logradouro']);
$numero = addslashes($_POST['complemento']);
$referencia = addslashes($_POST['referencia']);
$agente_principal = addslashes($_POST['agente_principal']);
$agente_apoio_1 = addslashes($_POST['agente_apoio_1']);
$agente_apoio_2 = addslashes($_POST['agente_apoio_2']);
$ocorr_retorno = addslashes($_POST['ocorr_retorno']);
$ocorr_referencia = addslashes($_POST['ocorr_referencia']);
$data_ocorrencia = addslashes($_POST['data_ocorrencia']);
$titulo = addslashes($_POST['titulo']);
$descricao = addslashes($_POST['descricao']);
$ocorr_origem = addslashes($_POST['ocorr_origem']);
$pessoa_atendida_1 = addslashes($_POST['pessoa_atendida_1']);
$pessoa_atendida_2 = addslashes($_POST['pessoa_atendida_2']);
$cobrade_categoria = $_POST['cobrade_categoria'];
$cobrade_grupo = $_POST['cobrade_grupo'];
$cobrade_subgrupo = $_POST['cobrade_subgrupo'];
$cobrade_tipo = $_POST['cobrade_tipo'];
$cobrade_subtipo = $_POST['cobrade_subtipo'];
$natureza = addslashes($_POST['natureza']);
$possui_fotos = addslashes($_POST['possui_fotos']);
$cobrade_descricao = addslashes($_POST['cobrade_descricao']);
$prioridade = addslashes($_POST['prioridade']);
$analisado = addslashes($_POST['analisado']);
$congelado = addslashes($_POST['congelado']);
$encerrado = addslashes($_POST['encerrado']);
$id_logradouro = addslashes($_POST['id_logradouro']);
session_start();
$usuario_editor = $_SESSION['id_usuario'];

$base64_array = array();


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
		$pg_array = join(',',$base64_array).'}';

		$string = $ocorrenciadao->buscaFotos($id_ocorrencia);
		
		$string = str_replace('}','',$string);

		$pg_array = $string.','.$pg_array;
	}else{
		$possui_fotos = true;
		$pg_array = '{'.join('',$base64_array).'}';
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


//seleciona o endereço no BD, caso ele nao exista entao cria um novo
$logradouro_id = null;

if($endereco_principal == "Logradouro"){
//VERIFICA SE TEM O ENDEREÇO NO BD, SE NÃO TIVE INSERE
	if($enderecodao->buscarPeloId($id_logradouro) == false){
		$novoendereco = new Endereco();
		$novoendereco->setCep($cep);
		$novoendereco->setCidade($cidade);
		$novoendereco->setBairro($bairro);
		$novoendereco->setLogradouro($logradouro);
		$novoendereco->setNumero($numero);
		$novoendereco->setReferencia($referencia);

		$enderecodao->adicionar($novoendereco);
		
		if($enderecodao->adicionar($novoendereco) == true){
			$linha = $enderecodao->buscarEndereco($logradouro,$numero);
		}else{
			$erros = $erros.'&logradouro';
		}
	}
	$endereco = $enderecodao->buscarPeloId($id_logradouro);

	$logradouro_id = $endereco->getId();
	$longitude = null;
	$latitude = null;
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
	$novaocorrencia->setLatitude($latitude);
	$novaocorrencia->setLongitude($longitude);
	$novaocorrencia->setLogradouroId($logradouro_id);
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
	
	$atualizaocorrencia = $ocorrenciadao->editarOcorrencia($novaocorrencia);


	$cepbd = str_replace('-','',$cep);
	$novaocorrencia->setCep($cepbd);
	$novaocorrencia->setCidade($cidade);
	$novaocorrencia->setBairro($bairro);
	$novaocorrencia->setLogradouro($logradouro);
	$novaocorrencia->setNumero($numero);
	$novaocorrencia->setReferencia($referencia);

	$atualizaendereco = $ocorrenciadao->editarEndereco($novaocorrencia);

	if(!$atualizaocorrencia && !$atualizaendereco){
		header('location:index.php?pagina=editarOcorrencia&id='.$id_ocorrencia.'&erroDB');
	}else{
		//echo pg_last_error();
		header('location:index.php?pagina=exibirOcorrencia&id='.$id_ocorrencia.'&sucesso');
	}
}