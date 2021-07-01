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

    $barras = array("{","}");
    $string = str_replace($barras,'',$string);

    $fotos = explode(',', $string);

    if($linhaChamado->getPossuiFotos() == false){
        $possui_fotos = false;
    }else{
        $possui_fotos = true;
    }

    if($linhaChamado->getNomePessoa() !== ''){ 
            if($idpessoa){
                $linhaPessoa1 = $pessoadao->buscarPeloID($idpessoa);
                $contato =  $linhaPessoa1->getCelular();
                $telefone = $linhaPessoa1->getTelefone();
                if( $contato == ''){
                    $contato = $linhaPessoa1->getTelefone();
                }
            }
    }

?>

<div class="printAreaChamado printShow">
<title>Teste pdf</title>
    <div class="printHeaderNotificacao row">
        <div class="divHeaderNotificacaoTexto">
            <p class="pMarg">Estado de Santa Catarina</p>
            <p class="pMarg">Prefeitura de Balneário Camboriú</p>
            <p class="pMarg">Defesa Civil</p>
        </div>
        <div></div>
        <div class="imgPrintAreaNotificacao">
            <img src="images/logo_bc.png" alt="prefeitura-balneario-camboriu" class="img-cabecalho" style="width: 180px;">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint100 text-center">
            <h3 class=""><?php echo 'Chamado Nº ' . $id_chamado . '/' . date('Y'); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <h5><strong>Rua:</strong></h5>
            <?php echo $linhaLogradouro->getLogradouro(); ?>
        </div>
        <div class="col-sm-10 borderPrint">
            <h5><strong>Número:</strong></h5>
            <?php echo $linhaLogradouro->getNumero(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <h5><strong>Bairro:</strong></h5>
            <?php echo $linhaLogradouro->getBairro(); ?>
        </div>
        <div class="col-sm-10 borderPrint">
            <h5><strong>Cidade:</strong></h5>
            <?php echo $linhaLogradouro->getCidade(); ?>
        </div>
    </div>
    <?php if($linhaLogradouro->getReferencia()){  ?>
        <div class="row">
            <div class="borderPrint100 col-sm-10">
                <h5><strong>Referência:</strong></h5>
                <?php echo $linhaLogradouro->getReferencia(); ?>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <h5><strong>Data de abertura:</strong></h5>
            <?php echo date('d/m/Y', strtotime($linhaChamado->getData()));  ?>
        </div>
        <div class="col-sm-10 borderPrint">
            <h5><strong>Origem:</strong></h5>
            <?php echo $linhaChamado->getOrigem(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <h5><strong>Criador:</strong></h5>
            <?php echo $linhaAgente->getNome(); ?>
        </div>
        <div class="col-sm-10 borderPrint">
            <h5><strong>Distribuído para:</strong></h5>
            <?php echo $linhaDistribuicao->getNome(); ?>
        </div>
    </div>
    <div class="row">
    <div class="col-sm-12 borderPrint100">
        <h5><strong>Prioridade:</strong></h5>
        <?php echo $linhaChamado->getPrioridade(); ?>
    </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint100 text-center">
            <h4><strong>Descrição do chamado</strong></h4>
        </div>
    </div>
    <div class="row divDescricaoArea">
        <p align="justify"><?php echo $linhaChamado->getDescricao(); ?></p>
    </div>
    <div class="row">
        <div class="print-img-area printShow">
            <?php 
            if($linhaChamado->getPossuiFotos()){
                if(sizeof($fotos) < 4){
                    for($i = 0; $i < sizeof($fotos); $i++){
                        echo '<img class="image-print"  src="data:image/png;base64,' . $fotos[$i] .'">';
                    }
                }else{
                    for($i = 0; $i < 4; $i++){
                        echo '<img class="image-print"  src="data:image/png;base64,' . $fotos[$i] .'">';
                        }
                }  
            }
            ?>
        </div>
    </div>  
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <label><strong>Solicitante:</strong></label><br>
            <?php 
                if($linhaChamado->getNomePessoa() !== ''){
                    if($linhaChamado->getPessoaId()){
                        echo $linhaPessoa1->getNome(); if($linhaPessoa1->getCelular()) echo ' - ' . $linhaPessoa1->getCelular();
                    }else{
                        echo $linhaChamado->getNomePessoa();
                    }
                }else{
                    echo 'Nenhuma pessoa cadastrada.';
                }
            ?>
        </div>
        <div class="col-sm-10 borderPrint"><br>
            <label><strong>Ass:</strong></label>
        </div>
    </div>
</div>

<div class="container positioning printHide">
<div class="jumbotron campo_cadastro">
<?php if(isset($_GET['sucesso'])) { ?>
        <div class="alert alert-success printHide" role="alert">
            Chamado cadastrado com sucesso.
        </div>
    <?php } ?>
    <?php if(isset($_GET['sucessoEdit'])) { ?>
        <div class="alert alert-success printHide" role="alert">
            Chamado alterado com sucesso.
        </div>
    <?php } ?>
        <div class="row cabecalho">
            <div class="col-sm-6">
                <nav class="texto-cabecalho">Estado de Santa Catarina</nav>
                <nav class="texto-cabecalho">Prefeitura de Balneário Camboriú</nav>
                <nav class="texto-cabecalho">Secretaria de segurança</nav>
                <nav class="texto-cabecalho">Defesa Civil</nav>
            </div>
            <div class="col-sm-6 print-chamado-img">
                <img src="images/logo_bc.png" alt="prefeitura-balneario-camboriu" class="img-cabecalho">
            </div>
        </div>
        <h3 class="text-center "><?php echo 'Chamado Nº ' . $id_chamado  .'/'. date('Y'); ?></h3>
        <button style="border:none;" onclick="print()"><img src="images/print.png" style="width: 50px; height:auto"></button>
        <div class="box">
            <h4>Endereço</h4>
            <span class="titulo hide">Endereço principal: </span><span class="hide" id="coordenada_principal" ng-model="sel_endereco" ng-init="sel_endereco='<?php echo $linhaChamado->getEnderecoPrincipal(); ?>'"><?php echo $linhaChamado->getEnderecoPrincipal(); ?></span>
            <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ ?>
                <div class="row">
                    <div class="col-sm-6">
                        <label>Cidade:</label>
                        <input class="form-control" readonly value="<?php echo $linhaLogradouro->getCidade(); ?>">
                    </div>
                    <div class="col-sm-6">
                        <label>Bairro: </label>
                        <input class="form-control" readonly value="<?php echo $linhaLogradouro->getBairro(); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>CEP:</label>
                        <input class="form-control" readonly value="<?php echo $linhaLogradouro->getCep(); ?>">
                    </div>
                    <div class="col-sm-8">
                        <label>Endereço:</label>
                        <input class="form-control" readonly value="<?php echo $linhaLogradouro->getLogradouro(); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Número:</label>
                        <input class="form-control" readonly value="<?php echo $linhaLogradouro->getNumero(); ?>">
                    </div>
                    <div class="col-sm-8">
                        <label>Referência:</label>
                        <input class="form-control" readonly value="<?php echo $linhaLogradouro->getReferencia(); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label>Complemento:</label>
                        <input class="form-control" readonly value="<?php echo $linhaLogradouro->getComplemento(); ?>">
                    </div>
                </div>
            <?php };
            if($linhaChamado->getEnderecoPrincipal() == 'Coordenada'){?>
                <div class="row">
                    <div class="col-sm-5">
                        <label>Latitude:</label>
                        <input class="form-control" id="latitude" readonly value="<?php echo $linhaCoordenada->getLatitude() ?>">
                    </div>
                    <div class="col-sm-5">
                        <label>Longitude:</label>
                        <input class="form-control" id="longitude" readonly value="<?php echo $linhaCoordenada->getLongitude() ?>">
                    </div>
                    <div class="col-sm-2">
                        <label>Mapa:</label><br>
                        <button type="button" class="btn-default btn-small inline printHide" onclick="abrirMapa()"><span class="glyphicon glyphicon-map-marker"></span></button>
                    </div>
                </div>
            <?php }?>
        </div>
        <div class="box">
            <h4>Ocorrência</h4>
            <div class="row">
               <div class="col-sm-6">
                    <label>Origem:</label>
                    <input class="form-control" readonly value="<?php echo $linhaChamado->getOrigem(); ?>">
                </div>
                <div class="col-sm-6">
                    <label>Data e hora:</label>
                    <input class="form-control" readonly value="<?php echo date("d/m/Y H:i", strtotime($linhaChamado->getData())); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label>Criador:</label>
                    <a href="?pagina=exibirUsuario&id=<?php echo $linhaChamado->getAgenteId(); ?>"><input class="form-control pointer" readonly value="<?php echo $linhaAgente->getNome();?>"></a>
                </div>
                <div class="col-sm-6">
                    <label>Distribuído para:</label>
                    <?php if($linhaChamado->getDistribuicao() != NULL){ ?>
                        <a href="?pagina=exibirUsuario&id=<?php echo $linhaChamado->getDistribuicao() ?>"><input class="form-control pointer" readonly value="<?php echo $linhaDistribuicao->getNome()?>"></a>
                    <?php }else{ ?>
                    <span style="margin-top: 5px;">Nenhuma distribuição cadastrada.</span>
                    <?php } ?>
                </div>
            </div>
            <?php if($linhaChamado->getUsado() == true){ ?>
                <div class="row">
                    <div class="col-sm-6">
                        <label>Data do atendimento:</label>
                        <input class="form-control" readonly value="<?php echo date("d/m/Y", strtotime($linhaChamado->getDataAtendimento() ))?>">
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-sm-6">
                    <label>Prioridade:</label>
                    <input class="form-control" readonly value="<?php echo $linhaChamado->getPrioridade(); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                <label>Descrição do chamado:</label>
                <textarea id="descricao" name="descricao" readonly class="form-control " style="resize:none;" rows="5"><?php echo $linhaChamado->getDescricao() ?></textarea>
                </div>
            </div>
            <h4>Solicitante</h4>
                <div class="row">
                <span class="titulo">Pessoa atendida: </span> <?php if($idpessoa){?>  <a href="" class="open-AddBookDialog" data-toggle="modal" onclick="corrigeTelefone()" data-id="pessoa_nome1"><span id="pessoaNome"><?php echo $linhaPessoa1->getNome();?></span></a><?php } else{ ?><span><?php if($linhaChamado->getNomePessoa() != ''){ echo $linhaChamado->getNomePessoa();}?></span><?php } ?>
                <?php 
                if($idpessoa){ ?><span class="titulo printShow">Contato: </span> <span class="printShow"><?php echo $contato ?></span>
                </div>
                <?php }else{
                    if($linhaChamado->getNomePessoa() == '' || $linhaChamado->getNomePessoa() == null){
                    echo  'Nenhuma pessoa cadastrada.';
                    } ?>
                <?php } ?>
        </div>
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
                    <span id="sucessoEditPessoa" class="alert-sucess" style="color: greenyellow;"></span>
                    <span id="falhaEditPessoa" class="alert-danger" style="color: red;"></span>
                        <nav>
                            <input id="id_pessoa" type="hidden" value="">
                            <input name="idChamado" type="hidden" value="<?php echo $id_chamado; ?>">
                            <input id="pessoa_id" name="pessoa_id" type="hidden" value="<?php echo $linhaChamado->getPessoaId() ?>">
                            <div class="row">
                                <div class="col-sm-12">
                                    Nome:
                                   <?php echo '<input id="nome_pessoa" name="nome_pessoa" type="text" class="form-control" value=" '.$linhaPessoa1->getNome() . ' " readonly > '  ?>
                                </div>
                            </div>   
                            <span id="erroNome" class="alertErro hide">Nome inválido.</span>
                            <div class="row">
                                <div class="col-sm-6">
                                    CPF:
                                    <?php echo '<input id="cpf_pessoa" name="cpf_pessoa" type="text" class="form-control" value="'.$linhaPessoa1->getCPF().'" readonly>' ?>
                                </div>
                                <div class="col-sm-6">
                                    Outros documentos:
                                    <?php echo '<input id="outros_documentos" name="outros_documentos" class="form-control" type="text" value="'.$linhaPessoa1->getOutrosDocumentos().'" readonly>' ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    Celular: 
                                    <?php echo ' <input id="celular_pessoa" name="celular_pessoa" type="text" class="form-control" value="'. $linhaPessoa1->getCelular() .'" readonly >'?>
                                    
                                </div>
                                <div class="col-sm-6">
                                    Telefone Fixo: 
                                    <?php echo ' <input id="telefone_pessoa" name="telefone_pessoa" type="text" class="form-control" value=" ' . $linhaPessoa1->getTelefone() . ' " readonly>'?>
                                </div>
                            </div>
                            <div class="form-group">
                                Email:
                                <?php echo'<input id="email_pessoa" name="email_pessoa" type="email" class="form-control" value="'.$linhaPessoa1->getEmail().'" readonly>' ?>
                            </div>
                            <div class="col-sm-6">
                        </div>
                        </nav>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-6">
                            <input type="button" id="editar_pessoa" class="btn btn-default" onclick="editarPessoa()" value="Editar">
                            <button type="submit" id="salvar_pessoa" class="btn btn-default hidden" onclick="salvarEditPessoa()">Salvar</button>
                        </div>
                    </div>

            </div>
        </div>
    </div>
    <?php }
   ?>
    <?php if(($fotos[0]) != "" && $fotos[0] != null){?>
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

    <?php }?>
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
    <div class="col-sm-5">
        <?php if($linhaChamado->getUsado() == false && ($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2)){ ?>
        <form action="cancelarChamado.php" method="post">
            <!--<input name="id_chamado" type="hidden" value="<?php //echo $id_chamado; ?>">
            <input type="submit" class="btn btn-default btn-md" value="Cancelar chamado">-->
            <button type="button" class="btn btn-default btn-md open-AddBookDialog btn-cancelar-chamado printHide" data-toggle="modal" data-id="motivo" style="left:60%">Cancelar</button>
        </form>
        <?php } ?>
    </div>
    <?php if($linhaChamado->getUsado() == false){ ?>
        <div class="col-sm-2">
        <a href="index.php?pagina=editarChamado&id=<?php echo $id_chamado; ?>"><button class="btn btn-default">Editar</button></a>
        </div>
    <?php } ?>
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
                <input name="complemento" type="hidden" value="<?php echo $linhaLogradouro->getComplemento() ?>">
                <?php }else{ ?>
                <input name="id_coordenada" type="hidden" value="<?php echo $id_coordenada; ?>">
                <input name="latitude" type="hidden" value="<?php echo $linhaCoordenada->getLatitude(); ?>">
                <input name="longitude" type="hidden" value="<?php echo $linhaCoordenada->getLongitude(); ?>">
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