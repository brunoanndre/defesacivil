<?php
    require_once 'dao/NotificacaoDaoPgsql.php';
    require_once 'dao/EnderecoDaoPgsql.php';
    require_once 'dao/OcorrenciaDaoPgsql.php';
    require_once 'dao/UsuarioDaoPgsql.php';

    $usuariodao = New UsuarioDaoPgsql($pdo);
    $ocorrenciadao = New OcorrenciaDaoPgsql($pdo);
    $notificacaodao = New NotificacaoDaoPgsql($pdo);
    $enderecodao = New EnderecoDaoPgsql($pdo);
    $id_notificacao = filter_input(INPUT_GET, 'id');

    $linhaNotificacao = $notificacaodao->buscarPeloId($id_notificacao);
    $linhaEndereco = $enderecodao->buscarPeloId($linhaNotificacao->getIdEndereco());

    $titulo = $ocorrenciadao->buscarPeloId($linhaNotificacao->getIdOcorrencia())->getTitulo();

    $listaUsuarios = $usuariodao->buscarUsuariosAtivos();
    $agente = $usuariodao->findById($linhaNotificacao->getRepresentante());


    ?>

<div class="printAreaNotificacao printShow">
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
            <h3 class=""><?php echo 'Notificação Nº ' . $id_notificacao . '/' . date('Y'); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <h5><strong>Rua:</strong></h5>
            <?php echo $linhaEndereco->getLogradouro(); ?>
        </div>
        <div class="col-sm-10 borderPrint">
            <h5><strong>Número:</strong></h5>
            <?php echo $linhaEndereco->getNumero(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <h5><strong>Bairro:</strong></h5> <?php echo $linhaEndereco->getBairro();?>
        </div>
        <div class="col-sm-10 borderPrint">
            <h5><strong>Localidade:</strong></h5> <?php echo $linhaEndereco->getCidade(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint100">
            <h5><strong>Ocorrência:</strong></h5>
            <?php echo  $linhaNotificacao->getIdOcorrencia()  . ' - ' . $titulo;   ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <h5><strong>Agente representante:</strong></h5>
            <?php if($agente){ echo $agente->getNome();}else{echo 'Nenhum agente informado.';} ?>

        </div>
        <div class="col-sm-10 borderPrint">
            <h5><strong>Notificado:</strong></h5>
            <?php echo $linhaNotificacao->getNotificado(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <h5><strong>Data de emissão:</strong></h5>
            <?php echo date('d/m/Y', strtotime($linhaNotificacao->getDataEmissao())); ?>
        </div>
        <div class="col-sm-10 borderPrint">
            <h5><strong>Data de vencimento:</strong></h5>
            <?php echo date('d/m/Y', strtotime($linhaNotificacao->getDataVencimento())); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint100 text-center">
            <h3><strong>Descrição da notificação</strong></h3>
        </div>
    </div>
    <div class="row divDescricaoArea">
        <p align="justify"><?php echo $linhaNotificacao->getDescricao(); ?></p>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <label><strong>Notificado:</strong></label><br>
            <?php echo $linhaNotificacao->getNotificado(); ?>
        </div>
        <div class="col-sm-10 borderPrint"><br>
            <label><strong>Ass:</strong></label>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <label><strong>Representante da Defesa Civil:</strong></label><br>
            <?php echo $agente->getNome();?>
        </div>
        <div class="col-sm-10 borderPrint"><br>
            <label><strong>Ass:</strong></label>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint pad">
            BALNEARIO CAMBORIÚ
        </div>
        <div class="col-sm-10 borderPrint pad">
            Data e hora de entrega: <br> ________________________________
        </div>
    </div>
</div>

<div class="container positioning printHide">
    <div class="jumbotron campo_cadastro">
            <?php if(isset($_GET['erroDB'])){ ?>
                <div class="alert alert-danger" role="alert">Falha ao editar a notificação, contate o administrador.</div>
            <?php } ?>
            <?php if(isset($_GET['sucesso'])){ ?>
                <div class="alert alert-success" role="alert">Notificação cadastrada com sucesso.</div>
            <?php } ?>
            <?php if(isset($_GET['sucessoEdit'])){ ?>
                <div class="alert alert-success " role="alert">Notificação editada com sucesso.</div>
            <?php } ?>
            <div class="box">
                <div class="row cabecalhoNot">
                    <div class="col-sm-6 ">
                        <nav class="texto-cabecalho">Estado de Santa Catarina</nav>
                        <nav class="texto-cabecalho">Prefeitura de Balneário Camboriú</nav>
                        <nav class="texto-cabecalho">Secretaria de segurança</nav>
                        <nav class="texto-cabecalho">Defesa Civil</nav>
                    </div>
                    <div class="col-sm-6 cbbls ">
                        <img src="images/logo_bc.png" alt="prefeitura-balneario-camboriu" class="img-cabecalho-not">
                    </div>  
                </div>  
                <h3 class="text-center ">Notificação Nº <?php echo $id_notificacao . '/' . date('Y'); ?></h3>
                <button style="background-color: white; border:none;" onclick="print()"><img src="images/print.png" style="width: 50px; height:auto"></button>
                <form action="processa_editarNotificacao.php" method="post" enctype="multipart/form-data">
                <input name="id_notificacao" type="hidden" value="<?php echo $id_notificacao; ?>">
                <input name="id_endereco" type="hidden" value="<?php echo $linhaEndereco->getId(); ?>">
                    <div class="row">
                        <div class="col-sm-6">
                        <label>Cidade:</label>
                            <input id="cidade" name="cidade" class="form-control " readonly value="Balneário Camboriú">
                        </div>
                        <div class="col-sm-6">
                        <label class="notificacaoMargemLeft">Bairro: </label>
                            <select id="bairro" name="bairro" class="form-control " disabled required>
                                <option <?php if($linhaEndereco->getBairro() == 'Centro') echo 'selected';  ?> value="Centro">Centro</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Nações') echo 'selected';  ?> value="Nações">Nações</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Pioneiros') echo 'selected';  ?> value="Pioneiros">Pioneiros</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Estados') echo 'selected';  ?> value="Estados">Estados</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Ariribá') echo 'selected';  ?> value="Ariribá">Ariribá</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Praia dos Amores') echo 'selected';  ?> value="Praia dos Amores">Praia dos Amores</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Municípios') echo 'selected';  ?> value="Municípios">Municípios</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Vila Real') echo 'selected';  ?> value="Vila Real">Vila Real</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Jardim Iate Clube') echo 'selected';  ?> value="Jardim Iate Clube">Jardim Iate Clube</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Várzea do Ranchinho') echo 'selected';  ?> value="Várzea do Ranchinho">Várzea do Ranchinho</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Barra') echo 'selected';  ?> value="Barra">Barra</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Parque Bandeirantes') echo 'selected';  ?> value="Parque Bandeirantes">Parque Bandeirantes</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Nova Esperança') echo 'selected';  ?> value="Nova Esperança">Nova Esperança</option>
                                <option <?php if($linhaEndereco->getBairro() == 'São Judas Tadeu') echo 'selected';  ?> value="São Judas Tadeu">São Judas Tadeu</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Estaleiro') echo 'selected';  ?> value="Estaleiro">Estaleiro</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Estaleirinho') echo 'selected';  ?> value="Estaleirinho">Estaleirinho</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Laranjeiras') echo 'selected';  ?> value="Laranjeiras">Laranjeiras</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Pinho') echo 'selected';  ?> value="Pinho">Pinho</option>
                                <option <?php if($linhaEndereco->getBairro() == 'Taquaras') echo 'selected';  ?> value="Taquaras">Taquaras</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Rua:</label>
                            <input id="logradouro" name="logradouro" class="form-control" readonly autocomplete="off" value="<?php echo $linhaEndereco->getLogradouro(); ?>">
                        </div>
                        <div class="col-sm-6">
                            <label class="notificacaoMargemLeft">Número:</label>
                            <input id="numero" name="numero" class="form-control  notificacaoMargemLeft" readonly autocomplete="off" value="<?php echo $linhaEndereco->getNumero(); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Referência:</label>           
                            <input id="referencia" name="referencia" class="form-control " readonly autocomplete="off" value="<?php echo $linhaEndereco->getReferencia(); ?>">
                        </div>
                        <div class="col-sm-6">
                            <label>Complemento:</label>
                            <input id="complemento" name="complemento" class="form-control" readonly autocomplete="off" value="<?php echo $linhaEndereco->getComplemento(); ?>">
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-sm-12">
                        <label>Ocorrência:</label>                     
                            <input name="id_ocorrencia" type="hidden" value="<?php echo $id_ocorrencia; ?>">
                            <input id="ocorrencia" class="form-control " readonly value="<?php echo  $linhaNotificacao->getIdOcorrencia()  . ' - ' . $titulo;   ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Data de emissão:</label>
                            <input id="data_emissao" name="data_emissao" type="date" class="form-control " readonly name="data_emissao" autocomplete="off" value="<?php echo $linhaNotificacao->getDataEmissao();?>">
                        </div>
                        <div class="col-sm-6">
                            <label>Data de vencimento:</label>
                            <input id="data_vencimento" name="data_vencimento" type="date" class="form-control " autocomplete="off" readonly value="<?php echo $linhaNotificacao->getDataVencimento(); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                        <label>Agente representante:</label>                 
                            <select id="representante" disabled name="representante" class="form-control " >
                                <?php 
                                if($linhaNotificacao->getRepresentante() == null || $linhaNotificacao->getRepresentante() == ''){
                                    echo '<option selected></option>';
                                }else{
                                    echo '<option selected>' . $agente->getNome() . '</option>';
                                }
                                foreach($listaUsuarios as $usuario){
                                    if($usuario->getNome() != $agente->getNome()){
                                        echo '<option>' . $usuario->getNome()  . '</option>';
                                    }
                                }
                                
                                ?>

                            </select>
        
                        </div>
                        <div class="col-sm-6">
                            <label class="notificacaoMargemLeft">Notificado:</label>
                            <input id="notificado" readonly name="notificado" class="form-control " value="<?php echo $linhaNotificacao->getNotificado(); ?>">
                        </div>
                    </div>
                    <hr>
                    <h4 class="text-center">Descrição da notificação:</h4>
                    <div class="row">
                        <div class="col-sm-12">
                        <textarea id="descricao" name="descricao" readonly class="form-control " style="resize:none;" rows="10"><?php echo $linhaNotificacao->getDescricao() ?></textarea>
                        </div>
                    </div><hr>

                    <div id="areaDocumentoAssinado" class="row hidden">
                        <div class="col-sm-6">
                            <label>Arquivar documento assinado:</label>
                            <input id="imgInp" name="files[]" type="file" multiple="multiple" accept="application/pdf">
                        </div>
                    </div>
                    <?php
                    if($linhaNotificacao->getDocumentoAssinado()){?>
                        <div class="row hidden">
                                <div class="col-sm-6">
                                    <?php var_dump($linhaNotificacao->getDocumentoAssinado()); 
                                    ?>
                                </div>
                        </div>
                
                        <div class="row">
                            <a href="<?php  ?>">Baixar documento assinado.</a>
                        </div>
                    <?php }?>


                    <div class="row" style="display:flex;justify-content: center;">
                        <div class="col-sm-2">
                            <a id="editarNotificacao" class="btn btn-default" onclick="habilitarEdicaoNotificacao()">Editar</a>
                            <button type="submit" id="salvarNotificacao" class="btn btn-default hidden">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
    </div>
</div>