    <?php
    include 'database.php';
    require_once 'dao/ChamadoDaoPgsql.php';
    require_once 'dao/EnderecoDaoPgsql.php';
    require_once 'dao/UsuarioDaoPgsql.php';
    require_once 'dao/PessoaDaoPgsql.php';

    $pessoadao = New PessoaDaoPgsql($pdo);
    $usuariodao = new UsuarioDaoPgsql($pdo);
    $enderecodao = new EnderecoDaoPgsql($pdo);
    $chamadodao = new ChamadoDaoPgsql($pdo);
    
    session_start();

    $id_chamado = $_GET['id'];


    $linhaChamado = $chamadodao->buscarPeloId($id_chamado);
    $idpessoa = $linhaChamado->getPessoaId();
    
    if($linhaChamado->getEnderecoPrincipal() == "Logradouro"){
        $id_logradouro = $linhaChamado->getLogradouroId();

        $linhaLogradouro = $enderecodao->buscarPeloId($id_logradouro);
    }else{
        $id_coordenada = $linhaChamado->getIdCoordenada();
        $linhaCoordenada = $enderecodao->buscarIdCoordenada($id_coordenada);
    }


    $id_agente = $linhaChamado->getAgenteId();
    if($id_agente != ""){
        $linhaAgente = $usuariodao->findById($id_agente);
    }

    $id_distribuicao = $linhaChamado->getDistribuicao();

    if($id_distribuicao != ""){
        $linhaDistribuicao = $usuariodao->findById($id_distribuicao);
    }

    $string = $linhaChamado->getFotos();

    $string = str_replace('{','',$string);
    $string = str_replace('}','',$string);

    $fotos = explode(',', $string);

?>

