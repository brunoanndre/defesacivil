<?php
    include 'database.php';
    require_once 'dao/OcorrenciaDaoPgsql.php';
    require_once 'dao/UsuarioDaoPgsql.php';
    require_once 'dao/PessoaDaoPgsql.php';
    require_once 'dao/EnderecoDaoPgsql.php';
    require_once 'dao/NotificacaoDaoPgsql.php';

    $notificacaodao = New NotificacaoDaoPgsql($pdo);
    $enderecodao = new EnderecoDaoPgsql($pdo);
    $pessoadao = New PessoaDaoPgsql($pdo);
    $usuariodao = New UsuarioDaoPgsql($pdo);
    $ocorrenciadao = New OcorrenciaDaoPgsql($pdo);
    $id_ocorrencia = $_GET['id'];
    //BUSCA A OCORRENCIA NO BD
    $linhaOcorrencia = $ocorrenciadao->buscarPeloId($id_ocorrencia);


    //BUSCA O ENDEREÇO NO BD


    if($linhaOcorrencia->getEnderecoPrincipal() == "Logradouro"){
        $id_logradouro = $linhaOcorrencia->getLogradouroid();
        $linhaLogradouro = $enderecodao->buscarPeloId($id_logradouro);
    }

    $id_agente = $linhaOcorrencia->getIdCriador();

    //BUSCA O NOME DO USUARIO NO BD
    $linhaAgentePrincipal = $usuariodao->findById($id_agente);

    //BUSCA O NOME DO USUARIO CRIADOR NO BD
    $id_usuario_criador = $linhaOcorrencia->getIdCriador();

    $linhaUsuarioCriador = $usuariodao->findById($id_usuario_criador);

    //BUSCA O NOME DO ULTIMO USUARIO QUE EDITOU A OCORRENCIA
    $usuario_editor = $usuariodao->findById($linhaOcorrencia->getUsuarioEditor());


    if($linhaOcorrencia->getApoio1()){
        $id_agente = $linhaOcorrencia->getApoio1();
        $linhaAgente1 = $usuariodao->findById($id_agente);
    }

    if($linhaOcorrencia->getApoio2()){
        $id_agente = $linhaOcorrencia->getApoio2();
        $linhaAgente2 = $usuariodao->findById($id_agente);
    }
 
    if($linhaOcorrencia->getIdPessoa1()){
       $id_pessoa = $linhaOcorrencia->getIdPessoa1();
       $linhaPessoa1 = $pessoadao->buscarPeloID($id_pessoa);
     }
    if($linhaOcorrencia->getIdPessoa2()){
        $id_pessoa = $linhaOcorrencia->getIdPessoa2();
        $linhaPessoa2 = $pessoadao->buscarPeloID($id_pessoa);
    }
    //BUSCAR COBRADE NO BD
    $cobrade = $linhaOcorrencia->getCobrade();
    $linhaCobrade = $ocorrenciadao->buscaCobrade($cobrade);

    //BUSCAR ID DA INTERDIÇÃO NO BD
    $id_interdicao = $ocorrenciadao->buscaInterdicao($id_ocorrencia);

    //BUSCAR ID DA NOTIFICACAO NO BD
    $id_notificacao = $notificacaodao->buscarIdNotificacao($id_ocorrencia);

    $string = $linhaOcorrencia->getFotos();

    $barras = array("{","}");
    $string = str_replace($barras,'',$string);
    

    $fotos = explode(',', $string);

    $linhaCoordenada = $enderecodao->buscarIdCoordenada($linhaOcorrencia->getIdCoordenada());

?>

