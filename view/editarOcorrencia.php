<?php

require_once 'dao/EnderecoDaoPgsql.php';
require_once 'dao/OcorrenciaDaoPgsql.php';
require_once 'dao/UsuarioDaoPgsql.php';
require_once 'dao/PessoaDaoPgsql.php';

$enderecodao = New EnderecoDaoPgsql($pdo);
$pessoadao = new PessoaDaoPgsql($pdo);
$ocorrenciadao = new OcorrenciaDaoPgsql($pdo);
$usuariodao = new UsuarioDaoPgsql($pdo);

//PEGAR OS DADOS PRA IMPRIMIR NA TELA QUANDO DER ERROS NA EDIÇÃO DA OCORRENCIA
if(isset($_GET['cobrade']) || isset($_GET['agente_principal']) || isset($_GET['agente_apoio_1']) || isset($_GET['agente_apoio_2']) || isset($_GET['logradouro']) || isset($_GET['pessoa_atendida_1']) || isset($_GET['pessoa_atendida_2']) || isset($_GET['erroDB'])){

    $linha = $ocorrenciadao->buscaOcorrenciaUsuarioEndereco($_GET['id']);

    //verifica se o agente de apoio e as pessoas estão na ocorrencia 
    $queryagt1 = $usuariodao->findById($linha['agente_apoio_1']);

     if($queryagt1 !== false){
        $agente_apoio_1 = $queryagt1;
    }


    $queryagt2 = $usuariodao->findById($linha['agente_apoio_2']);
    if($queryagt2 !== false){
        $agente_apoio_2 = $queryagt2;
    }

    $queryopessoa1 = $pessoadao->buscarPeloID($linha['atendido_1']);

    if($querypessoa1 !== false){
        $pessoa_atendida_1 = $querypessoa1;
    }
    
    $querypessoa2 = $pessoadao->buscarPeloID($linha['atendido_2']);
    if($querypessoa2 !== false){
        $pessoa_atendida_2 = $querypessoa2;
    }
    $cobrade = str_split($linha['ocorr_cobrade']);
}else{
    $id_logradouro = $_POST['id_logradouro'];

}

if($_POST['id_ocorrencia']){
    $linhaOcorrencia = $ocorrenciadao->buscarPeloId($_POST['id_ocorrencia']);
}

if($_GET['id']){
    $linhaOcorrencia = $ocorrenciadao->buscarPeloId($_GET['id']);
}

if($linhaOcorrencia->getEnderecoPrincipal() == 'Logradouro'){
    $linhaEndereco = $enderecodao->buscarPeloId($linhaOcorrencia->getLogradouroid());
}else{
    $linhaEndereco = $enderecodao->buscarIdCoordenada($linhaOcorrencia->getIdCoordenada());
}

$string = $linhaOcorrencia->getFotos();

$barras = array("{","}");
$string = str_replace($barras,'',$string);

$fotos = explode(',', $string);
$agente_principal = $usuariodao->findById($linhaOcorrencia->getIdCriador());

if($linhaOcorrencia->getPossuiFotos() == false){
    $possui_fotos = 0;
}else{
    $possui_fotos = 1   ;
}

?>

