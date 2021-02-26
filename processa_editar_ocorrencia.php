<?php
//inclui a conexao com o banco de dados
include 'database.php';


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
	if($possui_fotos == 't'){
		$possui_fotos = 'true';
		$pg_array = join(',',$base64_array).'}';

		$sql = $pdo->prepare("SELECT fotos FROM ocorrencia HWERE id_ocorrencia = :id_ocorrencia");
		$sql->bindValue("id_ocorrencia", $id_ocorrencia);
		$sql->execute();
		$string = $sql->fetch()['fotos'];

		$string = str_replace('}','',$string);

		$pg_array = $string.','.$pg_array;
	}else{
		$possui_fotos = 'false';
		$pg_array = '{'.join(',',$base64_array).'}';
	}
}else{
	if($possui_fotos == 't'){
		$possui_fotos = 'true';

		$sql = $pdo->prepare("SELECT fotos FROM ocorrencia WHERE id_ocorrencia = :id_ocorrencia");
		$sql->bindValue(":id_ocorrencia", $id_ocorrencia);
		$sql->execute();
		$string = $sql->fetch()['fotos'];
		
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
$logradouro_id = 'null';


if($endereco_principal == "Logradouro"){
	$sql = $pdo->prepare("SELECT * FROM endereco_logradouro WHERE id_logradouro = :id_logradouro");
	$sql->bindValue(":id_logradouro", $id_logradouro);
	$sql->execute();

//VERIFICA SE TEM O ENDEREÇO NO BD, SE NÃO TIVE INSERE
	if($sql->rowCount() == 0){
		$sql = $pdo->prepare("INSERT INTO endereco_logradouro (cep,cidade,bairro,loagraoudo,numero,referencia
								VALUES (:cep, :cidade, :bairro, :logradouro, :numero, :referencia)");
		$sql->bindValue(":cep", $cep);
		$sql->bindValue(":cidade", $cidade);
		$sql->bindValue(":bairro", $bairro);
		$sql->bindValue(":logradouro", $logradouro);
		$sql->bindValue(":numero", $numero);
		$sql->bindValue(":referencia", $referencia);
		$sql->execute();
		
		if(!$sql)
			$erros = $erros.'&logradouro';
			$sql = $pdo->prepare("SELECT * FROM endereco_logradouro WHERE logradouro = :logradouro AND numero = :numero");
			$sql->bindValue(":logradouro", $logradouro);
			$sql->bindValue(":numero", $numero);
			$sql->execute();
		if(!$sql)
			$erros = $erros.'&logradouro';
	}

	$linha = $sql->fetch();
	$logradouro_id = $linha['id_logradouro'];
	$longitude = 'null';
	$latitude = 'null';
}

//busca o agente informado no banco de dados
$sql = $pdo->prepare("SELECT * FROM usuario WHERE nome = :agente_principal");
$sql->bindValue(":agente_principal", $agente_principal);
$sql->execute();

if($sql){
	if($sql->rowCount() == 0){ //agente nao encontrado
		$erros = $erros.'&agente_principal';
	}else{ //agente encontrado, seleciona o id do mesmo
		$linha = $sql->fetch();
		$agente_principal = $linha['id_usuario'];
	}
}else//retorna erro caso nao consiga acessar o banco de dados
	$erros = $erros.'&agente_principal';

if(strlen($agente_apoio_1) > 0 && $agente_apoio_1 != null){ //se o agente foi informado, busca o mesmo no BD
	$sql = $pdo->prepare("SELECT * FROM usuario WHERE nome = :agente_apoio_1");
	$sql->bindValue(":agente_apoio_1", $agente_apoio_1);
	$sql->execute();

	if($sql){
		if($sql->rowCount() == 0){ //agente nao encontrado
			$erros = $erros.'&agente_apoio_1';
		}else{  //agente encontrado, seleciona o id do mesmo
			$linha = $sql->fetch();
			$agente_apoio_1 = $linha['id_usuario'];
		}
	}else //retorna erro caso nao consiga acessar o banco de dados
		$erros = $erros.'&agente_apoio_1';
}else //agente nao foi informado
	$agente_apoio_1 = 'null';

if(strlen($agente_apoio_2) > 0 && $agente_apoio_2 != null){ //se o agente foi informado, busca o mesmo no BD
	$sql = $pdo->prepare("SELECT * FROM usuario WHERE nome = :agente_apoio_2");
	$sql->bindValue(":agente_apoio_2", $agente_apoio_2);
	$sql->execute();
	if($sql){ //agente encontrado
		if($sql->rowCount() == 0){ //agente nao encontrado
			$erros = $erros.'&agente_apoio_2';
		}else{  //agente encontrado, seleciona o id do mesmo
			$linha = $sql->fetch();
			$agente_apoio_2 = $linha['id_usuario'];
		}
	}else //retorna erro caso nao consiga acessar o banco de dados
		$erros = $erros.'&agente_apoio_2';
}else //agente nao foi informado
	$agente_apoio_2 = 'null';


	
if(strlen($pessoa_atendida_1) > 0){ //se a pessoa foi informada, busca a mesma no BD 
	$sql = $pdo->prepare("SELECT * FROM pessoa WHERE nome = :pessoa_atendida_1");
	$sql->bindValue(":pessoa_atendida_1", $pessoa_atendida_1);
	$sql->execute();
	if($sql){
		if($sql->rowCount() == 0){ //pessoa nao encontrada
			$erros = $erros.'&pessoa_atendida_1';
		}else{  //pessoa encontrada, seleciona o id da mesma
			$linha = $sql->fetch();
			$pessoa_atendida_1 = $linha['id_pessoa'];
		}
	}else //erro no acesso ao BD
		$erros = $erros.'&pessoa_atendida_1';
}else //pessoa nao foi informada
	$pessoa_atendida_1 = 'null';

if(strlen($pessoa_atendida_2) > 0){ //se a pessoa foi informada, busca a mesma no BD
	$sql = $pdo->prepare("SELECT * FROM pessoa WHERE nome = :pessoa_atendida_2");
	$sql->bindValue(":pessoa_atendida_2", $pessoa_atendida_2);
	$sql->execute();
	if($sql){
		if($sql->rowCount() == 0){ //pessoa nao encontrada
			$erros = $erros.'&pessoa_atendida_2';
		}else{  //pessoa encontrada, seleciona o id da mesma
			$linha = $sql->fetch();
			$pessoa_atendida_2 = $linha['id_pessoa'];
		}
	}else //erro no acesso ao BD
		$erros = $erros.'&pessoa_atendida_2';
}else //pessoa nao foi informada
	$pessoa_atendida_2 = 'null';

if(strlen($chamado_id)==0)
	$chamado_id = 'null';

//caso ocorra algum erro na validacao, entao volta para a pagina e indica onde esta o erro
if(strlen($erros) > 0){
	header('location:index.php?pagina=editarOcorrencia&id='.$id_ocorrencia.$erros);
//caso esteja tudo certo, procede com a inserção no banco de dados
}else{
	//insere a ocorrencia no banco de dados
	$sql = $pdo->prepare("UPDATE ocorrencia SET
	chamado_id = $chamado_id, ocorr_endereco_principal = '$endereco_principal',
	ocorr_coordenada_latitude = $latitude, ocorr_coordenada_longitude = $longitude,
	ocorr_logradouro_id = '$logradouro_id', agente_principal = $agente_principal,
	agente_apoio_1 = $agente_apoio_1, agente_apoio_2 = $agente_apoio_2,
	data_ocorrencia = '$data_ocorrencia', 
	ocorr_titulo = '$titulo', ocorr_descricao = '$descricao', ocorr_origem = '$ocorr_origem',
	atendido_1 = $pessoa_atendida_1, atendido_2 = $pessoa_atendida_2,
	ocorr_cobrade = '$cobrade', ocorr_fotos = '$possui_fotos',
	ocorr_prioridade = '$prioridade', ocorr_analisado = '$analisado', ocorr_congelado = '$congelado',
	ocorr_encerrado = '$encerrado', usuario_criador = $id_criador, data_alteracao ='$dataAtual',
	ocorr_referencia =$id_ocorrencia, fotos = '$pg_array' WHERE id_ocorrencia = $id_ocorrencia;");
	$sql->execute();
	$cepbd = str_replace('-','',$cep);
	$sql2 = $pdo->prepare("UPDATE endereco_logradouro SET cep = '$cepbd', cidade = '$cidade', bairro = '$bairro', 
	logradouro = '$logradouro', numero = '$numero', referencia = '$referencia'");

	$sql2->execute();
	

	if(!$sql && !$sql2){
		header('location:index.php?pagina=editarOcorrencia&id='.$id_ocorrencia.'&erroDB');

	}else{
		//echo pg_last_error();
		header('location:index.php?pagina=exibirOcorrencia&id='.$id_ocorrencia.'&sucesso');
	}
}