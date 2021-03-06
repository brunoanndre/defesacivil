<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </html><link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <title>Document</title>
</head>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
</body>


<?php
    include 'database.php';
    require_once 'dao/UsuarioDaoPgsql.php';

    $usuariodao = new UsuarioDaoPgsql($pdo);

    $id = $_SESSION['id_usuario'];

    $agente_principal = $usuariodao->findById($id);

?>

<div class="container positioning">
<div class="jumbotron campo_cadastro">
    <form method="post" action="processa_cadastrar_ocorrencia.php" enctype="multipart/form-data" onsubmit="return validarFormCadastroOcorrencia()">

        <?php if(isset($_GET['erroDB'])){ ?>
            <div class="alert alert-danger" role="alert">Falha ao cadastrar ocorrencia.</div>
        <?php } ?>
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
            <h3 class="text-center">Registro de ocorrência</h3>
        <?php if(isset($_POST['id_chamado'])){ ?>
        <hr>
            <div>
                <label>Número do chamado:</label>
                <input name="id_chamado" type="hidden" class="form-control" style="width:25%;" value="<?php echo $_POST['id_chamado']; ?>" pattern="[0-9]+" title="Apenas números">
                <span><?php echo $_POST['id_chamado']; ?></span>
            </div>
            <?php } ?>
        <hr>
            <div>
                <label>Localizar por:</label> <span style="color:red;">*</span>
                <br>
                <label for="endereco_principal"></label>
                <select name="endereco_principal" class="form-control endereco-principal" ng-model="sel_endereco" ng-init="sel_endereco='<?php if(isset($_POST['endereco_principal'])){echo $_POST['endereco_principal'];}else{echo 'Logradouro';} ?>'" required>
                    <option value="Logradouro">Logradouro</option>
                    <option value="Coordenada">Coordenada</option>
                </select>
            </div>
            <div ng-show="sel_endereco == 'Coordenada'">
                <div class="row">
                    <div class="col-sm-4">
                       <label>Latitude:</label> <span style="color:red;">*</span>
                        <input id="latitude" name="latitude" type="text" class="form-control" value="<?php echo $_POST['latitude']; ?>" pattern="[-+]?\d*\.?\d*" title="Apenas números, separados por ponto" ng-required="sel_endereco=='Coordenada'">
                    </div>
                    <div class="col-sm-4">
                        <label>Longitude:</label> <span style="color:red;">*</span>
                        <input id="longitude" name="longitude" type="text" class="form-control" value="<?php echo $_POST['longitude']; ?>" pattern="[-+]?\d*\.?\d*" title="Apenas números, separados por ponto" ng-required="sel_endereco=='Coordenada'">
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
                        <label>CEP:</label>
                        <input id="cep" name="cep" type="text" autocomplete="off" class="form-control" ng-model="cep" maxlength="8" onchange="verificaCep(this.value)" <?php if($_POST['id_chamado']> 0){ ?> value=" <?php echo $_POST['cep']; } ?>">
                        <span id="erroCep" class="alertErro hide">CEP inválido.</span>
                    </div>
                    <div class="col-sm-8">
                        <label>Cidade:</label><span style="color:red;">*</span>
                        <!--<input id="cidade" name="cidade" type="text" class="form-control">-->
                        <select id="cidade" name="cidade" class="form-control">
                            <option value="Balneário Camboriú">Balneário Camboriú</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <label>Bairro:</label> <span style="color:red;">*</span>
                        <!--<input id="bairro" name="bairro" type="text" class="form-control">-->
                        <select id="bairro" name="bairro" class="form-control" selec>
                            <option <?php if($_POST['bairro'] == 'Centro'){ echo 'selected';} ?> value="Centro">Centro</option>
                            <option <?php if($_POST['bairro'] == 'Nações'){ echo 'selected'; }?> value="Nações">Nações</option>
                            <option <?php if($_POST['bairro'] == 'Pioneiros'){ echo 'selected';} ?> value="Pioneiros">Pioneiros</option>
                            <option <?php if($_POST['bairro'] == 'Estados'){ echo 'selected'; }?> value="Estados">Estados</option>
                            <option <?php if($_POST['bairro'] == 'Ariribá'){ echo 'selected'; }?> value="Ariribá">Ariribá</option>
                            <option <?php if($_POST['bairro'] == 'Praia dos Amores'){ echo 'selected';} ?> value="Praia dos Amores">Praia dos Amores</option>
                            <option <?php if($_POST['bairro'] == 'Municípios'){ echo 'selected'; }?> value="Municípios">Municípios</option>
                            <option <?php if($_POST['bairro'] == 'Vila Real'){ echo 'selected'; }?> value="Vila Real">Vila Real</option>
                            <option <?php if($_POST['bairro'] == 'Jardim Iate Clube'){ echo 'selected';} ?> value="Jardim Iate Clube">Jardim Iate Clube</option>
                            <option <?php if($_POST['bairro'] == 'Várzea do Ranchinho'){ echo 'selected'; }?> value="Várzea do Ranchinho">Várzea do Ranchinho</option>
                            <option <?php if($_POST['bairro'] == 'Barra'){ echo 'selected'; }?> value="Barra">Barra</option>
                            <option <?php if($_POST['bairro'] == 'Parque Bandeirantes'){ echo 'selected'; }?> value="Parque Bandeirantes">Parque Bandeirantes</option>
                            <option <?php if($_POST['bairro'] == 'Nova Esperança'){ echo 'selected';} ?> value="Nova Esperança">Nova Esperança</option>
                            <option <?php if($_POST['bairro'] == 'São Judas Tadeu'){ echo 'selected'; }?> value="São Judas Tadeu">São Judas Tadeu</option>
                            <option <?php if($_POST['bairro'] == 'Estaleiro'){ echo 'selected'; }?> value="Estaleiro">Estaleiro</option>
                            <option <?php if($_POST['bairro'] == 'Estaleirinho'){ echo 'selected'; }?> value="Estaleirinho">Estaleirinho</option>
                            <option <?php if($_POST['bairro'] == 'Laranjeiras'){ echo 'selected'; }?> value="Laranjeiras">Laranjeiras</option>
                            <option <?php if($_POST['bairro'] == 'Pinho'){ echo 'selected'; }?> value="Pinho">Pinho</option>
                            <option <?php if($_POST['bairro'] == 'Taquaras'){ echo 'selected'; }?> value="Taquaras">Taquaras</option>
                        </select>
                    </div>
                    <div class="col-sm-8">
                        <label>Logradouro:</label> <span style="color:red;">*</span>
                        <input id="logradouro" name="logradouro" type="text" class="form-control" ng-required="sel_endereco=='Logradouro'" <?php if($_POST['id_chamado'] > 0){ ?> value=" <?php echo $_POST['logradouro']; } ?>">
                        <?php if(isset($_GET['logradouro'])){ ?>
                            <span class="alertErro">Erro ao cadastrar logradouro.</span>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Número:</label><span style="color:red;">*</span>
                        <input id="numero" name="numero" type="text" class="form-control" ng-required="sel_endereco=='Logradouro'" <?php if($_POST['id_chamado'] > 0){ ?> value=" <?php echo $_POST['numero']; } ?>">
                    </div>
                    <div class="col-sm-8">
                        <label>Referência:</label>
                        <input name="referencia" type="text" class="form-control" <?php if($_POST['id_chamado'] != ''){ ?> value="<?php echo $_POST['referencia']; } ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                    <label>Complemento:</label>
                    <input id="complemento" name="complemento" class="form-control" autocomplete="off" <?php if($_POST['id_chamado'] != ''){ ?> value="<?php echo $_POST['complemento']; } ?>">
                    </div>
                </div>
            </div>
        <hr>

            <!--<div>
                Agente principal: <span style="color:red;">*</span>
                <input id="agente_principal" name="agente_principal" type="text" class="form-control" onkeyup="showResult(this.value,this.id)" value="<?php //echo $_POST['agente_principal']; ?>" required>
                <div class="autocomplete" id="livesearchagente_principal"></div>
            </div>
            <?php //if(isset($_GET['agente_principal'])){ ?>
                <span class="alertErro">Agente não encontrado.</span>
            <?php //} ?>-->
            <div>
                <span style="font-weight: bold;">Agente Principal:</span> <span><?php  echo $agente_principal->getNome();   ?></span>
            </div>
            <div>
                <label>Agente de apoio 1:</label>
                <input id="agente_apoio_1" name="agente_apoio_1" autocomplete="off" type="text" class="form-control" onkeyup="showResult(this.value,this.id)">
                <div class="autocomplete" id="livesearchagente_apoio_1"></div>
            </div>
            <?php if(isset($_GET['agente_apoio_1'])){ ?>
                <span class="alertErro">Agente não encontrado.</span>
            <?php } ?>
            <div>
                <label>Agente de apoio 2:</label>
                <input id="agente_apoio_2" name="agente_apoio_2" autocomplete="off" type="text" class="form-control" onkeyup="showResult(this.value,this.id)">
                <div class="autocomplete" id="livesearchagente_apoio_2"></div>
            </div>
            <?php if(isset($_GET['agente_apoio_2'])){ ?>
                <span class="alertErro">Agente não encontrado.</span>
            <?php } ?>
        <hr>
            <div>
                <label>Data de ocorrência:</label><span style="color:red;">*</span>
                <br>
                <input id="data_ocorrencia" name="data_ocorrencia"  type="date" class="form-control data" value="<?php if($_POST['data_ocorrencia']==""){echo date("Y-m-d");}else{echo $_POST['data_ocorrencia'];} ?>" max="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div>
                <label>Título:</label> <span style="color:red;">*</span>
                <textarea id="titulo" name="titulo" autocomplete="off" class="form-control titulobox" cols="30" rows="2" maxlength="120" ng-model="tituloVal" ng-init="tituloVal='<?php echo $_POST['titulo']; ?>'" required></textarea>
                <span class="char-count">{{tituloVal.length || 0}}/100</span>
            </div>
            <div>
                <label>Descrição:</label> <span style="color:red;">*</span>
                <textarea id="descricao" name="descricao"  autocomplete="off" class="form-control" cols="30" rows="15" required><?php echo $_POST['descricao']; ?></textarea>
            </div>
            <div>
                <?php if($_POST['ocorr_origem'] == ""){ ?>
                   <label>Origem:</label> <span style="color:red;">*</span>
                    <select name="ocorr_origem" class="form-control" ng-model="sel_origem" ng-init="sel_origem='Telefone Base'" required>
                    <option value="Telefone Base">Telefone Base</option>
                    <option value="Base">Base</option>
                    <option value="Ouvidoria">Ouvidoria</option>
                    <option value="199">199</option>
                    <option value="Secretaria de Obras">Secretaria de Obras</option>
                    <option value="Secretaria do Meio Ambiente">Secretaria do Meio Ambiente</opntion>
                    <option value="Secretaria da Saúde">Secretaria da Saúde</option>
                    <option value="Outro">Outro (Especificar)</option>
                    </select>

                    <div ng-show="sel_origem == 'Outro'">
                    <label>Descrição Origem:</label>
                    <input type="text" name="ocorr_origem2" class="form-control">
                    </div>
                <?php } else { ?>
                    <input name="ocorr_origem" type="hidden" class="form-control" value="<?php echo $_POST['ocorr_origem']; ?>">
                <?php } ?>
            </div>
        <hr>
            <div class="row">
                <div class="col-sm-12">
                    <label>Solicitante 1:</label>
                    <span id="alertpessoasucesso" class="alert-sucess" style="color: greenyellow;"></span>
                    <input id="pessoa_atendida_1" name="pessoa_atendida_1" autocomplete="off" type="text" class="form-control inline" style="width:93%;" value="<?php echo $_POST['pessoa1']; echo $pessoa_atendida_1; echo $_POST['pessoa_atendida_1']; ?>" onkeyup="showResult(this.value,this.id)" >
                    <button type="button" class="btn-default btn-small inline" data-toggle="modal" data-target="#pessoasModal"><span class="glyphicon glyphicon-plus"></span></button>
                    <div class="autocomplete" id="livesearchpessoa_atendida_1"></div>
                    <div id="resultpessoa_atendida_1"></div>
                    <!-- onkeyup="showResult(this.value,this.id)">
                    <div class="autocomplete" id="livesearchpessoa_atendida_1"></div>
                    <div id="resultpessoa_atendida_1"></div>
                    <?php //if(isset($_GET['pessoa_atendida_1'])){ ?>
                        <span class="alertErro">Pessoa não encontrada, por favor faça um novo cadastro.</span>
                    <?php //} ?>
                --></div>
                <!--<div class="col-sm-2">
                    <br>
                    <button type="button" class="btn-default btn-small inline open-AddBookDialog" data-toggle="modal" data-id="pessoa_atendida_1"><span class="glyphicon glyphicon-plus"></span></button>
                </div>-->
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label>Solicitante 2:</label>
                    <input id="pessoa_atendida_2" name="pessoa_atendida_2" autocomplete="off" type="text" class="form-control inline" style="width:93%;" value="<?php echo $_POST['pessoa1']; echo $pessoa_atendida_1; ?>" onkeyup="showResult(this.value,this.id)">
                    <button type="button" class="btn-default btn-small inline" data-toggle="modal" data-target="#pessoasModal"><span class="glyphicon glyphicon-plus"></span></button>
                    <div class="autocomplete" id="livesearchpessoa_atendida_2"></div>
                    <div id="resultpessoa_atendida_2"></div>
                    <!-- onkeyup="showResult(this.value,this.id)">
                    <div class="autocomplete" id="livesearchpessoa_atendida_2"></div>
                    <div id="resultpessoa_atendida_2"></div>
                    <?php //if(isset($_GET['pessoa_atendida_2'])){ ?>
                        <span class="alertErro">Pessoa não encontrada, por favor faça um novo cadastro.</span>
                    <?php //} ?>
                --></div>
                <!--<div class="col-sm-2">
                    <br>
                    <button type="button" class="btn-default btn-small inline open-AddBookDialog" data-toggle="modal" data-id="pessoa_atendida_2"><span class="glyphicon glyphicon-plus"></span></button>
                </div>-->
            </div>
        <hr>
            <div>
                <label>Cobrade:</label> <span style="color:red;">*</span>
                <?php if(isset($_GET['cobrade'])){ ?>
                    <br><span class="alertErro">
                        Cobrade incorreto.
                    </span>
                <?php } ?>
                <div class="cobrade">
                    <label>Categoria:</label> <br>
                    <select name="cobrade_categoria" class="form-control cobrade-sub" ng-model="categoria">
                        <option value="1">Naturais</option>
                        <option value="2">Tecnológicos</option>
                        <option value="0">Sem Cobrade</option>
                    </select>
                    <label>Grupo:</label> <br>
                    <select name="cobrade_grupo" class="form-control cobrade-sub" ng-model="grupo" ng-disabled="categoria == 0">
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
                    <label>Subgrupo:</label> <br>
                    <select name="cobrade_subgrupo" class="form-control cobrade-sub" ng-model="subgrupo" ng-disabled="grupo == 0 || categoria == 0">
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
                    <label>Tipo:</label> <br>
                    <select name="cobrade_tipo" class="form-control cobrade-sub" ng-model="tipo" ng-disabled="subgrupo==0 || categoria == 0">
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
                    <label>Subtipo:</label> <br>
                    <select name="cobrade_subtipo" class="form-control cobrade-sub" ng-model="subtipo" ng-disabled="tipo==0 || categoria==2 || categoria == 0">
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
            <div>
                <label>Fotos:</label>
                <input id="imgInp" name="files[]" type="file" multiple="multiple" accept="image/png,image/jpeg">
            </div>
            <div class="gallery"></div>
        <hr>
            <div>
                <label>Prioridade:</label> <span style="color:red;">*</span>
                <label for="prioridade"></label>
                <select name="prioridade" class="form-control" style="width:30%;" required>
                    <option value="Baixa">Baixa</option>
                    <option value="Média">Média</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>
        </div>
        <div class="div-btn-cadastrar">
        <input class="hidden" name="id_coordenada" value="<?php echo $_POST['id_coordenada'] ?>">
        <input type="submit" class="btn-cadastrar btn-default btn-md" value="Cadastrar">
        </div>
    </form>

    <div class="modal fade" id="pessoasModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cadastrar pessoa</h4>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <nav>
                            <input id="id_pessoa" type="hidden" value="">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Nome:</label> <span style="color:red;">*</span>
                                    <input id="nome_pessoa" name="nome_pessoa" type="text" class="form-control" onchange="verificaNome(this.value)">
                                </div>
                            </div>   
                            <span id="erroNome" class="alertErro hide">Nome inválido.</span>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>CPF:</label>
                                    <input id="cpf_pessoa" name="cpf_pessoa" type="text" class="form-control" onchange="verificaCpf(this.value)">
                                    <span id="erroCpf" class="alertErro hide">CPF inválido.</span>
                                </div>
                                <div class="col-sm-6">
                                    <label>Outros documentos:</label>
                                    <input id="outros_documentos" name="outros_documentos" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Celular: </label>
                                    <input id="celular_pessoa" name="celular_pessoa" type="text" class="form-control" pattern="\([0-9]{2}\)[\s][0-9]{4,5}-[0-9]{4}" title="(XX) XXXXX-XXXX" onchange="verificaCelular(this.value)">
                                    <span id="erroCelular" class="alertErro hide">Celular inválido.</span>
                                </div>
                                <div class="col-sm-6">
                                    <label>Fixo:</label> 
                                    <input id="telefone_pessoa" name="telefone_pessoa" type="text" class="form-control" pattern="\([0-9]{2}\)\s[0-9]{4}\-[0-9]{4}" title="(XX) XXXX-XXXX" onchange="verificaTelefone(this.value)">
                                    <span id="erroTelefone" class="alertErro hide">Telefone inválido.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
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
                    <button type="button"  data-dismiss="modal">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


