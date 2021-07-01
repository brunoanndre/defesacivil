<?php
    require_once 'dao/OcorrenciaDaoPgsql.php';
    require_once 'dao/EnderecoDaoPgsql.php';
    require_once 'dao/UsuarioDaoPgsql.php';

    $usuariodao = New UsuarioDaoPgsql($pdo);
    $ocorrenciadao = New OcorrenciaDaoPgsql($pdo);
    $enderecodao = New EnderecoDaoPgsql($pdo);

    $id_ocorrencia = filter_input(INPUT_GET, 'id');

    $linhaOcorrencia = $ocorrenciadao->buscarPeloId($id_ocorrencia);

    $linhaEndereco = $enderecodao->buscarPeloId($linhaOcorrencia->getLogradouroid());
    $data = date('d/m/Y');
    $consulta_usuarios = $usuariodao->buscarUsuariosAtivos();
?>
<div class="container positioning">
    <div class="jumbotron campo_cadastro">
            <?php if(isset($_GET['erroDB'])){ ?>
                <div class="alert alert-danger" role="alert">Falha ao cadastrar ocorrencia.</div>
            <?php } ?>
            <?php if(isset($_GET['campos'])){ ?>
                <div class="alert alert-danger" role="alert">Preencha todos os campos.</div>
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
                <h3 class="text-center">Cadastro de Notificação</h3>
                <h4>Endereço</h4>
                <form action="processa_cadastrar_notificacao.php" method="post">
                    <input type="hidden" name="id_endereco" value="<?php echo $linhaOcorrencia->getLogradouroid(); ?>">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Cidade</label>
                            <input name="cidade" class="form-control" value="Balneário Camboriú">
                        </div>
                        <div class="col-sm-6">
                            <label>Bairro</label>
                            <select id="bairro" name="bairro" class="form-control" required>
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
                            <label>Rua:</label>
                            <input name="logradouro" class="form-control" autocomplete="off" value="<?php echo $linhaEndereco->getLogradouro(); ?>">
                        </div>
                        <div class="col-sm-6">
                            <label>Número:</label>
                            <input name="numero" class="form-control" autocomplete="off" value="<?php echo $linhaEndereco->getNumero(); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Referência:</label>
                            <input name="referencia" class="form-control" autocomplete="off" value="<?php echo $linhaEndereco->getReferencia(); ?>">
                        </div>
                        <div class="col-sm-6">
                            <label>Complemento:</label>
                            <input id="complemento" name="complemento" class="form-control" autocomplete="off" value="<?php echo $linhaEndereco->getComplemento();?>" > 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Ocorrência:</label>
                            <input name="id_ocorrencia" type="hidden" value="<?php echo $id_ocorrencia; ?>">
                            <input class="form-control" value="<?php echo  $id_ocorrencia  . ' - ' . $linhaOcorrencia->getTitulo();   ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Data de emissão:</label>
                            <input type="date" class="form-control" name="data_emissao" autocomplete="off">
                            <?php if(isset($_GET['data'])){ ?>
                                <div class="alertErro" >Informe a data de emissão.</div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-6">
                                <label>Data de vencimento</label>
                                <input type="date" class="form-control" name="data_vencimento" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                                <label>Agente Notificante:</label>
                                <select id="notificante" name="notificante" class="form-control" required>
                    <?php
                        session_start();
                        echo '<option selected></option>';
                        if($consulta_usuarios == false){
                            echo '<tr><td colspan="5" class="text-center">Nenhum usuário encontrado</td></tr>';
                        }
                        foreach($consulta_usuarios as $item){
                            echo '<option value='.$item->getId().'>'.$item->getNome().'</option>'; 
                        }
                    ?>
                </select>
                        </div>
                        <div class="col-sm-6">
                                <label>Pessoa notificada:</label>
                                <input class="form-control" name="notificado" id="notificado" autocomplete="off">
                        </div>
                    </div>
                    
                    
                    <hr>
                    <h4 class="text-center">Descrição dos fatos</h4>
                    <div class="row">
                        <div class="col-sm-12">
                        <textarea name="descricao" class="form-control" style="resize:none;" rows="10"></textarea>
                        </div>
                    </div><hr>
                    <div class="row printShow">
                        <div class="col-sm-4 bordas">
                            BALNEARIO CAMBORIÚ
                        </div>
                        <div class="col-sm-4 bordas">
                            Data emissão: <?php echo date('d/m/Y'); ?>
                        </div>
                        <div class="col-sm-4 bordas">
                            Data e hora de entrega: _________________
                        </div>
                    </div>
                    <div class="row" style="display:flex;justify-content: center;">
                        <div class="col-sm-2">
                            <button type="submit" class="form-control">Cadastrar</button>
                        </div>
                    </div>

                </form>
            </div>
    </div>
</div>