<?php
//inclui a conexao com o banco de dados
include 'database.php';
require_once 'dao/ChamadoDaoPgsql.php';
require_once 'dao/EnderecoDaoPgsql.php';

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
$numero = addslashes($_POST['complemento']);
$referencia = addslashes($_POST['referencia']);
$descricao = addslashes($_POST['descricao']);
$prioridade = addslashes($_POST['prioridade']);
$distribuicao = addslashes($_POST['distribuicao']);

$erros='';

session_start();
$id_usuario = $_SESSION['id_usuario'];
$dataAtual = date('Y-m-d H:i:s');

if($endereco_principal == "Logradouro"){
	$cep = str_replace("-","",$cep);
	$linhaendereco = $enderecodao->buscarEndereco($logradouro,$numero);	

	if($linhaendereco == false){
		$e = new Endereco();
		$e->setCep($cep);
		$e->setCidade($cidade);
		$e->setBairro($bairro);
		$e->setLogradouro($logradouro);
		$e->setNumero($numero);
		$e->setReferencia($referencia);

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
}

$pessoa_atendida = null;
/*if(strlen($nome) > 0){ //se a pessoa foi informada, busca a mesma no BD 
	$result = pg_query($connection, "SELECT * FROM pessoa WHERE nome = '$nome'");
	if($result){
		if(pg_num_rows($result) == 0){ //pessoa nao encontrada
			$erros = $erros.'&nome';
		}else{  //pessoa encontrada, seleciona o id da mesma
			$linha = pg_fetch_array($result, 0);
			$pessoa_atendida = $linha['id_pessoa'];
		}
	}else //erro no acesso ao BD
		$erros = $erros.'&nome';
}*/

if(strlen($distribuicao) == 0 || $distribuicao == null){ //se o agente foi informado, busca o mesmo no BD
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
}

$timestamp = $dataAtual;

if(strlen($erros) > 0){
    //echo pg_last_error();
    header('location:index.php?pagina=cadastrarChamado&erroDB'.$erros);
//caso esteja tudo certo, procede com a inserção no banco de dados
}else{
	//insere o chamado no banco de dados
	$c = new Chamado();
	$c->setData($timestamp);
	$c->setOrigem($origem);
	$c->setPessoaId($pessoa_atendida);
	$c->setLogradouroId($logradouro_id);
	$c->setDescricao($descricao);
	$c->setEnderecoPrincipal($endereco_principal);
	$c->setLatitude($latitude);
	$c->setLongitude($longitude);
	$c->setAgenteId($id_usuario);
	$c->setPrioridade($prioridade);
	$c->setDistribuicao($distribuicao);
	$c->setNomePessoa($nome);

	
	if($id_chamado = $chamadodao->adicionar($c) !== false){

		$chamadodao->adicionarLog($id_usuario,$id_chamado,$dataAtual);

		header('location:index.php?pagina=cadastrarChamado&sucesso');
	}else
		//echo pg_last_error();
		header('location:index.php?pagina=cadastrarChamado&erroDB');
}
