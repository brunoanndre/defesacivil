<?php
    include 'database.php';

    require_once 'dao/UsuarioDaoPgsql.php';
    require_once 'dao/ChamadoDaoPgsql.php';
    require_once 'dao/EnderecoDaoPgsql.php';

    $enderecodao = New EnderecoDaoPgsql($pdo);
    $usuariodao = new UsuarioDaoPgsql($pdo);
    $chamadodao = new ChamadoDaoPgsql($pdo);

    $id_chamado = $_GET['id'];

    $linhaChamado = $chamadodao->buscarPeloId($id_chamado);

    $id_agente = $linhaChamado->getAgenteId();
    $linhaAgente = $usuariodao->findById($id_agente);

    $id_distribuicao = $linhaChamado->getDistribuicao();
    $linhaDistribuicao = $usuariodao->findById($id_distribuicao);
    if($linhaChamado->getEnderecoPrincipal() == 'Coordenada'){
        $id_endereco = $linhaChamado->getIdCoordenada();
        $linhaEndereco = $enderecodao->buscarIdCoordenada($id_endereco);
    }else{
        $id_endereco = $linhaChamado->getLogradouroId();
        $linhaEndereco = $enderecodao->buscarPeloId($id_endereco);
    }

    $string = $linhaChamado->getFotos();

    $barras = array("{","}");
    $string = str_replace($barras,'',$string);

    $fotos = explode(',', $string);

    if($linhaChamado->getPossuiFotos() == false){
        $possui_fotos = 0;
    }else{
        $possui_fotos = 1;
    }


    $consulta_usuarios = $usuariodao->buscarUsuariosAtivos();

?>

