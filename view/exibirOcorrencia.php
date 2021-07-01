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

<div class="printAreaOcorrencia printShow">
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
            <h3 class=""><?php echo 'Ocorrência Nº ' . $id_ocorrencia . '/' . date('Y'); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 borderPrint">
            <h5 class="printShow"><strong>Endereço:</strong><?php echo $linhaLogradouro->getLogradouro() . ',' . $linhaLogradouro->getNumero(); ?></h5> 
        </div>
        <div class="col-sm-6 borderPrint">
            <h5><strong>Bairro:</strong><?php echo $linhaLogradouro->getBairro(); ?></h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 borderPrint" >
            <h5><strong>Assunto:</strong> <?php echo $linhaOcorrencia->getTitulo(); ?></h5>
        </div>
        <div class="col-sm-6 borderPrint">
            <h5><strong>Origem:</strong><?php echo $linhaOcorrencia->getOrigem(); ?></h5> 
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 descricaoOcorrenciaPrint">
            <h5><strong>Chamado Nº:  </strong><?php if($linhaOcorrencia->getChamadoId()){echo $linhaOcorrencia->getChamadoId();}else{ echo 'Não possui chamado';} ?></h5>
            
        </div>
    </div>
    <div class="row">
        <div class="descricaoOcorrenciaPrint text-center">
            <h4><strong>DESCRIÇÃO DA OCORRÊNCIA</strong></h4>
        </div>
    </div>
    <div class="row divDescricaoArea">
        <p align="justify"><?php echo $linhaOcorrencia->getDescricao(); ?></p>
    </div><hr>
    
    <div class="row">
        <?php if($linhaOcorrencia->getPossuiFotos() == true){ ?>
            <div class="print-img-area printShow">
            <?php 
                if(sizeof($fotos) > 4){
                    for($i = 0; $i < 4; $i++){
                        echo '<img class="image-print" src="data:image/png;base64,' . $fotos[$i] .'">';
                    }
                }else{
                    for($i = 0; $i < sizeof($fotos); $i++){
                        echo '<img class="image-print" src="data:image/png;base64,' . $fotos[$i] .'">';
                    }
                }       
 ?>
            </div>
        <?php } ?>
    </div>
    <div class="row">
            <div class="borderPrint text-center">
                BALNEÁRIO CAMBORIÚ
            </div>
            <div class="borderPrint text-center">
                Data da ocorrência: <?php echo date("d/m/Y", strtotime($linhaOcorrencia->getData())); ?>
            </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <label><strong>Solicitante:</strong></label>
         <?php if($linhaOcorrencia->getPessoa1()){
                if($linhaPessoa1){
                    echo $linhaPessoa1->getNome();
                }else{
                    echo $linhaOcorrencia->getPessoa1();
                }
         }else{
             echo 'Pessoa não informada';
         }
         ?><br>
        <label>Telefone:</label> <?php if($linhaOcorrencia->getPessoa1()){ 
            if($linhaPessoa1){
                echo $linhaPessoa1->getCelular();
            }else{
                echo 'Telefone não informado';
            }
        }?>
        </div>
        <div class="col-sm-10 borderPrint"><br>
            <label><strong>Ass:</strong></label>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <label><strong>Representante da Defesa Civil:</strong></label><br>
            <?php echo $linhaAgentePrincipal->getNome(); ?>
        </div>
        <div class="col-sm-10 borderPrint"><br>
            <label><strong>Ass:</strong></label>
        </div>
    </div>

