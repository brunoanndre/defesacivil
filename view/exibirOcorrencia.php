<?php
    include 'database.php';
    require_once 'dao/OcorrenciaDaoPgsql.php';
    require_once 'dao/UsuarioDaoPgsql.php';

    $usuariodao = New UsuarioDaoPgsql($pdo);
    $ocorrenciadao = New OcorrenciaDaoPgsql($pdo);
    $id_ocorrencia = $_GET['id'];
    //BUSCA A OCORRENCIA NO BD
    $linhaOcorrencia = $ocorrenciadao->buscarPeloId($id_ocorrencia);

    //BUSCA O ENDEREÇO NO BD
    if($linhaOcorrencia['ocorr_endereco_principal'] == "Logradouro"){
        $id_logradouro = $linhaOcorrencia['ocorr_logradouro_id'];
        $linhaLogradouro = $ocorrenciadao->buscaEnderecoPeloId($id_logradouro);
    }

    $id_agente = $linhaOcorrencia['agente_principal'];
    //BUSCA O NOME DO USUARIO NO BD
    $linhaAgentePrincipal = $usuariodao->findById($id_agente);

    //BUSCA O NOME DO USUARIO CRIADOR NO BD
    $id_usuario_criador = $linhaOcorrencia['usuario_criador'];
    $linhaUsuarioCriador = $usuariodao->findById($id_usuario);

    if($linhaOcorrencia['agente_apoio_1']){
        $id_agente = $linhaOcorrencia['agente_apoio_1'];
        $linhaAgente1 = $usuariodao->findById($id_agente);
    }
    if($linhaOcorrencia['agente_apoio_2']){
        $id_agente = $linhaOcorrencia['agente_apoio_2'];
        $linhaAgente2 = $usuariodao->findById($id_agente);
    }
 
    if($linhaOcorrencia['atendido_1']){
       $id_pessoa = $linhaOcorrencia['atendido_1'];
       $linhaPessoa1 = $ocorrenciadao->buscaPessoaPeloId($id_pessoa);
     }
    if($linhaOcorrencia['atendido_2']){
        $id_pessoa = $linhaOcorrencia['atendido_2'];
        $linhaPessoa2 = $ocorrenciadao->buscaPessoaPeloId($id_pessoa);
    }
    //BUSCAR COBRADE NO BD
    $cobrade = $linhaOcorrencia['ocorr_cobrade'];
    $linhaCobrade = $ocorrenciadao->buscaCobrade($cobrade);

    //BUSCAR ID DA INTERDIÇÃO NO BD
    $id_interdicao = $ocorrenciadao->buscaInterdicao($id_ocorrencia);

    $string = $linhaOcorrencia['fotos'];

    $string = str_replace('{','',$string);
    $string = str_replace('}','',$string);

    $fotos = explode(',', $string);
        echo "<pre>";
    var_dump($linhaOcorrencia);
    die;
?>