<div class="container positioning">
<div class="jumbotron campo_cadastro">
    <?php if(isset($_GET['sucesso'])){ ?>
            <div class="alert alert-success printHide" role="alert">
                Ocorrencia alterada com sucesso.
            </div>
    <?php } ?>
    <?php if(isset($_GET['sucessocad'])){ ?>
            <div class="alert alert-success printHide" role="alert">Ocorrencia cadastrada com sucesso.</div>
        <?php } ?>
    <?php if(isset($_GET['sucessoInterdicao'])){ ?>
            <div class="alert alert-success printHide" role="alert">
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
            <div class="printHide">
                <h3 class="text-center"><?php echo 'Ocorrência Nº ' . $id_ocorrencia . '/' . date('Y'); ?></h3>
            </div>
        </div>
        <div class="printShow divPrintHeader row">
                <img class="printShow" src="images/logo.jpg" style="width: 40px;">
                <h3 class="ocorrenciaTitlePrint printShow"><?php echo 'Ocorrência Nº ' . $id_ocorrencia . '/' . date('Y'); ?></h3>
                <img src="images/balneario-camboriu.png" alt="prefeitura-balneario-camboriu" class="img-cabecalho printShow" style="width: 120px;">
        </div>
        <button class="printHide" style="background-color: white; border:none;" onclick="print()"><img src="images/print.png" style="width: 50px; height:auto"></button>
    </div>
    <div class="box">
        <h4 class="printHide">Endereço</h4>
        <h3 class="printShow">Endereço</h3>
        <hr>
        <span class="titulo printHide">Localizar por: </span><span class="printHide" id="coordenada_principal" ng-model="sel_endereco" ng-init="sel_endereco='<?php echo $linhaOcorrencia->getEnderecoPrincipal(); ?>'"><?php echo $linhaOcorrencia->getEnderecoPrincipal(); ?></span>
        <?php if($linhaOcorrencia->getEnderecoPrincipal() == "Logradouro"){ ?>
        <div ng-show="sel_endereco == 'Logradouro'">
            <div class="row">
                <div class="col-sm-7"><span class="titulo">CEP: </span><?php echo $linhaLogradouro->getCep(); ?></div>
                <div class="col-sm-7"><span class="titulo">Logradouro: </span><?php echo $linhaLogradouro->getLogradouro(); ?></div>
                <div class="col-sm-3"><span class="titulo">Número: </span><?php echo $linhaLogradouro->getNumero(); ?></div>
                <div class="col-sm-7"><span class="titulo">Bairro: </span><?php echo $linhaLogradouro->getBairro(); ?></div>
                <div class="col-sm-7"><span class="titulo">Cidade: </span><?php echo $linhaLogradouro->getCidade(); ?></div>
                <div class="col-sm-7"><span class="titulo">Referência:</span><?php echo $linhaLogradouro->getReferencia(); ?></div>
            </div>
        </div>
        <?php } ?>
        <?php if($linhaOcorrencia->getEnderecoPrincipal() == "Coordenada"){  ?>
        <div ng-show="sel_endereco == 'Coordenada'">
            <nav>
                <span class="titulo">Latitude: </span><span id="latitude" ><?php echo $linhaCoordenada->getLatitude(); ?></span>
            </nav>
            <nav class="inline">
                <span class="titulo">Longitude: </span><span id="longitude" ><?php echo $linhaCoordenada->getLongitude(); ?></span>
            </nav>
            <button type="button" class="btn-default btn-small inline printHide" onclick="abrirMapa(), myMap()"><span class="glyphicon glyphicon-map-marker"></span></button>
        </div>
        <?php }?>
    </div>
    <div class="box">
        <h4 class="printHide">Agentes</h4>
        <h3 class="printShow">Agentes</h3>
        <hr>
        <span class="titulo">Agente principal:</span><span class="printShoww"> <?php echo $linhaAgentePrincipal->getNome(); ?></span></span><a id="agente_principal" class="printHide" href="?pagina=exibirUsuario&id=<?php echo $linhaOcorrencia->getIdCriador(); ?>"><?php echo $linhaAgentePrincipal->getNome(); ?></a><br>
        <?php if($linhaOcorrencia->getApoio1()){ ?>
            <span class="titulo">Agente de apoio 1: </span><span class="printShoww"><?php echo $linhaAgente1->getNome(); ?></span><a id="agente_principal" class="printHide" href="?pagina=exibirUsuario&id=<?php echo $linhaOcorrencia->getApoio1(); ?>"><?php echo $linhaAgente1->getNome(); ?></a><br>
        <?php } if($linhaOcorrencia->getApoio2){ ?>
            <span class="titulo">Agente de apoio 2: </span><span class="printShoww"><?php echo $linhaAgente2->getNome(); ?></span><a id="agente_principal" class="printHide" href="?pagina=exibirUsuario&id=<?php echo $linhaOcorrencia->getApoio2(); ?>"><?php echo $linhaAgente2->getNome(); ?></a><br>
        <?php } ?>
        <br>
    </div>
    <div class="box">
        <h4 class="printHide">Ocorrência</h4>
        <h3 class="printShow">Ocorrência</h3>
        <hr>
        <div class="printWrap">
            <span class="titulo">Data de ocorrência: </span><span id="data_ocorrencia"><?php echo date("d/m/Y", strtotime($linhaOcorrencia->getData())); ?></span><br>
            <span class="titulo">Titulo: </span><span id="ocorr_titulo"><?php echo $linhaOcorrencia->getTitulo(); ?></span><br>
            <span class="titulo">Origem: </span><span id="ocorr_origem"><?php echo $linhaOcorrencia->getOrigem(); ?></span><br>
        </div>
            <div class="row" style="margin: 0;"><span class="titulo">Descrição: </span><span class="printShow"><?php echo $linhaOcorrencia->getDescricao() ?></span></div>
            <textarea id="ocorr_descricao" rows="5" readonly class="printHide readtextarea"><?php echo $linhaOcorrencia->getDescricao(); ?></textarea><br>

        <br>
    </div>
    <div class="box">
       <?php if($linhaOcorrencia->getIdPessoa1() || $linhaOcorrencia->getIdPessoa2()){?>
          <h4 class="printHide" >Solicitantes</h4> 
          <h4 class="printShow">Solicitantes</h4>
          <hr> 
      <?php  } else{ ?>
        <h4 class="printHide">Solicitantes</h4>
        <hr class="printHide">
        <?php } ?>
        <?php if(!$linhaOcorrencia->getIdPessoa1() && !$linhaOcorrencia->getIdPessoa2()){ ?>
            <span class="titulo printHide">Nenhuma pessoa foi cadastrada</span><br>
        <?php }else{ ?>
            <div class="row">
                <span class="titulo">Solicitante 1: <a href="" class="open-AddBookDialog printHide" data-toggle="modal" data-id="pessoa_nome1"></span><span><?php echo $linhaPessoa1->getNome(); ?></span></a><span class="printShow"><?php echo $linhaPessoa1->getNome(); ?></span>
                <span class="titulo printShow">Contato: </span><span class="printShow"><?php echo $linhaPessoa1->getCelular(); ?></span>
            </div>
            <?php if($linhaOcorrencia->getIdPessoa2() != ""){ ?>
                <div class="row">
                    <span class="titulo">Solicitante 2: <a href="" class="open-AddBookDialog printHide" data-toggle="modal" data-id="pessoa_nome2"></span><span><?php echo $linhaPessoa2->getNome(); ?></span></a><span class="printShow"><?php echo $linhaPessoa2->getNome(); ?></span>
                    <span class="titulo printShow">Contato: </span><span class="printShow"><?php echo $linhaPessoa2->getCelular(); ?></span>
                </div>
            <?php } 
        }?>
<?php
if($linhaPessoa1 !== null){ ?>
<div class="modal fade" id="pessoa1Modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <span>Dados da pessoa</span>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                    <div class="modal-body">
                        <nav>
                            <input id="id_pessoa" type="hidden" value="">
                            <div class="row">
                                <div class="col-sm-12">
                                    Nome:
                                   <?php echo '<input id="nome_pessoa" name="nome_pessoa" type="text" class="form-control" value=" '.$linhaPessoa1->getNome() . ' " disabled > '  ?>
                                </div>
                            </div>   
                            <span id="erroNome" class="alertErro hide">Nome inválido.</span>
                            <div class="row">
                                <div class="col-sm-6">
                                    CPF:
                                    <?php echo '<input id="cpf_pessoa" name="cpf_pessoa" type="text" class="form-control" value="'.$linhaPessoa1->getCPF().'" disabled>' ?>
                                </div>
                                <div class="col-sm-6">
                                    Outros documentos:
                                    <?php echo '<input id="outros_documentos" name="outros_documentos" class="form-control" type="text" value="'.$linhaPessoa1->getOutrosDocumentos().'" disabled>' ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    Celular: 
                                    <?php echo ' <input id="celular_pessoa" name="celular_pessoa" type="text" class="form-control" value="'. $linhaPessoa1->getCelular() .'" disabled >'?>
                                    
                                </div>
                                <div class="col-sm-6">
                                    Telefone: 
                                    <?php echo'<input id="telefone_pessoa" name="telefone_pessoa" type="text" class="form-control" value="'.$linhaPessoa1->getTelefone().'" disabled>'?>
                                </div>
                            </div>
                            <div class="form-group">
                                Email:
                                <?php echo'<input id="email_pessoa" name="email_pessoa" type="email" class="form-control" value="'.$linhaPessoa1->getEmail().'" disabled>' ?>
                            </div>
                        </nav>
                    </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php
if($linhaPessoa2 !== null){ ?>
<div class="modal fade" id="pessoa2Modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <span>Dados da pessoa</span>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                    <div class="modal-body">
                        <nav>
                            <input id="id_pessoa" type="hidden" value="">
                            <div class="row">
                                <div class="col-sm-12">
                                    Nome:
                                   <?php echo '<input id="nome_pessoa" name="nome_pessoa" type="text" class="form-control" value=" '.$linhaPessoa2->getNome() . ' " disabled > '  ?>
                                </div>
                            </div>   
                            <span id="erroNome" class="alertErro hide">Nome inválido.</span>
                            <div class="row">
                                <div class="col-sm-6">
                                    CPF:
                                    <?php echo '<input id="cpf_pessoa" name="cpf_pessoa" type="text" class="form-control" value="'.$linhaPessoa2->getCPF().'" disabled>' ?>
                                </div>
                                <div class="col-sm-6">
                                    Outros documentos:
                                    <?php echo '<input id="outros_documentos" name="outros_documentos" class="form-control" type="text" value="'.$linhaPessoa2->getOutrosDocumentos().'" disabled>' ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    Celular: 
                                    <?php echo ' <input id="celular_pessoa" name="celular_pessoa" type="text" class="form-control" value="'. $linhaPessoa2->getCelular() .'" disabled >'?>
                                    
                                </div>
                                <div class="col-sm-6">
                                    Telefone: 
                                    <?php echo'<input id="telefone_pessoa" name="telefone_pessoa" type="text" class="form-control" value="'.$linhaPessoa2->getTelefone().'" disabled>'?>
                                </div>
                            </div>
                            <div class="form-group">
                                Email:
                                <?php echo'<input id="email_pessoa" name="email_pessoa" type="email" class="form-control" value="'.$linhaPessoa2->getEmail().'" disabled>' ?>
                            </div>
                        </nav>
                    </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="box">
        <h4 class="printHide">Tipo</h4>
        <h4 class="printShow">Tipo</h4>
        <hr>
        <div class="printWrap">
        <span class="titulo">Cobrade: </span><span id="ocorr_cobrade"><?php echo $linhaCobrade['subgrupo']; ?></span><br>
        <span class="titulo">Possui fotos: </span><span id="fotos"><?php echo ($linhaOcorrencia->getPossuiFotos() == 1) ? 'Sim':'Não'; ?></span>
        </div>
    </div>
    <div class="box printMargin">
        <h4 class="printHide">Status</h4>
        <h4 class="printShow">Status</h4>
        <hr>
        <span class="titulo">Prioridade: </span><span id="ocorr_prioridade"><?php echo $linhaOcorrencia->getPrioridade(); ?></span>
        <span class="titulo">Analisado: </span><span id="ocorr_analisado"><?php echo ($linhaOcorrencia->getAnalisado() == 1) ? 'Sim':'Não'; ?></span>
        <span class="titulo">Congelado: </span><span id="ocorr_congelado"><?php echo ($linhaOcorrencia->getCongelado()== 1) ? 'Sim':'Não'; ?></span>
        <span class="titulo">Encerrado: </span><span id="ocorr_encerrado"><?php echo ($linhaOcorrencia->getEncerrado()== 1) ? 'Sim':'Não'; ?></span>
        <br><br>
    </div>
    <div class="box div-informacoes">
        <h4 class="printHide">Informações</h4>
        <h4 class="printShow">Informações</h4>
        <hr>
        <?php  
        if($usuario_editor == false){
            $usuario_editor = $linhaAgentePrincipal;
        };
        ?>
        <span class="titulo">Ativa: </span><span id="ativa"><?php echo ($linhaOcorrencia->getAtivo() == 't') ? 'Sim':'Não'; ?></span><br>
        <span class="titulo">Data de alteração: </span><span id="data_alteracao"><?php echo date("d/m/Y", strtotime($linhaOcorrencia->getDataAlteracao())); ?></span><br>
        <span class="titulo">Usuário que realizou a alteração: </span><span class="printShoww"><?php echo $usuario_editor->getNome(); ?></span><a id="usuario_criador" class="printHide" href="?pagina=exibirUsuario&id=<?php echo  $usuario_editor->getId(); ?>"><?php echo $usuario_editor->getNome(); ?></a><br>
        <span class="titulo">Chamado de referência: </span>
            <?php if($linhaOcorrencia->getChamadoId() == null){ ?>
                <span id="ocorr_referencia"><?php echo 'Não possui'; ?></span><br>
            <?php }else{ ?>
            <span class="printShoww"><?php echo $linhaOcorrencia->getChamadoId();?></span>
                <a class="printHide" id="ocorr_referencia" href="?pagina=exibirChamado&id=<?php echo $linhaOcorrencia->getChamadoId(); ?>"><?php echo $linhaOcorrencia->getChamadoId(); ?></a><br>
            <?php }?>
        <br>
    </div>  

    <?php if($linhaOcorrencia->getPossuiFotos() == true){ ?>
    <div class="box printHide">
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
    <?php }
    if($linhaOcorrencia->getPossuiFotos() == true){ ?>
        <div class="print-img-area printShow page-break-always">
            <?php for($i = 0; $i < sizeof($fotos); $i++){
            echo '<img class="image-print" src="data:image/png;base64,' . $fotos[$i] .'">';
            } ?>
        </div>
    <?php } ?>
    <div class="printShoww" style="display: flex; flex-direction:column; align-items:center;">
        <div style="margin-bottom: 1px solid black;">
        <span class="printShow" style="margin-top:40px;">_____________________</span>
        </div>
       <span class="printShow" >Assinatura</span>
    </div>
    <div class="hide mapModal" id="map" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" onclick="fecharMapa()" class="close">&times;</button>
                    <h5 class="modal-title">Mapa</h5>
                </div>
                <div class="modal-body">
                    <div id="googleMap" style="width:100%;height:400px;"></div>
                </div>
            </div>
        </div>
    </div>

    <?php if($linhaOcorrencia->getAtivo() == true){ ?>
        <div style="display: flex; justify-content:space-between"> 
        <?php if(!$id_notificacao){ ?>
            <a class="printHide" href="index.php?pagina=cadastrarNotificacao&id=<?php echo $id_ocorrencia; ?>"><input class="btn btn-default" value="Gerar notificação"></a>
        <?php }else{ ?>
            <a class="printHide" href="index.php?pagina=exibirNotificacao&id=<?php echo $id_notificacao; ?>"><input class="btn btn-default" value="Verificar Notificação"></a>   
        <?php } ?>
        <a class="printHide" href="index.php?pagina=editarOcorrencia&id=<?php echo $id_ocorrencia; ?>"><input type="button" class="btn btn-default btn-md printHide" style="position:relative;left:30%; color:#000000;" value="Editar"></a>
            <?php if(!$id_interdicao){ ?>
 
                <a class="printHide" href="index.php?pagina=cadastrarInterdicao&id=<?php echo $id_ocorrencia; ?>"><input class="btn btn-default" value="Gerar interdição"></a>

            <?php }else{ ?>
            <a href="index.php?pagina=exibirInterdicao&id=<?php echo $id_interdicao; ?>" class="btn btn-default btn-md btn-interdicao printHide">Verificar Interdição</a>
        <?php } ?>
        </div>
    <?php } ?>



</div>
</div>
