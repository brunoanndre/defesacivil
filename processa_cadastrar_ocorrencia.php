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
$chamado_id = addslashes($_POST['id_chamado']);
$endereco_principal = addslashes($_POST['endereco_principal']);
$longitude = addslashes($_POST['longitude']);
$latitude = addslashes($_POST['latitude']);
$cep = addslashes($_POST['cep']);
$cidade = addslashes($_POST['cidade']);
$bairro = addslashes($_POST['bairro']);
$logradouro = addslashes($_POST['logradouro']);
$numero = addslashes($_POST['complemento']);
$referencia = addslashes($_POST['referencia']);
//$agente_principal = addslashes($_POST['agente_principal']);
$agente_apoio_1 = addslashes($_POST['agente_apoio_1']);
$agente_apoio_2 = addslashes($_POST['agente_apoio_2']);
$data_ocorrencia = addslashes($_POST['data_ocorrencia']);
$titulo = addslashes($_POST['titulo']);
$descricao = addslashes($_POST['descricao']);
$ocorr_origem = addslashes($_POST['ocorr_origem']);
$nome_pessoa1 = addslashes($_POST['pessoa_atendida_1']);
$nome_pessoa2 = addslashes($_POST['pessoa_atendida_2']);
$cobrade_categoria = $_POST['cobrade_categoria'];
$cobrade_grupo = $_POST['cobrade_grupo'];
$cobrade_subgrupo = $_POST['cobrade_subgrupo'];
$cobrade_tipo = $_POST['cobrade_tipo'];
$cobrade_subtipo = $_POST['cobrade_subtipo'];
$prioridade = addslashes($_POST['prioridade']);
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

//guarda possiveis erros na inserção do usuário
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

//seleciona o endereço no BD, caso ele nao exista entao cria um novo
$logradouro_id = NULL;
if ($endereco_principal == "Logradouro") {
	$cep = str_replace("-", "", $cep);

	if ($enderecodao->buscarEndereco($logradouro, $numero) === false) {
		$novoEndereco = New Endereco();
		$novoEndereco->setCep($cep);
		$novoEndereco->setCidade($cidade);
		$novoEndereco->setBairro($bairro);
		$novoEndereco->setLogradouro($logradouro);
		$novoEndereco->setNumero($numero);
		$novoEndereco->setReferencia($referencia);
		
		$logradouro_id = $enderecodao->adicionar($novoEndereco);

		if (!$ocorrenciadao)
			$erros = $erros . '&logradouro';
	}

	//INSERIR NO LOG DE ENDEREÇO
	$enderecodao->adicionarLog($logradouro_id, $id_criador,$dataAtual);
	
	$longitude = NULL;
	$latitude = NULL;
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
	//caso esteja tudo certo, procede com a inserção no banco de dados
} else {
	//insere a ocorrencia no banco de dados

	$novaOcorrencia = New Ocorrencia();
	$novaOcorrencia->setChamadoId($chamado_id);
	$novaOcorrencia->setEnderecoPrincipal($endereco_principal);
	$novaOcorrencia->setLatitude($latitude);
	$novaOcorrencia->setLongitude($longitude);
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

	$adicionar = $ocorrenciadao->adicionar($novaOcorrencia);

	if($adicionar == false) {
		header('location:index.php?pagina=cadastrarOcorrencia&erroDB');
	} else {
		if ($chamado_id != NULL) {
			if ($ocorrenciadao->encerraChamadoAtivo($chamado_id) == false) {
				header('location:index.php?pagina=cadastrarOcorrencia&erroDB');
			} else {
				header('location:index.php?pagina=cadastrarOcorrencia&sucesso');
			}
		} else {
			header('location:index.php?pagina=cadastrarOcorrencia&sucesso');
		}
	}
}
