
<?php



//inclui a conexao com o banco de dados
include 'database.php';
require_once 'dao/ChamadoDaoPgsql.php';
require_once 'dao/EnderecoDaoPgsql.php';
require_once 'dao/PessoaDaoPgsql.php';

$pessoadao = New PessoaDaoPgsql($pdo);
$enderecodao = new EnderecoDaoPgsql($pdo);
$chamadodao = new ChamadoDaoPgsql($pdo);

//recebe dados do $_POST
$origem = addslashes($_POST['origem_chamado']);
$nome = addslashes($_POST['nome_chamado']);
$endereco_principal = addslashes($_POST['endereco_principal']);
$longitude = addslashes($_POST['longitude']);
$latitude = addslashes($_POST['latitude']);
$cep = addslashes($_POST['cep']);
$cidade = addslashes($_POST['cidade']);
$bairro = addslashes($_POST['bairro']);
$logradouro = addslashes($_POST['logradouro']);
$numero = addslashes($_POST['numero']);
$referencia = addslashes($_POST['referencia']);
$descricao = addslashes($_POST['descricao']);
$prioridade = addslashes($_POST['prioridade']);
$distribuicao = addslashes($_POST['distribuicao']);
$complemento = filter_input(INPUT_POST, 'complemento');

if($origem == 'Outro'){
	$origem = filter_input(INPUT_POST, 'origem_chamado2');
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

$erros='';

session_start();

$id_usuario = $_SESSION['id_usuario'];
$dataAtual = date('d-m-Y H:i:s');

if($endereco_principal == "Logradouro"){
	$cep = str_replace("-","",$cep);
	$linhaendereco = $enderecodao->buscarEndereco($logradouro,$numero);	
	$id_coordenada = null;
	if($linhaendereco == false){
		$e = new Endereco();
		$e->setCep($cep);
		$e->setCidade($cidade);
		$e->setBairro($bairro);
		$e->setLogradouro($logradouro);
		$e->setNumero($numero);
		$e->setReferencia($referencia);
		$e->setComplemento($complemento);

		$logradouro_id = $enderecodao->adicionar($e);

		if($logradouro_id == false){
			$erros = $erros.'&logradouro';
		}

	}else{
		$logradouro_id = $linhaendereco['id_logradouro'];
	}


	$enderecodao->adicionarLog($logradouro_id,$id_usuario,$dataAtual);

	$longitude = null;
	$latitude = null;
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



if(strlen($nome) > 0){ //se a pessoa foi informada, busca a mesma no BD 
	$pessoa_atendida = $pessoadao->buscarPeloNome($nome);
	}

/*if(strlen($distribuicao) == 0 || $distribuicao == null){ //se o agente foi informado, busca o mesmo no BD
//	$result = pg_query($connection, "SELECT * FROM usuario WHERE nome = '$distribuicao'");
//	if($result){
//		if(pg_num_rows($result) == 0){ //agente nao encontrado
//			$erros = $erros.'&distribuicao';
//		}else{  //agente encontrado, seleciona o id do mesmo
//			$linha = pg_fetch_array($result, 0);
//			$distribuicao = $linha['id_usuario'];
//		}
//	}else //retorna erro caso nao consiga acessar o banco de dados
//		$erros = $erros.'&distribuicao';
//}else //agente nao foi informado
	$distribuicao = null;
}*/

$timestamp = $dataAtual;


if(strlen($erros) > 0){
    //echo pg_last_error();
    header('location:index.php?pagina=cadastrarChamado&erroDB'.$erros);
//caso esteja tudo certo, procede com a inser????o no banco de dados
}else{
	//insere o chamado no banco de dados
	$c = new Chamado();
	$c->setData($timestamp);
	$c->setOrigem($origem);
	$c->setPessoaId($pessoa_atendida);
	$c->setLogradouroId($logradouro_id);
	$c->setIdCoordenada($id_coordenada);
	$c->setDescricao($descricao);
	$c->setEnderecoPrincipal($endereco_principal);
	$c->setAgenteId($id_usuario);
	$c->setPrioridade($prioridade);
	$c->setDistribuicao($distribuicao);
	$c->setNomePessoa($nome);
	$c->setFotos($pg_array);
	$c->setPossuiFotos($possui_fotos);

		$id_chamado = $chamadodao->adicionar($c);
		

	if($id_chamado !== false){

		$chamadodao->adicionarLog($id_usuario,$id_chamado,$dataAtual);
		
		header('location:index.php?pagina=exibirChamado&id='. $id_chamado .'&sucesso');
	}else
		//echo pg_last_error();
		header('location:index.php?pagina=cadastrarChamado&erroDB');
}
header('location:index.php?pagina=exibirChamado&id='. $id_chamado .'&sucesso');