<div class="container positioning">
<div class="jumbotron campo_cadastro">
    <form method="post" action="processa_editar_ocorrencia.php" enctype="multipart/form-data">
        <input name="id_logradouro" type="hidden" value="<?php echo $linhaOcorrencia->getLogradouroid() ?>">
        <input name="id_ocorrencia" type="hidden" value="<?php echo $linhaOcorrencia->getId(); echo $linha['id_ocorrencia']; ?>">
        <input type="hidden" name="chamado_id"  value="<?php echo $_POST['chamado_id']; ?>">
        <div class="box">
            <div class="row cabecalho">
                <div class="col-sm-6">
                    <nav class="texto-cabecalho">Estado de Santa Catarina</nav>
                    <nav class="texto-cabecalho">Prefeitura de Balneário Camboriú</nav>
                    <nav class="texto-cabecalho">Secretaria de segurança</nav>
                    <nav class="texto-cabecalho">Defesa Civil</nav>
                </div>
                <div class="col-sm-6">
                    <img src="images/balneario-camboriu.png" alt="prefeitura-balneario-camboriu" class="img-cabecalho">
                </div>
            </div>
            <h2 class="text-center">Registro de ocorrência</h2>
        <hr>
            <?php if(isset($_GET['erroDB'])){ ?>
            <div class="alert alert-danger" role="alert">
                Falha ao alterar ocorrencia.
            </div>
            <?php } ?>
            <div>
                Endereço principal: <span style="color:red;">*</span>
                <br>
                <label for="endereco_principal"></label>
                <select name="endereco_principal" class="form-control endereco-principal" ng-model="sel_endereco" ng-init="sel_endereco='<?php echo $linhaOcorrencia->getEnderecoPrincipal(); echo $linha['ocorr_endereco_principal']; ?>'" required>
                    <option value="Coordenada">Coordenada</option>
                    <option value="Logradouro">Logradouro</option>
                </select>
            </div>
            <?php if(isset($_GET['endereco_principal'])){ ?>
                <span class="alertErro">
                    Opção de endereço desconhecida.
                </span>
            <?php } ?>
            <div ng-show="sel_endereco == 'Coordenada'">
                <div class="row">
                    <div class="col-sm-4">
                        <span>Latitude: <span style="color:red;">*</span></span>
                        <input id="latitude" name="latitude" autocomplete="off" type="text" class="form-control"  value="<?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Coordenada'){ echo $linhaEndereco->getLatitude();} ?>" onchange="verificaLatLgn()">
                    </div>
                    <div class="col-sm-4">
                        Longitude: <span style="color:red;">*</span>
                        <input id="longitude" name="longitude" autocomplete="off" type="text" class="form-control"  value="<?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Coordenada'){ echo $linhaEndereco->getLatitude();} ?>">
                        
                    </div>
                    <div class="col-sm-4">
                        <br>
                        <button type="button" class="btn-default btn-small inline open-AddBookDialog" data-toggle="modal" data-id="map"><span class="glyphicon glyphicon-map-marker"></span></button>
                    </div>
                </div>
            </div>
            <div ng-show="sel_endereco == 'Logradouro'">
            <div class="row">
                    <div class="col-sm-4">
                        <span>CEP:</span>
                        <input id="cep" name="cep" type="text" class="form-control"  onchange="verificaCep(this.value)" value="<?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Logradouro'){ echo $linhaEndereco->getCep(); } ?>">
                        <span id="erroCep" class="alertErro hide">CEP inválido.</span>
                    </div>
                    <div class="col-sm-8">
                        <span>Cidade: </span><span style="color:red;">*</span>
                        <!--<input id="cidade" name="cidade" type="text" class="form-control">-->
                        <select id="cidade" name="cidade" class="form-control">
                            <option value="Balneário Camboriú">Balneário Camboriú</option>
                        </select>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-4">
                        <span>Bairro: <span style="color:red;">*</span>
                        <!--<input id="bairro" name="bairro" type="text" class="form-control">-->
                        <select id="bairro" name="bairro" class="form-control" selec>
                            <option <?php if($linhaEndereco->getBairro() == 'Centro'){ echo 'selected';} ?> value="Centro">Centro</option>
                            <option <?php if($linhaEndereco->getBairro() == 'Nações'){ echo 'selected'; }?> value="Nações">Nações</option>
                            <option <?php if($linhaEndereco->getBairro() == 'Pioneiros'){ echo 'selected';} ?> value="Pioneiros">Pioneiros</option>
                            <option <?php if($linhaEndereco->getBairro() == 'Estados'){ echo 'selected'; }?> value="Estados">Estados</option>
                            <option <?php if($linhaEndereco->getBairro() == 'Ariribá'){ echo 'selected'; }?> value="Ariribá">Ariribá</option>
                            <option <?php if($linhaEndereco->getBairro() == 'Praia dos Amores'){ echo 'selected';} ?> value="Praia dos Amores">Praia dos Amores</option>
                            <option <?php if($linhaEndereco->getBairro() == 'Municípios'){ echo 'selected'; }?> value="Municípios">Municípios</option>
                            <option <?php if($linhaEndereco->getBairro() == 'Vila Real'){ echo 'selected'; }?> value="Vila Real">Vila Real</option>
                            <option <?php if($linhaEndereco->getBairro() == 'Jardim Iate Clube'){ echo 'selected';} ?> value="Jardim Iate Clube">Jardim Iate Clube</option>
                            <option <?php if($linhaEndereco->getBairro() == 'Várzea do Ranchinho'){ echo 'selected'; }?> value="Várzea do Ranchinho">Várzea do Ranchinho</option>
                            <option <?php if($linhaEndereco->getBairro() == 'Barra'){ echo 'selected'; }?> value="Barra">Barra</option>
                            <option <?php if($linhaEndereco->getBairro() == 'Parque Bandeirantes'){ echo 'selected'; }?> value="Parque Bandeirantes">Parque Bandeirantes</option>
                            <option <?php if($linhaEndereco->getBairro() == 'Nova Esperança'){ echo 'selected';} ?> value="Nova Esperança">Nova Esperança</option>
                            <option <?php if($linhaEndereco->getBairro() == 'São Judas Tadeu'){ echo 'selected'; }?> value="São Judas Tadeu">São Judas Tadeu</option>
                            <option <?php if($linhaEndereco->getBairro() == 'Região das Praias'){ echo 'selected'; }?> value="Região das Praias">Região das Praias</option>
                        </select>
                    </div>
                    <div class="col-sm-8">
                        Logradouro: <span style="color:red;">*</span>
                        <input id="logradouro" name="logradouro" type="text" class="form-control" ng-required="sel_endereco=='Logradouro'" value="<?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Logradouro'){ echo $linhaEndereco->getLogradouro(); } ?>">
                        <?php if(isset($_GET['logradouro'])){ ?>
                            <span class="alertErro">Erro ao cadastrar logradouro.</span>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <span>Número: </span><span style="color:red;">*</span>
                        <input id="complemento" name="complemento" type="text" class="form-control" ng-required="sel_endereco=='Logradouro'" value="<?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Logradouro'){ echo $linhaEndereco->getNumero(); } ?>">
                    </div>
                    <div class="col-sm-8">
                        <span>Referência: </span>
                        <input name="referencia" type="text" class="form-control" value="<?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Logradouro'){ echo $linhaEndereco->getReferencia(); } ?>">
                    </div>
                </div>
            </div>
        <hr>
            <div>
                Agente principal: <span style="color:red;">*</span>
                <input id="agente_principal" name="agente_principal" autocomplete="off" type="text" class="form-control" value="<?php  echo $agente_principal->getNome() ;echo $linha['nome']; ?>" onkeyup="showResult(this.value,this.id)" required>

                <div class="autocomplete" id="livesearchagente_principal"></div>
            </div>
            <?php if(isset($_GET['agente_principal'])){ ?>
                    <span class="alertErro">
                        Agente não encontrado ou informado incorretamente.
                    </span>
                <?php } ?>
            <div>
                Agente de apoio 1:
                <input id="agente_apoio_1" name="agente_apoio_1" autocomplete="off" type="text" class="form-control" value="<?php echo $_POST['agente_apoio1']; echo $agente_apoio_1['nome']; ?>" onkeyup="showResult(this.value,this.id)">
                <div class="autocomplete" id="livesearchagente_apoio_1"></div>
            </div>
            <?php if(isset($_GET['agente_apoio_1'])){ ?>
                <span class="alertErro">
                    Agente não encontrado ou informado incorretamente.
                </span>
            <?php } ?>
            <div>
                Agente de apoio 2:
                <input id="agente_apoio_2" name="agente_apoio_2" autocomplete="off" type="text" class="form-control" value="<?php echo $_POST['agente_apoio2']; echo $agente_apoio_2['nome'] ?>" onkeyup="showResult(this.value,this.id)">
                <div class="autocomplete" id="livesearchagente_apoio_2"></div>
            </div>
            <?php if(isset($_GET['agente_apoio_2'])){ ?>
                <span class="alertErro">
                    Agente não encontrado ou informado incorretamente.
                </span>
            <?php } ?>
        <hr>
            <div>
                <span>Data de ocorrência: <span style="color:red;">*</span></span>
                <br>
                <input id="data_ocorrencia" name="data_ocorrencia" autocomplete="off" type="date" class="form-control data" value="<?php echo $linhaOcorrencia->getData(); echo $linha['data_ocorrencia']; ?>" max="<?php echo date('Y-m-d'); ?>" required onchange="verificaData()">
            </div>
            <span id="erroData" class="alertErro hide">Data de lançamento inválida.</span>
            <div>
                Titulo:
                <textarea id="titulo" name="titulo" class="form-control titulobox" cols="30" rows="2" maxlength="120" ng-model="tituloVal" ng-init="tituloVal='<?php echo $linhaOcorrencia->getTitulo();echo $linha['ocorr_titulo'];?>'"?></textarea>
                <span class="char-count">{{tituloVal.length || 0}}/120</span>
            </div>
            <div>
                Descrição:
                <textarea id="descricao" name="descricao" class="form-control" cols="30" rows="5" maxlength = "100" ng-model="descricaoVal" ng-init="descricaoVal='<?php echo $linhaOcorrencia->getDescricao(); echo $linha['ocorr_descricao'];?>'"></textarea>
            </div>
            <div>
                <?php if($_POST['ocorr_origem'] == ""){ ?>
                    Origem: <span style="color:red;">*</span>
                    <select name="ocorr_origem" class="form-control" ng-model="sel_origem" ng-init="sel_origem=''" required>
                    <option value="Telefone Base">Telefone Base</option>
                    <option value="Ouvidoria">Ouvidoria</option>
                    <option value="199">199</option>
                    <option value="Secretaria de Obras">Secretaria de Obras</option>
                    <option value="Secretaria do Meio Ambiente">Secretaria do Meio Ambiente</opntion>
                    <option value="Secretaria da Saúde">Secretaria da Saúde</option>
                    <option value="Outro">Outro (Especificar)</option>
                    </select>

                    <div ng-show="sel_origem == 'Outro'">
                    Descrição Origem:
                    <input type="text" name="ocorr_origem2" class="form-control">
                    </div>
                <?php } else { ?>
                    <input name="ocorr_origem" type="hidden" class="form-control" value="<?php echo $_POST['ocorr_origem']; ?>">
                <?php } ?>
            </div>
        <hr>
            <div>
                Pessoa atendida 1:
                <br>
                <input id="pessoa_atendida_1" name="pessoa_atendida_1" autocomplete="off" type="text" class="form-control inline" style="width:93%;" value="<?php echo $linhaOcorrencia->getPessoa1(); echo $pessoa_atendida_1; ?>" onkeyup="showResult(this.value,this.id)">
                <button type="button" class="btn-default btn-small inline" data-toggle="modal" data-target="#pessoasModal"><span class="glyphicon glyphicon-plus"></span></button>
                <div class="autocomplete" id="livesearchpessoa_atendida_1"></div>
                <div id="resultpessoa_atendida_1"></div>
            </div>
            <?php if(isset($_GET['pessoa_atendida_1'])){ ?>
                <span class="alertErro">
                    Pessoa não encontrada ou informada incorretamente.
                </span>
            <?php } ?>
            <div>
                Pessoa atendida 2:
                <br>
                <input id="pessoa_atendida_2" name="pessoa_atendida_2" autocomplete="off" type="text" class="form-control inline" style="width:93%;" value="<?php echo $linhaOcorrencia->getPessoa2(); echo $pessoa_atendida_2['nome']; ?>" onkeyup="showResult(this.value,this.id)">
                <button type="button" class="btn-default btn-small inline open-AddBookDialog" data-toggle="modal" data-id="pessoa_atendida_2"><span class="glyphicon glyphicon-plus"></span></button>
                <div class="autocomplete" id="livesearchpessoa_atendida_2"></div>
                <div id="resultpessoa_atendida_2"></div>
            </div>
            <?php if(isset($_GET['pessoa_atendida_2'])){ ?>
                <span class="alertErro">
                    Pessoa não encontrada ou informada incorretamente.
                </span>
            <?php } ?>
        <hr>
        <div>
                Cobrade: 
                <?php if(isset($_GET['cobrade'])){ ?>
                    <br><span class="alertErro">
                        Cobrade incorreto.
                    </span>
                <?php }?>
                <div class="cobrade">
                    Categoria: <span style="color:red;">*</span><br>
                    <select name="cobrade_categoria" class="form-control cobrade-sub" ng-model="categoria" ng-init="categoria='<?php echo $linhaOcorrencia->getCobrade()[0]; ?>'">
                        <option value="1">Naturais</option>
                        <option value="2">Tecnológicos</option>
                        <option value="0">Sem cobrade</option>
                    </select>
                    Grupo: <span style="color:red;" ng-hide="categoria == 0">*</span><br>
                    <select name="cobrade_grupo" class="form-control cobrade-sub" ng-model="grupo" ng-disabled="categoria == 0" ng-init="grupo='<?php echo $linhaOcorrencia->getCobrade()[1]; ?>'">
                        <option ng-if="categoria==1" value="1">Geológico</option>
                        <option ng-if="categoria==1" value="2">Hidrológico</option>
                        <option ng-if="categoria==1" value="3">Meteorológico</option>
                        <option ng-if="categoria==1" value="4">Climatólogo</option>
                        <option ng-if="categoria==1" value="5">Biológico</option>
                        <option ng-if="categoria==2" value="1">Desastres Relacionados a Substâncias radioativas</option>
                        <option ng-if="categoria==2" value="2">Desastres Relacionados a Produtos Perigosos</option>
                        <option ng-if="categoria==2" value="3">Desastres Relacionados a Incêndios Urbanos</option>
                        <option ng-if="categoria==2" value="4">Desastres relacionados a obras civis</option>
                        <option ng-if="categoria==2" value="5">Desastres relacionados a transporte de passageiros e cargas não perigosas</option>
                    </select>
                    Subgrupo: <span style="color:red;" ng-hide="grupo == 0">*</span><br>
                    <select name="cobrade_subgrupo" class="form-control cobrade-sub" ng-model="subgrupo" ng-disabled="grupo == 0" ng-init="subgrupo='<?php echo $linhaOcorrencia->getCobrade()[2]; ?>'">
                        <option ng-if="grupo==1&&categoria==1" value="1">Terremoto</option>
                        <option ng-if="grupo==1&&categoria==1" value="2">Emanação vulcânica</option>
                        <option ng-if="grupo==1&&categoria==1" value="3">Movimento de massa</option>
                        <option ng-if="grupo==1&&categoria==1" value="4">Erosão</option>
                        <option ng-if="grupo==2&&categoria==1" value="1">Inundações</option>
                        <option ng-if="grupo==2&&categoria==1" value="2">Enxurradas</option>
                        <option ng-if="grupo==2&&categoria==1" value="3">Alagamentos</option>
                        <option ng-if="grupo==3&&categoria==1" value="1">Sistemas de Grande Escala/Escala Regional</option>
                        <option ng-if="grupo==3&&categoria==1" value="2">Tempestades</option>
                        <option ng-if="grupo==3&&categoria==1" value="3">Temperaturas Extremas</option>
                        <option ng-if="grupo==4&&categoria==1" value="1">Seca</option>
                        <option ng-if="grupo==5&&categoria==1" value="1">Epidemias</option>
                        <option ng-if="grupo==5&&categoria==1" value="2">Infestações/Pragas</option>
                        <option ng-if="grupo==1&&categoria==2" value="1">Desastres siderais com riscos radioativos</option>
                        <option ng-if="grupo==1&&categoria==2" value="2">Desastres com substâncias e equipamentos radioativos de uso em pesquisas, indústrias e usinas nucleares</option>
                        <option ng-if="grupo==1&&categoria==2" value="3">Desastres relacionados com riscos de intensa poluição ambiental provocada por resíduos radioativos</option>
                        <option ng-if="grupo==2&&categoria==2" value="1">Desastres em plantas e distritos industriais, parques e armazenamentos com extravasamento de produtos perigosos</option>
                        <option ng-if="grupo==2&&categoria==2" value="2">Desastres relacionados à contaminação da água</option>
                        <option ng-if="grupo==2&&categoria==2" value="3">Desastres Relacionados a Conflitos Bélicos</option>
                        <option ng-if="grupo==2&&categoria==2" value="4">Desastres relacionados a transporte de produtos perigosos</option>
                        <option ng-if="grupo==3&&categoria==2" value="1">Incêndios urbanos</option>
                        <option ng-if="grupo==4&&categoria==2" value="1">Colapso de edificações</option>
                        <option ng-if="grupo==4&&categoria==2" value="2">Rompimento/colapso de barragens</option>
                        <option ng-if="grupo==5&&categoria==2" value="1">Transporte rodoviário</option>
                        <option ng-if="grupo==5&&categoria==2" value="2">Transporte ferroviário</option>
                        <option ng-if="grupo==5&&categoria==2" value="3">Transporte aéreo</option>
                        <option ng-if="grupo==5&&categoria==2" value="4">Transporte marítimo</option>
                        <option ng-if="grupo==5&&categoria==2" value="5">Transporte aquaviário</option>
                    </select>
                    Tipo: <span style="color:red;" ng-hide="subgrupo == 0">*</span><br>
                    <select name="cobrade_tipo" class="form-control cobrade-sub" ng-model="tipo" ng-disabled="subgrupo==0" ng-init="tipo='<?php echo $linhaOcorrencia->getCobrade()[3]; ?>'">
                        <option ng-if="subgrupo==1&&grupo==1&&categoria==1" value="1">Tremor de terra</option>
                        <option ng-if="subgrupo==1&&grupo==1&&categoria==1" value="2">Tsunami</option>
                        <option ng-if="subgrupo==2&&grupo==1&&categoria==1" value="0"></option>
                        <option ng-if="subgrupo==3&&grupo==1&&categoria==1" value="1">Quedas, Tombamentos e rolamentos</option>
                        <option ng-if="subgrupo==3&&grupo==1&&categoria==1" value="2">Deslizamentos</option>
                        <option ng-if="subgrupo==3&&grupo==1&&categoria==1" value="3">Corridas de Massa</option>
                        <option ng-if="subgrupo==3&&grupo==1&&categoria==1" value="4">Subsidências e colapsos</option>
                        <option ng-if="subgrupo==4&&grupo==1&&categoria==1" value="1">Erosão Costeira/Marinha</option>
                        <option ng-if="subgrupo==4&&grupo==1&&categoria==1" value="2">Erosão de Margem Fluvial</option>
                        <option ng-if="subgrupo==4&&grupo==1&&categoria==1" value="3">Erosão Continental</option>
                        <option ng-if="grupo==2&&categoria==1" value="0"></option>
                        <option ng-if="subgrupo==1&&grupo==3&&categoria==1" value="1">Ciclones</option>
                        <option ng-if="subgrupo==1&&grupo==3&&categoria==1" value="2">Frentes Frias/Zonas de Convergência</option>
                        <option ng-if="subgrupo==2&&grupo==3&&categoria==1" value="1">Tempestade Local/Convectiva</option>
                        <option ng-if="subgrupo==3&&grupo==3&&categoria==1" value="1">Onda de Calor</option>
                        <option ng-if="subgrupo==3&&grupo==3&&categoria==1" value="2">Onda de Frio</option>
                        <option ng-if="subgrupo==1&&grupo==4&&categoria==1" value="1">Estiagem</option>
                        <option ng-if="subgrupo==1&&grupo==4&&categoria==1" value="2">Seca</option>
                        <option ng-if="subgrupo==1&&grupo==4&&categoria==1" value="3">Incêndio Florestal</option>
                        <option ng-if="subgrupo==1&&grupo==4&&categoria==1" value="4">Baixa Humidade do Ar</option>
                        <option ng-if="subgrupo==1&&grupo==5&&categoria==1" value="1">Doenças infecciosas virais </option>
                        <option ng-if="subgrupo==1&&grupo==5&&categoria==1" value="2">Doenças infecciosas bacterianas</option>
                        <option ng-if="subgrupo==1&&grupo==5&&categoria==1" value="3">Doenças infecciosas parasíticas</option>
                        <option ng-if="subgrupo==1&&grupo==5&&categoria==1" value="4">Doenças infecciosas fúngicas</option>
                        <option ng-if="subgrupo==2&&grupo==5&&categoria==1" value="1">Infestações de animais</option>
                        <option ng-if="subgrupo==2&&grupo==5&&categoria==1" value="2"> Infestações de algas</option>
                        <option ng-if="subgrupo==2&&grupo==5&&categoria==1" value="3">Outras Infestações</option>
                        <option ng-if="subgrupo==1&&grupo==1&&categoria==2" value="1">Queda de satélite (radionuclídeos)</option>
                        <option ng-if="subgrupo==2&&grupo==1&&categoria==2" value="1">ontes radioativas em processos de produção</option>
                        <option ng-if="subgrupo==3&&grupo==1&&categoria==2" value="1">Outras fontes de liberação de radionuclídeos para o meio ambiente</option>
                        <option ng-if="subgrupo==1&&grupo==2&&categoria==2" value="1">Liberação de produtos químicos para a atmosfera causada por explosão ou incêndio</option>
                        <option ng-if="subgrupo==2&&grupo==2&&categoria==2" value="1">Liberação de produtos químicos nos sistemas de água potável</option>
                        <option ng-if="subgrupo==2&&grupo==2&&categoria==2" value="2">Derramamento de produtos químicos em ambiente lacustre, fluvial, marinho e aquíferos</option>
                        <option ng-if="subgrupo==3&&grupo==2&&categoria==2" value="1">Liberação produtos químicos e contaminação como conseqüência de ações militares.</option>
                        <option ng-if="subgrupo==4&&grupo==2&&categoria==2" value="1">Transporte rodoviário</option>
                        <option ng-if="subgrupo==4&&grupo==2&&categoria==2" value="2">Transporte ferroviário</option>
                        <option ng-if="subgrupo==4&&grupo==2&&categoria==2" value="3">Transporte aéreo</option>
                        <option ng-if="subgrupo==4&&grupo==2&&categoria==2" value="4">Transporte dutoviário</option>
                        <option ng-if="subgrupo==4&&grupo==2&&categoria==2" value="5">Transporte marítimo</option>
                        <option ng-if="subgrupo==4&&grupo==2&&categoria==2" value="6">Transporte aquaviário</option>
                        <option ng-if="subgrupo==1&&grupo==3&&categoria==2" value="1">Incêndios em plantas e distritos industriais, parques e depósitos</option>
                        <option ng-if="subgrupo==1&&grupo==3&&categoria==2" value="2">Incêndios em aglomerados residenciais</option>
                        <option ng-if="grupo==4&&categoria==2" value="0"></option>
                        <option ng-if="grupo==5&&categoria==2" value="0"></option>
                    </select>
                    Subtipo: <span style="color:red;" ng-hide="tipo==0 || categoria==2">*</span><br>
                    <select name="cobrade_subtipo" class="form-control cobrade-sub" ng-model="subtipo" ng-disabled="tipo==0 || categoria==2" ng-init="subtipo='<?php echo $linhaOcorrencia->getCobrade()[4];?>'">
                        <option ng-if="subgrupo==1&&grupo==1&&categoria==1" value="0"></option>
                        <option ng-if="subgrupo==2&&grupo==1&&categoria==1" value="0"></option>
                        <option ng-if="tipo==1&&subgrupo==3&&grupo==1&&categoria==1" value="1">Blocos</option>
                        <option ng-if="tipo==1&&subgrupo==3&&grupo==1&&categoria==1" value="2">Lascas</option>
                        <option ng-if="tipo==1&&subgrupo==3&&grupo==1&&categoria==1" value="3">Matacões</option>
                        <option ng-if="tipo==1&&subgrupo==3&&grupo==1&&categoria==1" value="4">Lajes</option>
                        <option ng-if="tipo==2&&subgrupo==3&&grupo==1&&categoria==1" value="1">Deslizamentos de solo e ou rocha</option>
                        <option ng-if="tipo==3&&subgrupo==3&&grupo==1&&categoria==1" value="1">Solo/Lama</option>
                        <option ng-if="tipo==3&&subgrupo==3&&grupo==1&&categoria==1" value="2">Rocha/Detrito</option>
                        <option ng-if="tipo==4&&subgrupo==3&&grupo==1&&categoria==1" value="0"></option>
                        <option ng-if="tipo==1&&subgrupo==4&&grupo==1&&categoria==1" value="0"></option>
                        <option ng-if="tipo==2&&subgrupo==4&&grupo==1&&categoria==1" value="0"></option>
                        <option ng-if="tipo==3&&subgrupo==4&&grupo==1&&categoria==1" value="1">Laminar</option>
                        <option ng-if="tipo==3&&subgrupo==4&&grupo==1&&categoria==1" value="2">Ravinas</option>
                        <option ng-if="tipo==3&&subgrupo==4&&grupo==1&&categoria==1" value="3">Boçorocas</option>
                        <option ng-if="grupo==2&&categoria==1" value="0"></option>
                        <option ng-if="tipo==1&&subgrupo==1&&grupo==3&&categoria==1" value="1">Ventos Costeiros (Mobilidade de Dunas)</option>
                        <option ng-if="tipo==1&&subgrupo==1&&grupo==3&&categoria==1" value="2">Marés de Tempestade (Ressacas)</option>
                        <option ng-if="tipo==2&&subgrupo==1&&grupo==3&&categoria==1" value="0"></option>
                        <option ng-if="tipo==1&&subgrupo==2&&grupo==3&&categoria==1" value="1">Tornados</option>
                        <option ng-if="tipo==1&&subgrupo==2&&grupo==3&&categoria==1" value="2">Tempestade de Raios</option>
                        <option ng-if="tipo==1&&subgrupo==2&&grupo==3&&categoria==1" value="3">Granizo</option>
                        <option ng-if="tipo==1&&subgrupo==2&&grupo==3&&categoria==1" value="4">Chuvas Intensas</option>
                        <option ng-if="tipo==1&&subgrupo==2&&grupo==3&&categoria==1" value="5">Vendaval</option>
                        <option ng-if="tipo==1&&subgrupo==3&&grupo==3&&categoria==1" value="0"></option>
                        <option ng-if="tipo==2&&subgrupo==3&&grupo==3&&categoria==1" value="1">Friagem</option>
                        <option ng-if="tipo==2&&subgrupo==3&&grupo==3&&categoria==1" value="2">Geadas</option>
                        <option ng-if="tipo==1&&subgrupo==1&&grupo==4&&categoria==1" value="0"></option>
                        <option ng-if="tipo==2&&subgrupo==1&&grupo==4&&categoria==1" value="0"></option>
                        <option ng-if="tipo==3&&subgrupo==1&&grupo==4&&categoria==1" value="1">Incêndios em Parques, Áreas de Proteção Ambiental e Áreas de Preservação Permanente Nacionais, Estaduais ou Municipais</option>
                        <option ng-if="tipo==3&&subgrupo==1&&grupo==4&&categoria==1" value="2">Incêndios em áreas não protegidas, com reflexos na qualidade do ar</option>
                        <option ng-if="tipo==4&&subgrupo==1&&grupo==4&&categoria==1" value="0"></option>
                        <option ng-if="subgrupo==1&&grupo==5&&categoria==1" value="0"></option>
                        <option ng-if="tipo==1&&subgrupo==2&&grupo==5&&categoria==1" value="0"></option>
                        <option ng-if="tipo==2&&subgrupo==2&&grupo==5&&categoria==1" value="1">Marés vermelhas</option>
                        <option ng-if="tipo==2&&subgrupo==2&&grupo==5&&categoria==1" value="2">Ciano bactérias em reservatórios</option>
                        <option ng-if="tipo==3&&subgrupo==2&&grupo==5&&categoria==1" value="0"></option>
                        <option ng-if="categoria==2" value="0"></option>
                    </select>
                </div>
            </div>
            <br>
            <br>     
            <div>
                Fotos:
                <input name="possui_fotos" type="hidden" value="<?php echo $possui_fotos; ?>">
                <?php if($linhaOcorrencia->getPossuiFotos()){ ?>
                    <div class="box">
                        <div id="myCarousel" class="carousel slide limite" data-ride="carousel">
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                <?php $i = 1; while($i < sizeof($fotos)){ ?>
                                    <li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>"></li>
                                <?php $i+=1; } ?>
                            </ol>
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                                <div class="item active">
                                <?php $i=0; echo '<button type="button" class="btn btn-danger" id="'.$i.'"  value="idFotos'.$i.'" style=" margin-left: 50%; z-index:1;" onclick="modalFoto(this.value,this.id)">&times;</button>' ?>
                                    <img src="data:image/png;base64,<?php echo $fotos[0]; ?>" alt="img1" style="width:100%;">
                                </div>
                                <?php $i = 1; while($i < sizeof($fotos)){ ?>
                                    <div class="item">
                                    <div><?php echo '<button type="button" class="btn btn-danger" id="'.$i.'"  value="idFotos'.$i.'" style="position:absolute; margin-left: 50%; z-index:1;" onclick="modalFoto(this.value,this.id)">&times;</button>' ?></div>
                                        <img src="data:image/png;base64,<?php echo $fotos[$i]; ?>" alt="img<?php echo $i; ?>" style="width:100%;">
                                    </div>
                                <?php $i+=1; } ?>
                                
                            </div>
                            <!-- Left and right controls -->
                            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                Adicionar fotos:
                <input id="imgInp" name="files[]" type="file" multiple="multiple" accept="image/png,image/jpeg">
                <div class="gallery"></div>
            </div>
        <hr>
            <div>
                Prioridade: <span style="color:red;">*</span>
                <label for="prioridade"></label>
                <select name="prioridade" class="form-control" style="width:30%;" required ng-model="prioridade" ng-init="prioridade='<?php echo $linhaOcorrencia->getPrioridade(); echo $linha['ocorr_prioridade']; ?>'">
                    <option value="Baixa">Baixa</option>
                    <option value="Média">Média</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>
            <?php if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2){ ?>
                <div class="row">
                    <div class="col-sm-4">
                        <span>Analisado: <span style="color:red;">*</span></span><br>
                        <div style="display:inline;">
                            <label class="radio-inline">
                                <input type="radio" value="true" id="analisado" name="analisado" <?php echo ($linhaOcorrencia->getAnalisado() == 1) ? 'checked':''; ?>>Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="false" id="analisado" name="analisado" <?php echo ($linhaOcorrencia->getAnalisado() == 1) ? '':'checked'; ?>>Não
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <span>Congelado: <span style="color:red;">*</span></span><br>
                        <div>
                            <label class="radio-inline">
                                <input type="radio" value="true" name="congelado" <?php echo ($linhaOcorrencia->getCongelado() == 1) ? 'checked':''; ?>>Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="false" name="congelado" <?php echo ($linhaOcorrencia->getCongelado() == 1) ? '':'checked'; ?>>Não
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <span>Encerrado: <span style="color:red;">*</span></span><br>
                        <div>
                            <label class="radio-inline">
                                <input type="radio" value="true" name="encerrado" <?php echo ($linhaOcorrencia->getEncerrado() == 1) ? 'checked':''; ?>>Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="false" name="encerrado" <?php echo ($linhaOcorrencia->getEncerrado() == 1) ? '':'checked'; ?>>Não
                            </label>
                        </div>
                    </div>
                </div>
            <?php }else{ ?>

                <p>Status</p>
                <nav>
                    <input type="hidden" name="analisado" value="<?php echo ($linhaOcorrencia->getAnalisado() == true) ? "true":"false"; ?>">
                    Analisado: <span><?php echo ($linhaOcorrencia->getAnalisado() == true) ? 'Sim':'Não'; ?></span>
                </nav>
                <nav>
                    <input type="hidden" name="congelado" value="<?php echo ($linhaOcorrencia->getCongelado() == true) ? "true":"false";  ?>">
                    Congelado: <span><?php echo ($linhaOcorrencia->getCongelado() == true) ? 'Sim':'Não'; ?></span>
                </nav>
                <nav>
                    <input type="hidden" name="encerrado" value="<?php echo ($linhaOcorrencia->getEncerrado() == true) ? "true":"false";  ?>">
                    Encerrado: <span><?php echo ($linhaOcorrencia->getEncerrado() == true) ? 'Sim':'Não'; ?></span>
                </nav>
            <?php } ?>
            <br>
        </div>
        <div class="btn-salvar-editarOcorrencia">
        <input type="submit" class="btn btn-default btn-md" value="Salvar">
        </div>
    </form>
    <div class="modal fade " style="position: absolute; top:250%; left:20%" id="excluirModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="btnFecharModalFoto" onclick="fecharModalFoto()">&times;</button>
                    <h5 class="modal-title">Excluir foto</h5>
                </div>
                    <div class="modal-body">
                        <nav>
                            <div class="row">
                                <div class="col-sm-12">
                                    <textarea id="motivo" name="motivo" class="form-control" cols="10" rows="3" maxlength="255" readonly required>Deseja mesmo excluir esta foto?</textarea>
                                </div>
                                <input id="pegarIdFoto" class="hidden"> 
                            </div>
                        </nav>
                    </div>
                    <div class="modal-footer" style="display: flex; justify-content:center">
                        <form method="POST" action="excluirFotoOcorrencia.php">
                            <input class="hidden" name="id_ocorrencia" value="<?php echo $linhaOcorrencia->getId(); ?>">
                            <input id="idFotoParaExcluir" class="hidden" name="idFotoExcluir">
                            <input type="submit" class="btn btn-default btn-success" name="sim" onclick="excluirFoto()" value="Sim">
                        </form>
                    <input class="btn btn-default btn-danger" style="width: 50px; margin-left:10px" onclick="fecharModalFoto()" value="Não">
                    </div>
            </div>
        </div>
    </div> 

    <div class="modal fade" id="pessoasModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Cadastrar pessoa</h5>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <nav>
                            <input id="id_pessoa" type="hidden" value="">
                            <div class="row">
                                <div class="col-sm-12">
                                    Nome: <span style="color:red;">*</span>
                                    <input id="nome_pessoa" name="nome_pessoa" type="text" class="form-control" onchange="verificaNome(this.value)">
                                </div>
                            </div>   
                            <span id="erroNome" class="alertErro hide">Nome inválido.</span>
                            <div class="row">
                                <div class="col-sm-6">
                                    CPF:
                                    <input id="cpf_pessoa" name="cpf_pessoa" type="text" class="form-control" maxlength="11" onchange="verificaCpf(this.value)">
                                    <span id="erroCpf" class="alertErro hide">CPF inválido.</span>
                                </div>
                                <div class="col-sm-6">
                                    Outros documentos:
                                    <input id="outros_documentos" name="outros_documentos" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    Celular: 
                                    <input id="celular_pessoa" name="celular_pessoa" type="text" class="form-control" pattern="\([0-9]{2}\)[\s][0-9]{4,5}-[0-9]{4}" title="(XX) XXXXX-XXXX" onchange="verificaCelular(this.value)">
                                    <span id="erroCelular" class="alertErro hide">Celular inválido.</span>
                                </div>
                                <div class="col-sm-6">
                                    Telefone: 
                                    <input id="telefone_pessoa" name="telefone_pessoa" type="text" class="form-control" pattern="\([0-9]{2}\)\s[0-9]{4}\-[0-9]{4}" title="(XX) XXXX-XXXX" onchange="verificaTelefone(this.value)">
                                    <span id="erroTelefone" class="alertErro hide">Telefone inválido.</span>
                                </div>
                            </div>
                            <span id="erroTelefone" class="alertErro hide">Telefone inválido.</span>
                            <div class="form-group">
                                Email:
                                <input id="email_pessoa" name="email_pessoa" type="email" class="form-control" onchange="verificaEmail(this.value)">
                            </div>
                            <span id="erroEmail" class="alertErro hide">Email inválido.</span>
                        </nav>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="submitFormData" onclick="SubmitFormData()">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="map" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Mapa</h5>
                </div>
                <div class="modal-body">
                    <div id="googleMap" style="width:100%;height:400px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submitFormData" onclick="SubmitFormData()" data-dismiss="modal">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>