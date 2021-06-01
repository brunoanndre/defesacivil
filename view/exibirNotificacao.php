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

<div class="container positioning">
    <div class="jumbotron campo_cadastro">
            <?php if(isset($_GET['erroDB'])){ ?>
                <div class="alert alert-danger printHide" role="alert">Falha ao editar a notificação, contate o administrador.</div>
            <?php } ?>
            <?php if(isset($_GET['sucesso'])){ ?>
                <div class="alert alert-success printHide" role="alert">Notificação cadastrada com sucesso.</div>
            <?php } ?>
            <?php if(isset($_GET['sucessoEdit'])){ ?>
                <div class="alert alert-success printHide" role="alert">Notificação editada com sucesso.</div>
            <?php } ?>
            <div class="box">
                <div class="row cabecalhoNot">
                    <div class="col-sm-6">
                        <nav class="texto-cabecalho">Estado de Santa Catarina</nav>
                        <nav class="texto-cabecalho">Prefeitura de Balneário Camboriú</nav>
                        <nav class="texto-cabecalho">Secretaria de segurança</nav>
                        <nav class="texto-cabecalho">Defesa Civil</nav>
                    </div>
                    <div class="col-sm-6 cbbls">
                        <img src="images/balneario-camboriu.png" alt="prefeitura-balneario-camboriu" class="img-cabecalho-not">
                    </div>  
                </div>  
                <h3 class="text-center">Notificação Nº <?php echo $id_notificacao . '/' . date('Y'); ?></h3>
                <button class="printHide" style="background-color: white; border:none;" onclick="print()"><img src="images/print.png" style="width: 50px; height:auto"></button>
                <form action="processa_editarNotificacao.php" method="post">
                <input name="id_notificacao" type="hidden" value="<?php echo $id_notificacao; ?>">
                <input name="id_endereco" type="hidden" value="<?php echo $linhaEndereco->getId(); ?>">
                    <div class="row">
                        <div class="col-sm-6">
                        <div class="row">
                        <label>Cidade:</label> <span class="printShow"><?php echo 'Balneário Camboriú'; ?></span>
                        </div>
                            <input id="cidade" name="cidade" class="form-control printHide" readonly value="Balneário Camboriú">
                        </div>
                        <div class="col-sm-6">
                        <div class="row">
                        <label class="notificacaoMargemLeft">Bairro: </label><span class="printShow "><?php echo $linhaEndereco->getBairro(); ?></span>
                        </div> 
                            <select id="bairro" name="bairro" class="form-control printHide" disabled required>
                                <option value="Centro">Centro</option>
                                <option value="Nações">Nações</option>
                                <option value="Pioneiros">Pioneiros</option>
                                <option value="Estados">Estados</option>
                                <option value="Ariribá">Ariribá</option>
                                <option value="Praia dos Amores">Praia dos Amores</option>
                                <option value="Municípios">Municípios</option>
                                <option value="Vila Real">Vila Real</option>
                                <option value="Jardim Iate Clube">Jardim Iate Clube</option>
                                <option value="Várzea do Ranchinho">Várzea do Ranchinho</option>
                                <option value="Barra">Barra</option>
                                <option value="Parque Bandeirantes">Parque Bandeirantes</option>
                                <option value="Nova Esperança">Nova Esperança</option>
                                <option value="São Judas Tadeu">São Judas Tadeu</option>
                                <option value="Estaleiro">Estaleiro</option>
                                <option value="Estaleirinho">Estaleirinho</option>
                                <option value="Laranjeiras">Laranjeiras</option>
                                <option value="Pinho">Pinho</option>
                                <option value="Taquaras">Taquaras</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                        <div class="row">
                            <label>Rua:</label><span class="printShow"><?php echo $linhaEndereco->getLogradouro(); ?></span>
                        </div>
                        
                            <input id="logradouro" name="logradouro" class="form-control printHide" readonly autocomplete="off" value="<?php echo $linhaEndereco->getLogradouro(); ?>">
                        </div>
                        <div class="col-sm-6">
                        <div class="row">
                            <label class="notificacaoMargemLeft">Número:</label><span class="printShow"><?php echo $linhaEndereco->getNumero(); ?></span>
                        </div>
                            <input id="numero" name="numero" class="form-control printHide notificacaoMargemLeft" readonly autocomplete="off" value="<?php echo $linhaEndereco->getNumero(); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                        <div class="row">
                            <label>Referência:</label><span class="printShow"><?php echo $linhaEndereco->getReferencia() ?></span>
                        </div>
                         
                            <input id="referencia" name="referencia" class="form-control printHide" readonly autocomplete="off" value="<?php echo $linhaEndereco->getReferencia(); ?>">
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-sm-12">
                        <div class="row">
                        <label>Ocorrência:</label><span class="printShow"><?php echo $linhaNotificacao->getIdOcorrencia() . '-' . $titulo; ?></span>
                        </div>
                            
                            <input name="id_ocorrencia" type="hidden" value="<?php echo $id_ocorrencia; ?>">
                            <input id="ocorrencia" class="form-control printHide" readonly value="<?php echo  $linhaNotificacao->getIdOcorrencia()  . ' - ' . $titulo;   ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                            <label>Data de emissão:</label><span class="printShow"><?php echo $linhaNotificacao->getDataEmissao(); ?></span>
                            </div>
                            <input id="data_emissao" name="data_emissao" type="date" class="form-control printHide" readonly name="data_emissao" autocomplete="off" value="<?php echo $linhaNotificacao->getDataEmissao();?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                        <div class="row">
                        <label>Agente representante:</label><span class="printShow"><?php echo $agente->getNome();  ?></span>
                        </div>
                        
                            <select id="representante" disabled name="representante" class="form-control printHide" >
                                <?php 
                                if($linhaNotificacao->getRepresentante() == null || $linhaNotificacao->getRepresentante() == ''){
                                    echo '<option selected></option>';
                                }
                                foreach($listaUsuarios as $usuario){
                                    if($linhaNotificacao->getRepresentante() == $usuario->getId()){
                                        echo '<option selected>' . $usuario->getNome()  . '</option>';
                                    }
                                        echo '<option>' . $usuario->getNome()  . '</option>';
                                }
                                
                                ?>

                            </select>
        
                        </div>
                        <div class="col-sm-6 printHide">
                            <div class="row">
                            <label class="notificacaoMargemLeft">Notificado:</label><span class="printShow"><?php echo $linhaNotificacao->getNotificado(); ?></span>
                            </div>
                            <input id="notificado" readonly name="notificado" class="form-control printHide" value="<?php echo $linhaNotificacao->getNotificado(); ?>">
                        </div>
                    </div>
                    <div class="row printShow">
                        <label>Notificado:</label><span><?php echo $linhaNotificacao->getNotificado();?></span>
                    </div>
                    <hr>

                    <h4 class="text-center">Descrição dos fatos:</h4><span class="printShow"><?php echo $linhaNotificacao->getDescricao(); ?></span>
                    <div class="row">
                        <div class="col-sm-12">
                        <textarea id="descricao" name="descricao" readonly class="form-control printHide" style="resize:none;" rows="10"><?php echo $linhaNotificacao->getDescricao() ?></textarea>
                        </div>
                    </div><hr>
                    <div class="row printShow">
                        <div class="col-sm-1 bordas">
                            BALNEARIO CAMBORIÚ
                        </div>
                        <div class="col-sm-2 bordas">
                            Data e hora de entrega: _________________
                        </div>
                    </div>
                    <div class="row printShow" style="display:flex;justify-content: center;margin-top:50px; ">
                            <div class="col-sm-6 printShow" style="border-top: 1px solid black;">
                                Assinatura representante da Defesa Civil
                            </div>
                    </div>
                    <div class="row printShow" style="display:flex;justify-content: center;margin-top:50px;">
                            <div class="col-sm-4 printShow" style="border-top: 1px solid black;">
                                Assinatura notificado
                            </div>
                    </div>
                    <div class="row" style="display:flex;justify-content: center;">
                        <div class="col-sm-2">
                            <a id="editarNotificacao" class="btn btn-default printHide" onclick="habilitarEdicaoNotificacao()">Editar</a>
                            <button type="submit" id="salvarNotificacao" class="btn btn-default hidden">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
    </div>
</div>