<div class="container positioning">
<div class="jumbotron campo_cadastro">
    <div class="box">
        <div class="row cabecalho">
            <div class="col-sm-6 printHide">
                <nav class="texto-cabecalho">Estado de Santa Catarina</nav>
                <nav class="texto-cabecalho">Prefeitura de Balneário Camboriú</nav>
                <nav class="texto-cabecalho">Secretaria de segurança</nav>
                <nav class="texto-cabecalho">Defesa Civil</nav>
            </div>
            <div class="col-sm-6 print-chamado-img printHide">
                <img src="images/balneario-camboriu.png" alt="prefeitura-balneario-camboriu" class="img-cabecalho">
            </div>
        </div>
        <div class="printShow divPrintHeader row">
            <img class="printShow" src="images/logo.jpg" style="width: 40px;">
            <h3 class="chamadoTitlePrint printShow">Registro de chamado</h3>
            <img src="images/balneario-camboriu.png" alt="prefeitura-balneario-camboriu" class="img-cabecalho printShow" style="width: 120px;">
        </div>
        <h3 class="text-center printHide">Registro de chamado</h3>
        <button class="printHide" style="background-color: white; border:none;" onclick="print()"><img src="images/print.png" style="width: 50px; height:auto"></button>
        <h4 class="printHide">Endereço</h4>
        <h2 class="printShow titulo" style="margin-bottom: 10px;">Endereço</h3>
        <span class="titulo hide">Endereço principal: </span><span class="hide" id="coordenada_principal" ng-model="sel_endereco" ng-init="sel_endereco='<?php echo $linhaChamado->getEnderecoPrincipal(); ?>'"><?php echo $linhaChamado->getEnderecoPrincipal(); ?></span>
        <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ ?>
        <div style="margin-bottom: none;" ng-show="sel_endereco == 'Logradouro'">
            <div class="row">
                <div class="col-sm-7"><span class="titulo">CEP: </span><?php echo $linhaLogradouro->getCep(); ?></div>
                <div class="col-sm-7"><span class="titulo">Logradouro: </span><?php echo $linhaLogradouro->getLogradouro(); ?></div>
                <div class="col-sm-3"><span class="titulo">Número: </span><?php echo $linhaLogradouro->getNumero(); ?></div>
            </div>
            <div class="row">
                <div class="col-sm-7"><span class="titulo">Cidade: </span><?php echo $linhaLogradouro->getCidade(); ?></div>
                <div class="col-sm-6"><span class="titulo">Bairro: </span><?php echo $linhaLogradouro->getBairro(); ?></div>
            </div>
            <nav><span class="titulo">Referência: </span><?php echo $linhaLogradouro->getReferencia(); ?></nav><br>
        </div>
        <?php };
        if($linhaChamado->getEnderecoPrincipal() == 'Coordenada'){?>
        <div style="margin-bottom: none;" ng-show="sel_endereco == 'Coordenada'">
            <nav>
                <span class="titulo">Latitude: </span><span id="latitude" ><?php echo $linhaCoordenada->getLatitude() ?></span>
            </nav>
            <nav class="inline">
                <span class="titulo">Longitude: </span><span id="longitude" ><?php echo $linhaCoordenada->getLongitude() ?></span>
            </nav>
            <button type="button" class="btn-default btn-small inline printHide" onclick="abrirMapa()"><span class="glyphicon glyphicon-map-marker"></span></button>
        </div>
        <?php }?>
    <hr>
        <h4 class="printHide">Ocorrência</h4>
        <h2 class="printShow titulo" style="margin-bottom: 10px;">Ocorrência</h3>
        <nav>
            <span class="titulo">Criador:</span><span><a class="printHide" href="?pagina=exibirUsuario&id=<?php echo $linhaChamado->getAgenteId(); ?>    "><?php echo $linhaAgente->getNome(); ?></a></span><br>
            <span class="titulo">Data e hora: </span>
            <span><?php echo date("d/m/Y H:i", strtotime($linhaChamado->getData())); ?></span><br>
            <span class="titulo">Origem: </span><span id="ocorr_origem"><?php echo $linhaChamado->getOrigem(); ?></span><br>
            <span class="titulo">Descrição: </span><br>
            <textarea name="descricao" rows="5" readonly class="readtextarea"><?php echo $linhaChamado->getDescricao(); ?></textarea><br>
            <span class="titulo">Distribuído para:</span>
            <?php if($linhaChamado->getDistribuicao() != NULL){ ?>
            <span class="printShoww"> <?php echo $linhaDistribuicao->getNome(); ?></span><a id="distribuicao" class="printHide" href="?pagina=exibirUsuario&id=<?php echo $linhaChamado->getDistribuicao(); ?>"><?php echo $linhaDistribuicao->getNome(); ?></a>
            <?php }else{ ?>
            <span style="margin-top: 5px;">Nenhuma distribuição cadastrada.</span>
            <?php } ?>
        </nav>
    <hr>
        <h4 class="printHide">Solicitante</h4>
        <h2 class="printShow titulo" style="margin-bottom: 10px;">Solicitante</h3>
        <nav>
            <?php if($idpessoa){ ?>
            <?php 
            $linhaPessoa1 = $pessoadao->buscarPeloID($idpessoa);
            $contato =  $linhaPessoa1->getCelular();
            if( $contato == ''){
                $contato = $linhaPessoa1->getTelefone();
            }
            ?>
            
            <div class="row">
            <span class="titulo">Pessoa atendida: </span> <a href="" class="open-AddBookDialog printHide" data-toggle="modal" data-id="pessoa_nome1"><span><?php echo $linhaChamado->getNomePessoa();?></span></a><span class="printShow"><?php echo $linhaChamado->getNomePessoa(); ?></span>
            <span class="titulo printShow">Contato: </span> <span class="printShow"><?php echo $contato ?></span>
            </div>
            <?php  }else{ ?>
            Nenhuma pessoa cadastrada.
            <?php } ?>
        </nav>
    <hr style="margin-bottom:0;">
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
    <?php if(($fotos[0]) != "" && $fotos[0] != null){?>
        <div class="box printHide">
        <div id="myCarousel" class="carousel slide limite" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators printHide">
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
            <a class="left carousel-control printHide" href="#myCarousel" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control printHide" href="#myCarousel" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>


    <div class="print-img-area printShow">
        <?php for($i = 0; $i < 3; $i++){
        echo '<img class="image-print-chamado"  src="data:image/png;base64,' . $fotos[$i] .'">';
        } ?>
    </div>
    <?php }?>
    <div class="printShoww">
        <div style="margin-top:20px; display:flex;">
        Assinatura:
        <div style="border-bottom: 1px solid black; width: 40%;"></div>
        </div>

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
    <div class="row">
    <div class="col-sm-6">
        <?php if($linhaChamado->getUsado() == false && ($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2)){ ?>
        <form action="cancelarChamado.php" method="post">
            <!--<input name="id_chamado" type="hidden" value="<?php //echo $id_chamado; ?>">
            <input type="submit" class="btn btn-default btn-md" value="Cancelar chamado">-->
            <button type="button" class="btn btn-default btn-md open-AddBookDialog btn-cancelar-chamado printHide" data-toggle="modal" data-id="motivo" style="left:60%">Cancelar</button>
        </form>
        <?php } ?>
    </div>
    <div class="col-sm-3">
        <?php if($linhaChamado->getUsado() == false){ ?>
            <form action="index.php?pagina=cadastrarOcorrencia" method="post">
                <input name="id_chamado" type="hidden" value="<?php echo $id_chamado; ?>">
                <input name="endereco_principal" type="hidden" value="<?php echo $linhaChamado->getEnderecoPrincipal(); ?>">
                <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ ?>
                <input name="cep" type="hidden" value="<?php echo $linhaLogradouro->getCep(); ?>">
                <input name="cidade" type="hidden" value="<?php echo $linhaLogradouro->getCidade(); ?>">
                <input name="bairro" type="hidden" value="<?php echo $linhaLogradouro->getBairro(); ?>">
                <input name="logradouro" type="hidden" value="<?php echo $linhaLogradouro->getLogradouro(); ?>">
                <input name="numero" type="hidden" value="<?php echo $linhaLogradouro->getNumero() ?>">
                <input name="referencia" type="hidden" value="<?php echo $linhaLogradouro->getReferencia(); ?>">
                <?php }else{ ?>
                <input name="id_coordenada" type="hidden" value="<?php echo $id_coordenada; ?>">
                <?php } ?>
                <input name="data_ocorrencia" type="hidden" value="<?php echo date("Y-m-d", strtotime($linhaChamado->getData())); ?>">
                <input name="descricao" type="hidden" value="<?php echo $linhaChamado->getDescricao(); ?>">
                <input name="ocorr_origem" type="hidden" value="<?php echo $linhaChamado->getOrigem(); ?>">
                <input name="pessoa_atendida_1" type="hidden" value="<?php echo $linhaChamado->getNomePessoa(); ?>">
                <input name="agente_principal" type="hidden" value="<?php echo $linhaAgente->getNome(); ?>">
                <input type="submit" class="btn btn-default btn-md printHide" value="Gerar Ocorrência">
            </form>
        <?php } ?>
    </div>
    </div>
    <div class="modal fade" id="cancelarModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Cancelar chamado</h5>
                </div>
                <form action="cancelarChamado.php" method="post">
                    <div class="modal-body">
                        <nav>
                            <div class="row">
                                <div class="col-sm-12">
                                    Motivo: <span style="color:red;">*</span>
                                    <textarea id="motivo" name="motivo" class="form-control" cols="10" rows="3" maxlength="255" required></textarea>
                                    <input name="id_chamado" type="hidden" value="<?php echo $id_chamado; ?>">
                                </div>
                            </div>
                        </nav>
                    </div>
                    <div class="modal-footer">
                        <button>Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>