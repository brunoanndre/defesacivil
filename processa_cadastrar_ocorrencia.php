<?php


//inclui a conexao com o banco de dados
include 'database.php';
require_once 'dao/OcorrenciaDaoPgsql.php';
require_once 'dao/EnderecoDaoPgsql.php';
require_once 'dao/UsuarioDaoPgsql.php';
require_once 'dao/PessoaDaoPgsql.php';

$pessoadao = New PessoaDaoPgsql($pdo);
$usuariodao = new UsuarioDaoPgsql($pdo);
$ocorrenciadao = New OcorrenciaDaoPgsql($pdo);
$enderecodao = new EnderecoDaoPgsql($pdo);

//recebe dados do $_POST
$chamado_id = filter_input(INPUT_POST, 'id_chamado');
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
//$agente_principal = addslashes($_POST['agente_principal']);
$agente_apoio_1 = filter_input(INPUT_POST, 'agente_apoio_1');
$agente_apoio_2 = filter_input(INPUT_POST, 'agente_apoio_2');
$data_ocorrencia = filter_input(INPUT_POST, 'data_ocorrencia');
$titulo = filter_input(INPUT_POST, 'titulo');
$descricao = filter_input(INPUT_POST, 'descricao');
$ocorr_origem = filter_input(INPUT_POST, 'ocorr_origem');
$nome_pessoa1 = filter_input(INPUT_POST, 'pessoa_atendida_1');
$nome_pessoa2 = filter_input(INPUT_POST, 'pessoa_atendida_2');
$cobrade_categoria = filter_input(INPUT_POST, 'cobrade_categoria');
$cobrade_grupo = filter_input(INPUT_POST, 'cobrade_grupo');
$cobrade_subgrupo = filter_input(INPUT_POST, 'cobrade_subgrupo');
$cobrade_tipo = filter_input(INPUT_POST, 'cobrade_tipo');
$cobrade_subtipo = filter_input(INPUT_POST, 'cobrade_subtipo');
$prioridade = filter_input(INPUT_POST, 'prioridade');
$id_coordenada = filter_input(INPUT_POST, 'id_coordenada');

$ativo = true;
if($ocorr_origem == 'Outro'){
	$ocorr_origem = filter_input(INPUT_POST,'ocorr_origem2');
}

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
	
if ($pg_array == "{}") {
	$possui_fotos = "false";
} else {
	$possui_fotos = "true";
}


$analisado = 'false';
$congelado = 'false';
$encerrado = 'false';

session_start();
$id_criador = $_SESSION['id_usuario'];
$dataAtual = date('Y-m-d H:i:s');

$data_lancamento = $dataAtual;

if ($_SESSION['nivel_acesso'] != 1) {
	$prioridade = 'Baixa';
	$analisado = 'false';
	$congelado = 'false';
	$encerrado = 'false';
}

//guarda possiveis erros na inser????o do usu??rio
$erros = '';

if ($cobrade_categoria == 0) {
	$cobrade = '00000';
} else {
	//verifica se os valores para formar o codigo do cobrade estao de acordo
	if (!preg_match("/^[0-5]$/", $cobrade_categoria))
		$cobrade_categoria = 0;
	if (!preg_match("/^[0-5]$/", $cobrade_grupo))
		$cobrade_grupo = 0;
	if (!preg_match("/^[0-5]$/", $cobrade_subgrupo))
		$cobrade_subgrupo = 0;
	if (!preg_match("/^[0-5]$/", $cobrade_tipo))
		$cobrade_tipo = 0;
	if (!preg_match("/^[0-5]$/", $cobrade_subtipo))
		$cobrade_subtipo = 0;
	$cobrade = $cobrade_categoria . $cobrade_grupo . $cobrade_subgrupo . $cobrade_tipo . $cobrade_subtipo;
	if (strlen($cobrade) > 5 || substr($cobrade, 0, 1) == '0' || substr($cobrade, 1, 2) == '0' || substr($cobrade, 2, 3) == '0')
		$erros = $erros . '&cobrade';
}
//seleciona o endere??o no BD, caso ele nao exista entao cria um novo
$logradouro_id = NULL;

if ($endereco_principal == "Logradouro") {
	$cep = str_replace("-", "", $cep);
	$id_coordenada = null;
	if ($enderecodao->buscarEndereco($logradouro, $numero) === false) {
		$novoEndereco = New Endereco();
		$novoEndereco->setCep($cep);
		$novoEndereco->setCidade($cidade);
		$novoEndereco->setBairro($bairro);
		$novoEndereco->setLogradouro($logradouro);
		$novoEndereco->setNumero($numero);
		$novoEndereco->setReferencia($referencia);
		$novoEndereco->setComplemento($complemento);

		$logradouro_id = $enderecodao->adicionar($novoEndereco);

		if (!$ocorrenciadao)
			$erros = $erros . '&logradouro';
	}

	//INSERIR NO LOG DE ENDERE??O
	$enderecodao->adicionarLog($logradouro_id, $id_criador,$dataAtual);

}else{
	$linhaCordenada = $enderecodao->buscarCoordenada($latitude,$longitude);
	$logradouro_id = null;
	if($linhaCordenada == false){
		$e = New Endereco();
		$e->setLatitude($latitude);
		$e->setLongitude($longitude);

		$id_coordenada = $enderecodao->adicionarCoordenada($e);
	}
}