</div>
<div class="container positioning printHide">
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
        <div class="row cabecalho">
            <div class="col-sm-6 printHide">
                <nav class="texto-cabecalho">Estado de Santa Catarina</nav>
                <nav class="texto-cabecalho">Prefeitura de Balneário Camboriú</nav>
                <nav class="texto-cabecalho">Secretaria de segurança</nav>
                <nav class="texto-cabecalho">Defesa Civil</nav>
            </div>
            <div class="col-sm-6">
                <img src="images/logo_bc.png" alt="prefeitura-balneario-camboriu" class="img-cabecalho">
            </div>
        </div>
        <div>
            <h3 class="text-center"><?php echo 'Ocorrência Nº ' . $id_ocorrencia . '/' . date('Y'); ?></h3>
        </div>
        <button style="border:none;" onclick="print()"><img src="images/print.png" style="width: 50px; height:auto"></button>
        <div class="box">
            <h4>Endereço</h4>
            <div class="hidden"><span class="titulo">Localizar por: </span><span id="coordenada_principal" ng-model="sel_endereco" ng-init="sel_endereco='<?php echo $linhaOcorrencia->getEnderecoPrincipal(); ?>'"><?php echo $linhaOcorrencia->getEnderecoPrincipal(); ?></span></div>
            <?php if($linhaOcorrencia->getEnderecoPrincipal() == "Logradouro"){ ?>
                <div class="row">
                    <div class="col-sm-6">
                        <label>Cidade:</label>
                        <input class="form-control" readonly value="<?php echo $linhaLogradouro->getCidade(); ?>">
                    </div>
                    <div class="col-sm-6">
                        <label>Bairro:</label>
                        <input class="form-control" readonly value="<?php echo $linhaLogradouro->getBairro(); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>CEP:</label>
                        <input class="form-control" readonly value="<?php echo $linhaLogradouro->getCep(); ?>">
                    </div>
                    <div class="col-sm-6">
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
                        <label>Referência:</label>
                        <input class="form-control" readonly value="<?php echo $linhaLogradouro->getComplemento(); ?>">
                    </div>
                </div>
            <?php }if($linhaOcorrencia->getEnderecoPrincipal() == "Coordenada"){ ?>
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
                        <button type="button" class="btn-default btn-small inline" onclick="abrirMapa(), myMap()"><span class="glyphicon glyphicon-map-marker"></span></button>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="box">
                <h4>Agentes</h4>
                <div class="row">
                    <div class="col-sm-6">
                        <label>Agente principal:</label>
                        <a id="agente_principal" href="?pagina=exibirUsuario&id=<?php echo $linhaOcorrencia->getIdCriador(); ?>">
                        <input class="form-control pointer" readonly value="<?php echo $linhaAgentePrincipal->getNome(); ?>">
                        </a>
                    </div>
                    <?php if($linhaOcorrencia->getApoio1()){ ?>
                    <div class="col-sm-6">
                        <label>Agente de apoio 1:</label>
                        <a id="agente_principal" href="?pagina=exibirUsuario&id=<?php echo $linhaOcorrencia->getApoio1(); ?>">
                        <input class="form-control pointer" readonly value="<?php echo $linhaAgente1->getNome(); ?>"></a>
                    </div>
                    <?php } ?>
                </div>
                <?php if($linhaOcorrencia->getApoio2){ ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Agente de apoio 2:</label>
                            <a id="agente_principal" href="?pagina=exibirUsuario&id=<?php echo $linhaOcorrencia->getApoio2(); ?>">
                            <input class="form-control pointer" readonly value="<?php echo $linhaAgente2->getNome(); ?>"></a>
                        </div>
                    </div>
                <?php } ?>
        </div>

        <div class="box">
            <h4>Ocorrência:</h4>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Título:</label>
                            <input class="form-control" id="ocorr_titulo" readonly value="<?php echo $linhaOcorrencia->getTitulo(); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Data da ocorrência:</label>
                            <input class="form-control" id="data_ocorrencia" readonly value="<?php echo date("d/m/Y", strtotime($linhaOcorrencia->getData())); ?>">
                        </div>
                        <div class="col-sm-6">
                            <label>Origem:</label>
                            <input class="form-control" id="ocorr_origem" readonly value="<?php echo $linhaOcorrencia->getOrigem(); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Descrição:</label>
                            <textarea id="ocorr_descricao" rows="5" readonly class="readtextarea"><?php echo $linhaOcorrencia->getDescricao(); ?></textarea>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Cobrade</label>
                            <span id="ocorr_cobrade" ><?php echo $linhaCobrade['subgrupo']; ?></span>
                        </div>
                        <div class="col-sm-6">
                            <label>Possui fotos:</label>
                            <span id="fotos"><?php echo ($linhaOcorrencia->getPossuiFotos() == 1) ? 'Sim':'Não'; ?></span>
                        </div>
                    </div>
        </div>
        <div class="box">
            <?php if($linhaOcorrencia->getIdPessoa1() || $linhaOcorrencia->getIdPessoa2()){?>
                <h4>Solicitantes</h4> 
            <?php }else{ ?>
                <h4>Solicitantes</h4>
            <?php } ?>
            <?php if(!$linhaOcorrencia->getIdPessoa1() && !$linhaOcorrencia->getIdPessoa2()){
                    if($linhaOcorrencia->getPessoa1() || $linhaOcorrencia->getPessoa2()){ ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Solicitante:</label>
                                <input class="form-control" readonly value="<?php echo $linhaOcorrencia->getPessoa1(); ?>">
                            </div>
                        </div>
                        <span class="titulo">Solicitante:</span> 
                        <?php echo $linhaOcorrencia->getPessoa1(); ?>
                    <?php }else{
                    echo '<span class="titulo">Nenhuma pessoa foi cadastrada</span><br>';
                    } ?>
            <?php }else{ ?>
                <div class="row">
                    <div class="col-sm-6">
                        <label>Solicitante 1:</label>
                        <input class="form-control pointer open-AddBookDialog" data-toggle="modal" data-id="pessoa_nome1" readonly value="<?php echo $linhaOcorrencia->getPessoa1(); ?>">
                    </div>
                </div>
                <?php if($linhaOcorrencia->getIdPessoa2() != ""){ ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Solicitante 2:</label>
                            <input class="form-control pointer open-AddBookDialog" data-toggle="modal" data-id="pessoa_nome2" readonly value="<?php echo $linhaPessoa2->getNome(); ?>">
                        </div>
                    </div>
                <?php } 
            }?>      
        </div>
        <div class="box">
            <h4>Status</h4>
            <div class="row">
                <div class="col-sm-3">
                    <label>Prioridade:</label>
                    <input class="form-control" id="ocorr_prioridade" readonly value="<?php echo $linhaOcorrencia->getPrioridade(); ?>">
                </div>
                <div class="col-sm-3">
                    <label>Analisado:</label>
                    <input class="form-control" id="ocorr_analisado" readonly value="<?php echo ($linhaOcorrencia->getAnalisado() == 1) ? 'Sim':'Não'; ?>">
                </div>
                <div class="col-sm-3">
                    <label>Congelado:</label>
                    <input class="form-control" id="ocorr_congelado" readonly value="<?php echo ($linhaOcorrencia->getCongelado()== 1) ? 'Sim':'Não'; ?>">
                </div>
                <div class="col-sm-3">
                    <label>Encerrado:</label>
                    <input class="form-control" id="ocorr_encerrado" readonly value="<?php echo ($linhaOcorrencia->getEncerrado()== 1) ? 'Sim':'Não'; ?>">
                </div>
            </div>
        </div>
            <div class="box">
                <h4>Informações</h4>           
                <?php  
                    if($usuario_editor == false){
                        $usuario_editor = $linhaAgentePrincipal;
                };?>
                <div class="row">
                    <div class="col-sm-6">
                        <label>Ativa:</label>
                        <input class="form-control" id="ativa" readonly value="<?php echo ($linhaOcorrencia->getAtivo() == 't') ? 'Sim':'Não'; ?>">
                    </div>
                    <div class="col-sm-6">
                        <label>Chamado de referência:</label>
                        <?php if($linhaOcorrencia->getChamadoId() == null){ ?>
                            <input class="form-control pointer" id="ocorr_referencia" readonly value="<?php echo 'Não possui'; ?>">
                        <?php }else{ ?>
                            <a href="?pagina=exibirChamado&id=<?php echo $linhaOcorrencia->getChamadoId(); ?>">
                            <input class="form-control pointer" id="ocorr_referencia" readonly value="<?php echo $linhaOcorrencia->getChamadoId(); ?>">
                            </a>
                        <?php }?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>Data de alteração:</label>
                        <input class="form-control" id="data_alteracao" readonly value="<?php echo date("d/m/Y", strtotime($linhaOcorrencia->getDataAlteracao())); ?>">
                    </div>
                    <div class="col-sm-6">
                        <label>Usuario que realizou a alteração:</label>      
                        <a  href="?pagina=exibirUsuario&id=<?php echo  $usuario_editor->getId(); ?>">
                        <input id="usuario_criador" class="form-control pointer" readonly value="<?php echo $usuario_editor->getNome(); ?>">
                        </a>            
                    </div>
                </div>
            </div>
            <?php if($linhaOcorrencia->getAtivo() == true){ ?>
                <div class="row"> 
                    <?php if(!$id_notificacao){ ?>
                        <div class="col-sm-4">
                            <a href="index.php?pagina=cadastrarNotificacao&id=<?php echo $id_ocorrencia; ?>">
                            <input class="btn btn-default" value="Gerar notificação"></a>
                        </div>
                    <?php }else{ ?>
                        <div class="col-sm-4">
                            <a href="index.php?pagina=exibirNotificacao&id=<?php echo $id_notificacao; ?>">
                            <input class="btn btn-default" value="Verificar Notificação"></a>  
                        </div>
                    <?php } ?>
                    <div class="col-sm-4">
                        <a  href="index.php?pagina=editarOcorrencia&id=<?php echo $id_ocorrencia; ?>">
                            <input class="btn btn-default"  value="Editar">
                        </a>
                    </div>
                    <?php if(!$id_interdicao){ ?>
                        <div class="col-sm-4">
                        <a href="index.php?pagina=cadastrarInterdicao&id=<?php echo $id_ocorrencia; ?>">
                            <input class="btn btn-default" value="Gerar interdição">
                        </a>
                        </div>
                    <?php }else{ ?>
                    <div class="col-sm-4">
                        <a href="index.php?pagina=exibirInterdicao&id=<?php echo $id_interdicao; ?>">
                        <input class="btn btn-default" value="Verificar Interdição">
                        </a>
                    </div>

                    <?php } ?>
                </div>
            <?php } ?>
    </div> 
    <div class="box">
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
                                </div>
                        </div>
                    </div>
            </div>
        <?php } ?>
        <?php if($linhaPessoa2 !== null){ ?>
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
    <?php if($linhaOcorrencia->getPossuiFotos() == true){ ?>
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

    </div>
</div>