<div class="container positioning">
<div class="jumbotron campo_cadastro">
    <form method="post" action="processa_editar_chamado.php" enctype="multipart/form-data" onsubmit="return validarFormCadastroChamado()">
        <?php if(isset($_GET['sucesso'])){ ?>
            <div class="alert alert-success" role="alert">
                Chamado editado com sucesso.
            </div>
            <?php } ?>
            <?php if(isset($_GET['erroDB'])){ ?>
            <div class="alert alert-danger" role="alert">
                Falha ao editar chamado.
            </div>
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
            <h3 class="text-center">Registro de chamado</h3>
        <hr>
            <div>
                <span style="font-weight: bold;">Agente Principal: </span><?php echo $linhaAgente->getNome(); ?>
                <br>
            </div>
            <div>
                Origem: <span style="color:red;">*</span>
                <select name="origem_chamado" class="form-control" ng-model="sel_origem" ng-init="sel_origem='<?php if($linhaChamado->getOrigem() == 'Telefone Base'){echo 'Telefone Base';}else if($linhaChamado->getOrigem() == 'Ouvidoria'){echo 'Ouvidoria';} 
                else if($linhaChamado->getOrigem() == '199'){ echo '199';}else if($linhaChamado->getOrigem() == 'Secretaria de Obras'){echo 'Secretaria de Obras';}
                else if($linhaChamado->getOrigem() == 'Secretaria do Meio Ambiente'){echo 'Secretaria do Meio Ambiente';}
                else if($linhaChamado->getOrigem() == 'Secretaria da Saúde'){echo 'Secretaria da Saude';}else{ echo 'Outro';}  ?>'" required>
                <option value="Telefone Base">Telefone Base</option>
                <option value="Ouvidoria">Ouvidoria</option>
                <option value="199">199</option>
                <option value="Secretaria de Obras">Secretaria de Obras</option>
                <option value="Secretaria do Meio Ambiente">Secretaria do Meio Ambiente</opntion>
                <option value="Secretaria da Saúde">Secretaria da Saúde</option>
                <option value="Outro">Outros (Especificar)</option>
                </select>

                <div ng-show="sel_origem == 'Outro'">
                Descrição Origem:
                <input type="text" name="origem_chamado2" class="form-control" value="<?php echo $linhaChamado->getOrigem(); ?>">
                </div>
            </div>
            <!--<div>
                Agente principal: <span style="color:red;">*</span>
                <input id="agente" name="agente" type="text" class="form-control" onkeyup="showResult(this.value,this.id)" required>
                <div class="autocomplete" id="livesearchagente"></div>
            </div>-->
            <?php //if(isset($_GET['agente'])){ ?>
                <!--<span class="alertErro">Agente não encontrado.</span>-->
            <?php //} ?>
            <div class="row">
                <div class="col-sm-12">
                    Solicitante:<br>
                    <span id="alertpessoasucesso" class="alert-sucess" style="color: greenyellow;"></span>
                    <input id="pessoa_nome" name="nome_chamado" autocomplete="off" type="text" class="form-control inline" style="width:93%;" onkeyup="showResult(this.value,this.id)" value="<?php echo $linhaChamado->getNomePessoa() ?>">
                    <button type="button" class="btn-default btn-small inline" data-toggle="modal" data-target="#pessoasModal"><span class="glyphicon glyphicon-plus"></span></button>
                    <div class="autocomplete" id="livesearchpessoa_nome"></div>
                    <div id="resultpessoa_nome"></div>

                    <?php if(isset($_GET['nome'])){ ?>
                        <span class="alertErro">Pessoa não encontrada, por favor faça um novo cadastro.</span>
                    <?php } ?>
                </div>
                <!--<div class="col-sm-2">
                    <br>
                    <button type="button" class="btn-default btn-small inline open-AddBookDialog" data-toggle="modal" data-id="pessoa_nome"><span class="glyphicon glyphicon-plus"></span></button>
                </div>-->
            </div>
            <div>
                Distribuir para:
                <select id="distribuicao" name="distribuicao" class="form-control" style="width: 50%" required>
                    <?php
                        session_start();
                        if($consulta_usuarios == false){
                            echo '<tr><td colspan="5" class="text-center">Nenhum usuário encontrado</td></tr>';
                        }
                        foreach($consulta_usuarios as $item){
                            if($item->getNome() == $linhaDistribuicao->getNome()){
                                echo '<option selected value='.$item->getId().'>'.$item->getNome().'</option>'; 
                            }else{
                                echo '<option value='.$item->getId().'>'.$item->getNome().'</option>'; 
                            }

                        }
                    ?>
                </select>
            <hr>
            <div>
                Localizar por: <span style="color:red;">*</span>
                <br>
                <label for="endereco_principal"></label>
                <select name="endereco_principal" class="form-control endereco-principal" ng-model="sel_endereco" ng-init="sel_endereco='<?php echo $linhaChamado->getEnderecoPrincipal(); ?>'" required>
                    <option value="Coordenada">Coordenada</option>
                    <option value="Logradouro">Logradouro</option>
                </select>
            </div>

            <div ng-show="sel_endereco == 'Coordenada'">
                <div class="row">
                    <div class="col-sm-4">
                        <span>Latitude: <span style="color:red;">*</span></span>
                        <input id="latitude" name="latitude" type="text" class="form-control" onchange="verificaLatLgn()" value="<?php if($linhaChamado->getEnderecoPrincipal() == 'Coordenada'){ echo $linhaEndereco->getLatitude(); }?>">
                    </div>
                    <div class="col-sm-4">
                        <span>Longitude: <span style="color:red;">*</span></span> 
                        <input id="longitude" name="longitude" type="text" class="form-control" onchange="verificaLatLgn()" value="<?php if($linhaChamado->getEnderecoPrincipal() == 'Coordenada'){ echo $linhaEndereco->getLongitude();} ?>">
                    </div>
                    <div class="col-sm-4">
                        <br>
                        <button type="button" class="btn-default btn-small inline open-AddBookDialog" data-toggle="modal" data-id="map"><span class="glyphicon glyphicon-map-marker"></span></button>
                    </div>
                </div>
                <span id="erroLatLgn" class="alertErro hide">Latitude e/ou Longitude inválida(s).</span>
            </div>
            <div ng-show="sel_endereco == 'Logradouro'">
                <div class="row">
                    <div class="col-sm-4">
                        <span>CEP:</span>
                        <input id="cep" name="cep" type="text" class="form-control" ng-model="cep" maxlength="8" onchange="verificaCep(this.value)" value="<?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ echo $linhaEndereco->getCep();}  ?>">
                        <span id="erroCep" class="alertErro hide">CEP inválido.</span>
                    </div>
                    <div class="col-sm-8">
                        <span>Cidade: </span> <span style="color:red;">*</span>
                        <select id="cidade" name="cidade" class="form-control" required>
                            <option value="Balneário Camboriú">Balneário Camboriú</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <span>Bairro: <span style="color:red;">*</span></span>
                        <select id="bairro" name="bairro" class="form-control" required>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Centro'){ echo 'selected'; } } ?> value="Centro">Centro</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Nações'){ echo 'selected'; }} ?>  value="Nações">Nações</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Pioneiros'){ echo 'selected'; }} ?>  value="Pioneiros">Pioneiros</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Estados'){ echo 'selected'; }} ?>  value="Estados">Estados</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Ariribá'){ echo 'selected'; }} ?>  value="Ariribá">Ariribá</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Praia dos Amores'){ echo 'selected'; }} ?>  value="Praia dos Amores">Praia dos Amores</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Municípios'){ echo 'selected'; }} ?>  value="Municípios">Municípios</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Vila Real'){ echo 'selected'; } }?>  value="Vila Real">Vila Real</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Jardim Iate Clube'){ echo 'selected'; }} ?>  value="Jardim Iate Clube">Jardim Iate Clube</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Várzea do Ranchinho'){ echo 'selected'; }} ?>  value="Várzea do Ranchinho">Várzea do Ranchinho</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Barra'){ echo 'selected'; }} ?>  value="Barra">Barra</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Parque Bandeirantes'){ echo 'selected'; } }?>  value="Parque Bandeirantes">Parque Bandeirantes</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Nova Esperança'){ echo 'selected'; } }?>  value="Nova Esperança">Nova Esperança</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'São Judas Tadeu'){ echo 'selected'; }} ?>  value="São Judas Tadeu">São Judas Tadeu</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Estaleiro'){ echo 'selected'; }} ?>  value="Estaleiro">Estaleiro</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Estaleirinho'){ echo 'selected'; }} ?>  value="Estaleirinho">Estaleirinho</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Laranjeiras'){ echo 'selected'; }} ?>  value="Laranjeiras">Laranjeiras</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Pinho'){ echo 'selected'; }} ?>  value="Pinho">Pinho</option>
                            <option <?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ 
                                if($linhaEndereco->getBairro() == 'Taquaras'){ echo 'selected'; }} ?>  value="Taquaras">Taquaras</option>
                        </select>
                    </div>
                    <div class="col-sm-8">
                        <span>Logradouro: <span style="color:red;">*</span></span>
                        <input id="logradouro" name="logradouro" type="text" class="form-control" value="<?php if($linhaChamado->getEnderecoPrincipal() == 'Logradouro'){ echo $linhaEndereco->getLogradouro() ;} ?>">
                        <?php if(isset($_GET['logradouro'])){ ?>
                            <span class="alertErro">Erro ao cadastrar logradouro.</span>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <span>Número: </span> <span style="color:red;">*</span>
                        <input id="complemento" name="numero" type="text" class="form-control" value="<?php echo $linhaEndereco->getNumero() ;?>">
                    </div>
                    <div class="col-sm-8">
                        <span class="testu">Referência: </span>
                        <input name="referencia" type="text" class="form-control" value="<?php $linhaEndereco->getReferencia() ?>">
                    </div>
                </div>
            </div>
        <hr>
            <div>
                Descrição: <span style="color:red;">*</span>
                <textarea id="descricao" name="descricao" class="form-control" cols="30" rows="3" maxlength="750" required><?php echo $linhaChamado->getDescricao(); ?></textarea>
            </div>
        <hr>
            <div>
                Prioridade: <span style="color:red;">*</span>
                <label for="prioridade"></label>
                <select name="prioridade" class="form-control" style="width:30%;" required aria-valuenow="">
                    <option <?php if($linhaChamado->getPrioridade() == 'Baixa'){ echo 'selected';} ?> value="Baixa">Baixa</option>
                    <option <?php if($linhaChamado->getPrioridade() == 'Média'){ echo 'selected';} ?> value="Média">Média</option>
                    <option <?php if($linhaChamado->getPrioridade() == 'Alta'){ echo 'selected';} ?> value="Alta">Alta</option>
                </select>
            </div>
        </div>
        <hr>
        Fotos:
        <div style="margin-top: 20px;">
        <?php if(($fotos[0]) != "" && $fotos[0] != null){?>
        <div class="box printHide">
            <div id="myCarousel" class="carousel slide limite" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators printHide">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    
                    <?php $i = 0; while($i < sizeof($fotos)){ ?>
                        <li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>"></li>
                    <?php $i+=1; } ?>
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <div class="item active">
                        <?php $i=0; echo '<button type="button" class="btn btn-danger" id="'.$i.'"  value="idFotos'.$i.'" style=" margin-left: 50%; z-index:1;" onclick="modalFoto(this.value,this.id)">&times;</button>' ?>
                        <img src="data:image/png;base64,<?php echo $fotos[$i]; ?>" alt="img1" style="width:100%;">
                    </div>
                    <?php $i = 1; while($i < sizeof($fotos)){ ?>
                        <div class="item">    
                        <div><?php echo '<button type="button" class="btn btn-danger" id="'.$i.'"  value="idFotos'.$i.'" style="position:absolute; margin-left: 50%; z-index:1;" onclick="modalFoto(this.value,this.id)">&times;</button>' ?></div>                    
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
        <?php }?>
        <div>
            Adicionar fotos:
            <input name="possui_fotos" type="hidden" value="<?php echo $possui_fotos; ?>">
            <input id="imgInp" name="files[]" type="file" multiple="multiple" accept="image/png,image/jpeg">
        </div>
        <div id="idGallery" class="gallery"></div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <label>Data do atendimento:</label>
                <input type="date" name="dataAtendimento" class="form-control" value="<?php echo $linhaChamado->getDataAtendimento(); ?>">
            </div> 
            <div class="col-sm-6">
                <label>Chamado encerrado:</label>
                <div>
                    <label class="radio-inline">
                        <input type="radio" value="true" name="encerrado" <?php if($linhaChamado->getUsado() == true){echo 'checked';} ?>>Sim
                    </label>
                    <label class="radio-inline">
                        <input type="radio" value="false" name="encerrado" <?php if($linhaChamado->getUsado() == false){echo 'checked';} ?>>Não
                    </label>
                </div>
            </div>
        </div>
        <div class="row" style="display:flex; justify-content:center;">
            <div>
                <a href="index.php?pagina=exibirChamado&id=<?php echo $id_chamado; ?>"><input style="margin-right: 20px; width:60px" class="btn btn-default" value="Voltar"></a>
            </div>
            <div class="div-btn-cadastrar">
                <input class="hidden" name="id_chamado" value="<?php echo $id_chamado; ?>">
                <input class="hidden" name="id_logradouro" value="<?php echo $linhaChamado->getLogradouroId(); ?>">
                <input class="hidden" name="id_coordenada" value="<?php echo $linhaChamado->getIdCoordenada(); ?>">
                <input type="submit" class="btn btn-cadastrar btn-default" value="Salvar">
            </div>
        </div>  
    
    </form>  
    <div class="modal fade " style="position: absolute; top:130%; left:20%" id="excluirModal">
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
                        <form method="POST" action="excluirFoto.php">
                            <input class="hidden" name="id_chamado" value="<?php echo $id_chamado; ?>">
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
                <form name="pessoa" method="post">
                    <div class="modal-body">
                        <nav>
                            <input id="id_pessoa" type="hidden" value="">
                            <div class="row">
                                <div class="col-sm-12">
                                    Nome: <span style="color:red;">*</span>
                                    <input id="nome_pessoa" name="nome_pessoa" type="text" class="form-control">
                                </div>
                            </div>   
                            <span id="erroNome" class="alertErro hide">Nome inválido.</span>
                            <div class="row">
                                <div class="col-sm-6">
                                    CPF:
                                    <input id="cpf_pessoa" name="cpf_pessoa" type="text" class="form-control" onchange="verificaCpf(this.value)">
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
                                    Fixo: 
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
                    <button type="button" id="submitFormData" onclick="myMap()" data-dismiss="modal">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>