<div class="container positioning">
<div class="jumbotron campo_cadastro">
    <?php if(isset($_GET['sucesso'])){ ?>
            <div class="alert alert-success" role="alert">
                Ocorrencia alterada com sucesso.
            </div>
    <?php } ?>
    <?php if(isset($_GET['sucessoInterdicao'])){ ?>
            <div class="alert alert-success" role="alert">
                Desinterditado com sucesso.
            </div>
    <?php } ?>
    <div class="box">
        <div class="printCabecalho">
            <div class="row cabecalho">
                <div class="col-sm-6 printHide">
                    <nav class="texto-cabecalho">Estado de Santa Catarina</nav>
                    <nav class="texto-cabecalho">Prefeitura de Balneário Camboriú</nav>
                    <nav class="texto-cabecalho">Secretaria de segurança</nav>
                    <nav class="texto-cabecalho">Defesa Civil</nav>
                </div>
                <div class="col-sm-6 printHide">
                    <img src="images/balneario-camboriu.png" alt="prefeitura-balneario-camboriu" class="img-cabecalho">
                </div>
            </div>
            <div class="printTitle">
                <h3 class="text-center">Registro de ocorrência</h3>
            </div>
            <div class="printShowImg"> <img src="images/balneario-camboriu.png" alt="prefeitura-balneario-camboriu" class="img-cabecalho" style="width: 100px; height:auto;"></div>
        </div>
        <button class="printHide" style="background-color: white; border:none;" onclick="print()"><img src="images/print.png" style="width: 50px; height:auto"></button>
    </div>
    <div class="box">
        <h4>Endereço</h4>
        <hr>
        <span class="titulo printHide">Endereço principal: </span><span class="printHide" id="coordenada_principal" ng-model="sel_endereco" ng-init="sel_endereco='<?php echo $linhaOcorrencia['ocorr_endereco_principal']; ?>'"><?php echo $linhaOcorrencia['ocorr_endereco_principal']; ?></span>
        <div ng-show="sel_endereco == 'Logradouro'">
            <div class="row">
                <div class="col-sm-7"><span class="titulo">CEP: </span><?php echo $linhaLogradouro['cep']; ?></div>
                <div class="col-sm-7"><span class="titulo">Logradouro: </span><?php echo $linhaLogradouro['logradouro']; ?></div>
                <div class="col-sm-3"><span class="titulo">Número: </span><?php echo $linhaLogradouro['numero']; ?></div>

                <div class="col-sm-7"><span class="titulo">Bairro: </span><?php echo $linhaLogradouro['bairro']; ?></div>
                <div class="col-sm-7"><span class="titulo">Cidade: </span><?php echo $linhaLogradouro['cidade']; ?></div>
                <div class="col-sm-7"><span class="titulo">Referência:</span><?php echo $linhaLogradouro['referencia']; ?></div>
            </div>
        </div>
        <div ng-show="sel_endereco == 'Coordenada'">
            <nav>
                <span class="titulo">Latitude: </span><span id="latitude" ><?php echo $linhaOcorrencia['ocorr_coordenada_latitude']; ?></span>
            </nav>
            <nav class="inline">
                <span class="titulo">Longitude: </span><span id="longitude" ><?php echo $linhaOcorrencia['ocorr_coordenada_longitude']; ?></span>
            </nav>
            <button type="button" class="btn-default btn-small inline open-AddBookDialog" style="position:relative;left:5%" data-toggle="modal" data-id="map"><span class="glyphicon glyphicon-map-marker"></span></button>
        </div>
    </div>
    <div class="box">
        <h4>Agentes</h4>
        <hr>
        <span class="titulo">Agente principal:</span><span class="printShoww"> <?php echo $linhaAgentePrincipal['nome']; ?></span></span><a id="agente_principal" class="printHide" href="?pagina=exibirUsuario&id=<?php echo $linhaOcorrencia['agente_principal']; ?>"><?php echo $linhaAgentePrincipal['nome']; ?></a><br>
        <?php if($linhaOcorrencia['agente_apoio_1']){ ?>
            <span class="titulo">Agente de apoio 1: </span><span class="printShoww"><?php echo $linhaAgente1['nome']; ?></span><a id="agente_principal" class="printHide" href="?pagina=exibirUsuario&id=<?php echo $linhaOcorrencia['agente_apoio_1']; ?>"><?php echo $linhaAgente1['nome']; ?></a><br>
        <?php } if($linhaOcorrencia['agente_apoio_2']){ ?>
            <span class="titulo">Agente de apoio 2: </span><span class="printShoww"><?php echo $linhaAgente2['nome']; ?></span><a id="agente_principal" class="printHide" href="?pagina=exibirUsuario&id=<?php echo $linhaOcorrencia['agente_apoio_2']; ?>"><?php echo $linhaAgente2['nome']; ?></a><br>
        <?php } ?>
        <br>
    </div>
    <div class="box">
        <h4>Ocorrencia</h4>
        <hr>
        <div class="printWrap">
            <span class="titulo">Data de ocorrência: </span><span id="data_ocorrencia"><?php echo date("d/m/Y", strtotime($linhaOcorrencia['data_ocorrencia'])); ?></span><br>
            <span class="titulo">Titulo: </span><span id="ocorr_titulo"><?php echo $linhaOcorrencia['ocorr_titulo']; ?></span><br>
            <span class="titulo">Origem: </span><span id="ocorr_origem"><?php echo $linhaOcorrencia['ocorr_origem']; ?></span><br>
        </div>
            <span class="titulo">Descrição: </span><br>
            <textarea id="ocorr_descricao" rows="5" readonly class="readtextarea"><?php echo $linhaOcorrencia['ocorr_descricao']; ?></textarea><br>

        <br>
    </div>
    <div class="box">
       <?php if($linhaOcorrencia['atendido_1'] || $linhaOcorrencia['atendido_2']){?>
          <h4 >Solicitantes</h4> 
          <hr> 
      <?php  } else{ ?>
        <h4 class="printHide">Solicitantes</h4>
        <hr class="printHide">
        <?php } ?>
        <?php if(!$linhaOcorrencia['atendido_1'] && !$linhaOcorrencia['atendido_2']){ ?>
            <span class="titulo printHide">Nenhuma pessoa foi cadastrada</span><br>
        <?php }else{ ?>
            <span class="titulo">Solicitante 1: </span><span><?php echo $linhaPessoa1['nome']; ?></span>
            <!--<a id="atendido_1" href="?pagina=exibirPessoa&id=<?php //echo $linhaOcorrencia['atendido_1']; ?>"><?php //echo $linhaPessoa1['nome']; ?></a><br>-->
            <?php if($linhaOcorrencia['nome_pessoa2'] != ""){ ?>
                <span class="titulo">Solicitante 2: </span><span><?php echo $linhaPessoa2['nome']; ?></span>
                <!--<a id="atendido_2" href="?pagina=exibirPessoa&id=<?php //echo $linhaOcorrencia['atendido_2']; ?>"><?php //echo $linhaPessoa2['nome']; ?></a><br>-->
            <?php } 
        }?>

    </div>
    <div class="box">
        <h4>Tipo</h4>
        <hr>
        <div class="printWrap">
        <span class="titulo">Cobrade: </span><span id="ocorr_cobrade"><?php echo $linhaCobrade['subgrupo']; ?></span><br>
        <span class="titulo">Possui fotos: </span><span id="fotos"><?php echo ($linhaOcorrencia['ocorr_fotos'] == 't') ? 'Sim':'Não'; ?></span>
        </div>
    </div>
    <div class="box printMargin">
        <h4>Status</h4>
        <hr>
        <span class="titulo">Prioridade: </span><span id="ocorr_prioridade"><?php echo $linhaOcorrencia['ocorr_prioridade']; ?></span>
        <span class="titulo">Analisado: </span><span id="ocorr_analisado"><?php echo ($linhaOcorrencia['ocorr_analisado'] == 't') ? 'Sim':'Não'; ?></span>
        <span class="titulo">Congelado: </span><span id="ocorr_congelado"><?php echo ($linhaOcorrencia['ocorr_congelado']== 't') ? 'Sim':'Não'; ?></span>
        <span class="titulo">Encerrado: </span><span id="ocorr_encerrado"><?php echo ($linhaOcorrencia['ocorr_encerrado']== 't') ? 'Sim':'Não'; ?></span>
        <br><br>
    </div>
    <div class="box">
        <h4>Informações</h4>
        <hr>
        <span class="titulo">Ativa: </span><span id="ativa"><?php echo ($linhaOcorrencia['ativo']== 't') ? 'Sim':'Não'; ?></span><br>
        <span class="titulo">Data de alteração: </span><span id="data_alteracao"><?php echo date("d/m/Y", strtotime($linhaOcorrencia['data_ocorrencia'])); ?></span><br>
        <span class="titulo">Usuário que realizou a alteração: </span><span class="printShoww"><?php echo $linhaUsuarioCriador['nome']; ?></span><a id="usuario_criador" class="printHide" href="?pagina=exibirUsuario&id=<?php echo $linhaOcorrencia['usuario_criador']; ?>"><?php echo $linhaUsuarioCriador['nome']; ?></a><br>
        <span class="titulo">Ocorrência de referência: </span>
            <?php if($linhaOcorrencia['ocorr_referencia'] == null){ ?>
                <span id="ocorr_referencia"><?php echo 'Não possui'; ?></span><br>
            <?php }else{ ?>
            <span class="printShoww"><?php echo $linhaOcorrencia['ocorr_referencia'];?></span>
                <a class="printHide" id="ocorr_referencia" href="?pagina=exibirOcorrencia&id=<?php echo $linhaOcorrencia['ocorr_referencia']; ?>"><?php echo $linhaOcorrencia['ocorr_referencia']; ?></a><br>
            <?php } ?>
        <br>
    </div>
    <?php if($linhaOcorrencia['ocorr_fotos'] == 't'){ ?>
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
                    <img src="data:image/png;base64,<?php echo $fotos[0]; ?>" alt="img1" style="width:100%;">
                </div>
                <?php $i = 1; while($i < sizeof($fotos)){ ?>
                    <div class="item">
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
            </div>
        </div>
    </div>
    <?php if($linhaOcorrencia['ativo']== 't'){ ?>
        <form action="index.php?pagina=editarOcorrencia" method="post">
            <input name="id_ocorrencia" type="hidden" value="<?php echo $id_ocorrencia; ?>">
            <input name="chamado_id" type="hidden" value="<?php echo $linhaOcorrencia['chamado_id']; ?>">
            <input name="endereco_principal" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_endereco_principal']; ?>">
            <input name="cep" type="hidden" value="<?php echo $linhaLogradouro['cep']; ?>">
            <input name="cidade" type="hidden" value="<?php echo $linhaLogradouro['cidade']; ?>">
            <input name="bairro" type="hidden" value="<?php echo $linhaLogradouro['bairro']; ?>">
            <input name="logradouro" type="hidden" value="<?php echo $linhaLogradouro['logradouro']; ?>">
            <input name="numero" type="hidden" value="<?php echo $linhaLogradouro['numero'] ?>">
            <input name="id_logradouro" type="hidden" value="<?php  echo $id_logradouro;?>">
            <input name="referencia" type="hidden" value="<?php echo $linhaLogradouro['referencia']; ?>">
            <input name="latitude" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_coordenada_latitude']; ?>">
            <input name="longitude" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_coordenada_longitude']; ?>">
            <input name="agente_principal" type="hidden" value="<?php echo $linhaAgentePrincipal['nome']; ?>">
            <input name="agente_apoio1" type="hidden" value="<?php echo $linhaAgente1['nome']; ?>">
            <input name="agente_apoio2" type="hidden" value="<?php echo $linhaAgente2['nome']; ?>">
            <input name="data_lancamento" type="hidden" value="<?php echo $linhaOcorrencia['data_lancamento']; ?>">
            <input name="data_ocorrencia" type="hidden" value="<?php echo $linhaOcorrencia['data_ocorrencia']; ?>">
            <input name="titulo" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_titulo']; ?>">
            <input name="ocorr_descricao" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_descricao']; ?>">
            <input name="ocorr_origem" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_origem']; ?>">
            <input name="pessoa1" type="hidden" value="<?php echo $linhaPessoa1['nome']; ?>">
            <input name="pessoa2" type="hidden" value="<?php echo $linhaPessoa2['nome']; ?>">
            <input name="ocorr_cobrade" type="hidden" value="<?php echo $linhaCobrade['codigo']; ?>">
            <input name="cobrade_descricao" type="hidden" value="<?php echo $linhaOcorrencia['cobrade_descricao']; ?>">
            <input name="possui_fotos" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_fotos']; ?>">
            <input name="prioridade" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_prioridade']; ?>">
            <input name="analisado" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_analisado']; ?>">
            <input name="congelado" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_congelado']; ?>">
            <input name="encerrado" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_encerrado']; ?>">
            <a class="printHide" href="index.php?pagina=consultarOcorrencia.php" style="text-decoration:none; color:#000000;"><input class="btn btn-default printHide" style="left:25%;" value="Voltar" type="button"></a>
            <input type="submit" class="btn btn-default btn-md printHide" style="position:relative;left:30%; color:#000000;" value="Editar Ocorrencia">
        </form>
        <?php if(!$id_interdicao){ ?>
            <form action="index.php?pagina=cadastrarInterdicao" method="post">
                <input name="id_ocorrencia" type="hidden" value="<?php echo $id_ocorrencia; ?>">
                <input name="titulo_ocorrencia" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_titulo']; ?>">
                <input name="endereco_principal" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_endereco_principal']; ?>">
                <input name="cep" type="hidden" value="<?php echo $linhaLogradouro['cep']; ?>">
                <input name="cidade" type="hidden" value="<?php echo $linhaLogradouro['cidade']; ?>">
                <input name="bairro" type="hidden" value="<?php echo $linhaLogradouro['bairro']; ?>">
                <input name="logradouro" type="hidden" value="<?php echo $linhaLogradouro['logradouro']; ?>">
                <input name="numero" type="hidden" value="<?php echo $linhaLogradouro['numero'] ?>">
                <input name="referencia" type="hidden" value="<?php echo $linhaLogradouro['referencia']; ?>">
                <input name="latitude" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_coordenada_latitude']; ?>">
                <input name="longitude" type="hidden" value="<?php echo $linhaOcorrencia['ocorr_coordenada_longitude']; ?>">
                <input type="submit" class="btn btn-default btn-md btn-interdicao-g printHide" style="left:65%; color:#000000;  "value="Gerar Interdição">
            </form>
        <?php }else{ ?>
            <a href="index.php?pagina=exibirInterdicao&id=<?php echo $id_interdicao; ?>" class="btn btn-default btn-md btn-interdicao printHide">Verificar Interdição</a>
        <?php } ?>
    <?php } ?>
</div>
</div>