if ($ocorr_retorno == "true") { //caso seja retorno de ocorrencia, verifica se nao esta vazio e soh aceita numeros
	if (!preg_match("/^[0-9]$/", $ocorr_referencia) || strlen($ocorr_referencia) <= 0)
		$erros = $erros . '&ocorr_referencia';
} else //caso nao for retorno, seta a variavel como null
	$ocorr_referencia = NULL;

//busca o agente informado no banco de dados
$usuariodao->buscarPeloNome($agente_principal);

if (strlen($agente_apoio_1) > 0 && $agente_apoio_1 != null) { //se o agente foi informado, busca o mesmo no BD

	if ($usuariodao->buscarPeloNome($agente_apoio_1) == false) {
			$erros = $erros . '&agente_apoio_1';
		} else {
			$agente_apoio_1 = $usuariodao->buscarPeloNome($agente_apoio_1)->getId();
		}
	}else{
		$agente_apoio_1 = NULL;
	}

if (strlen($agente_apoio_2) > 0 && $agente_apoio_2 != null) { //se o agente foi informado, busca o mesmo no BD
	$usuariodao->buscarPeloNome($agente_apoio_2);
	if ($ocorrenciadao) {
		if ($usuariodao->buscarPeloNome($agente_apoio_2) == false) { 
			$erros = $erros . '&agente_apoio_2';
		} else {
			$agente_apoio_2 = $usuariodao->buscarPeloNome($agente_apoio_2)->getId();
		}
	} else //retorna erro caso nao consiga acessar o banco de dados
		$erros = $erros . '&agente_apoio_2';
} else //agente nao foi informado
	{
		$agente_apoio_2 = NULL;
	}
	


if(strlen($nome_pessoa1) > 0){ //se a pessoa foi informada, busca a mesma no BD 
	$id_pessoa1 = $pessoadao->buscarPeloNome($nome_pessoa1);

	if($id_pessoa1 == false){
		$id_pessoa1 = NULL;
		}
	}else{ //pessoa nao foi informada
		$nome_pessoa1 = NULL;
		$id_pessoa1 = NULL;
	}

if(strlen($nome_pessoa2) > 0){ //se a pessoa foi informada, busca a mesma no BD
	$id_pessoa2 = $pessoadao->buscarPeloNome($nome_pessoa2);

	if($id_pessoa2 == false){
		$id_pessoa2 = NULL;
	}
}else{
	$id_pessoa2 = NULL;
	$nome_pessoa2 = NULL;
} //pessoa nao foi informada


if (strlen($chamado_id) == 0){
	$chamado_id = NULL;
}


//caso ocorra algum erro na validacao, entao volta para a pagina e indica onde esta o erro
if (strlen($erros) > 0) {
	header('location:index.php?pagina=cadastrarOcorrencia' . $erros);
	//caso esteja tudo certo, procede com a inser????o no banco de dados
} else {
	//insere a ocorrencia no banco de dados

	$novaOcorrencia = New Ocorrencia();
	$novaOcorrencia->setChamadoId($chamado_id);
	$novaOcorrencia->setEnderecoPrincipal($endereco_principal);
	$novaOcorrencia->setIdCoordenada($id_coordenada);
	$novaOcorrencia->setLogradouroId($logradouro_id);
	$novaOcorrencia->setIdCriador($id_criador);
	$novaOcorrencia->setApoio1($agente_apoio_1);
	$novaOcorrencia->setApoio2($agente_apoio_2);
	$novaOcorrencia->setData($data_ocorrencia);
	$novaOcorrencia->setTitulo($titulo);
	$novaOcorrencia->setDescricao($descricao);
	$novaOcorrencia->setOrigem($ocorr_origem);
	$novaOcorrencia->setIdPessoa1($id_pessoa1);
	$novaOcorrencia->setIdPessoa2($id_pessoa2);
	$novaOcorrencia->setCobrade($cobrade);
	$novaOcorrencia->setPossuiFotos($possui_fotos);
	$novaOcorrencia->setPrioridade($prioridade);
	$novaOcorrencia->setAnalisado($analisado);
	$novaOcorrencia->setCongelado($congelado);
	$novaOcorrencia->setEncerrado($encerrado);
	$novaOcorrencia->setDataAlteracao($dataAtual);
	$novaOcorrencia->setFotos($pg_array);
	$novaOcorrencia->setPessoa1($nome_pessoa1);
	$novaOcorrencia->setPessoa2($nome_pessoa2);
	$novaOcorrencia->setUsuarioEditor($id_criador);
	$novaOcorrencia->setAtivo($ativo);

	try{
		$id_ocorrencia = $ocorrenciadao->adicionar($novaOcorrencia);
	}catch(PDOException $e){
		echo $e->getMessage();
	}


	if($id_ocorrencia == false) {
		header('location:index.php?pagina=cadastrarOcorrencia&erroDB');
	} else {
		if ($chamado_id != NULL) {
			if ($ocorrenciadao->encerraChamadoAtivo($chamado_id) == false) {
				header('location:index.php?pagina=cadastrarOcorrencia&erroDB');
			} else {
				header('location:index.php?pagina=exibirOcorrencia&id='. $id_ocorrencia . '&sucessocad');
			}
		} else {
			header('location:index.php?pagina=exibirOcorrencia&id='. $id_ocorrencia . '&sucessocad');
		}
	}
}
