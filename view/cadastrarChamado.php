<?php
    include 'database.php';

    require_once 'dao/UsuarioDaoPgsql.php';
    require_once 'dao/ChamadoDaoPgsql.php';

    $usuariodao = new UsuarioDaoPgsql($pdo);
    $chamadodao = new ChamadoDaoPgsql($pdo);

    session_start();
    $id = $_SESSION['id_usuario'];
            
    $usuario_principal = $usuariodao->findById($id);

    $consulta_usuarios = $usuariodao->buscarUsuariosAtivos();
?>
<div class="container positioning">
<div class="jumbotron campo_cadastro">
    <form method="post" action="processa_cadastrar_chamado.php" enctype="multipart/form-data" onsubmit="return validarFormCadastroChamado()">
        <?php if(isset($_GET['sucesso'])){ ?>
            <div class="alert alert-success" role="alert">
                Chamado cadastrado com sucesso.
            </div>
            <?php } ?>
            <?php if(isset($_GET['erroDB'])){ ?>
            <div class="alert alert-danger" role="alert">
                Falha ao cadastrar chamado.
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
                <label>Agente Principal:</label><?php echo $usuario_principal->getNome(); ?>
                <br>
            </div>
            <div>
                <label>Origem:</label> <span style="color:red;">*</span>
                <select name="origem_chamado" class="form-control" ng-model="sel_origem" ng-init="sel_origem='Telefone Base'" required>
                <option value="Telefone Base">Telefone Base</option>
                <option value="Ouvidoria">Ouvidoria</option>
                <option value="199">199</option>
                <option value="Secretaria de Obras">Secretaria de Obras</option>
                <option value="Secretaria do Meio Ambiente">Secretaria do Meio Ambiente</opntion>
                <option value="Secretaria da Saúde">Secretaria da Saúde</option>
                <option value="Outro">Outros (Especificar)</option>
                </select>

                <div ng-show="sel_origem == 'Outro'">
                <label>Descrição Origem:</label>
                <input type="text" name="origem_chamado2" class="form-control">
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
                    <label>Solicitante:</label><br>
                    <span id="alertpessoasucesso" class="alert-sucess" style="color: greenyellow;"></span>
                    <input id="pessoa_nome" name="nome_chamado" autocomplete="off" type="text" class="form-control inline" style="width:93%;" onkeyup="showResult(this.value,this.id)">
                    <button type="button" class="btn-default btn-small inline" data-toggle="modal" data-target="#pessoasModal"><span class="glyphicon glyphicon-plus"></span></button>
                    <div class="autocomplete" id="livesearchpessoa_nome"></div>
                    <div id="resultpessoa_nome"></div>

                    <!--<?php //if(isset($_GET['nome'])){ ?>
                        <span class="alertErro">Pessoa não encontrada, por favor faça um novo cadastro.</span>
                    <?php //} ?> -->
                </div>
                <!--<div class="col-sm-2">
                    <br>
                    <button type="button" class="btn-default btn-small inline open-AddBookDialog" data-toggle="modal" data-id="pessoa_nome"><span class="glyphicon glyphicon-plus"></span></button>
                </div>-->
            </div>
            <div>
                <label>Distribuir para:</label>
                <select id="distribuicao" name="distribuicao" class="form-control" style="width: 50%" required>
                    <?php
                        session_start();
                        if($consulta_usuarios == false){
                            echo '<tr><td colspan="5" class="text-center">Nenhum usuário encontrado</td></tr>';
                        }
                        foreach($consulta_usuarios as $item){
                            echo '<option value='.$item->getId().'>'.$item->getNome().'</option>'; 
                        }
                    ?>
                </select>
            <hr>
            <div>
                <label>Localizar por:</label> <span style="color:red;">*</span>
                <br>
                <label for="endereco_principal"></label>
                <select name="endereco_principal" class="form-control endereco-principal" ng-model="sel_endereco" ng-init="sel_endereco='Logradouro'" required>
                    <option value="Coordenada">Coordenada</option>
                    <option value="Logradouro">Logradouro</option>
                </select>
            </div>
            <div ng-show="sel_endereco == 'Coordenada'">
                <div class="row">
                    <div class="col-sm-4">
                        <label>Latitude:</label> <span style="color:red;">*</span></span>
                        <input id="latitude" name="latitude" type="text" class="form-control" onchange="verificaLatLgn()">
                    </div>
                    <div class="col-sm-4">
                        <label>Longitude:</label> <span style="color:red;">*</span>
                        <input id="longitude" name="longitude" type="text" class="form-control" onchange="verificaLatLgn()">
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
                        <label>CEP:</label>
                        <input id="cep" name="cep" type="text" class="form-control" ng-model="cep" maxlength="8" onchange="verificaCep(this.value)">
                        <span id="erroCep" class="alertErro hide">CEP inválido.</span>
                    </div>
                    <div class="col-sm-8">
                        <label>Cidade:</label> <span style="color:red;">*</span>
                        <!--<input id="cidade" name="cidade" type="text" class="form-control">-->
                        <select id="cidade" name="cidade" class="form-control" required>
                            <option value="Balneário Camboriú">Balneário Camboriú</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                       <label>Bairro:</label> <span style="color:red;">*</span>
                        <!--<input id="bairro" name="bairro" type="text" class="form-control">-->
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
                    <div class="col-sm-8">
                        <label>Logradouro:</label> <span style="color:red;">*</span>
                        <input id="logradouro" name="logradouro" type="text" class="form-control">
                        <?php if(isset($_GET['logradouro'])){ ?>
                            <span class="alertErro">Erro ao cadastrar logradouro.</span>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Número:</label> <span style="color:red;">*</span>
                        <input id="numero" name="numero" type="text" class="form-control">
                    </div>
                    <div class="col-sm-8">
                        <label>Referência:</label>
                        <input name="referencia" type="text" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                    <label>Complemento:</label>
                    <input id="complemento" name="complemento" type="text" class="form-control">
                    </div>
                </div>
            </div>
        <hr>
            <div>
                <label>Descrição:</label> <span style="color:red;">*</span>
                <textarea id="descricao" name="descricao" class="form-control" cols="30" rows="3" maxlength="750" ng-model="descricaoVal" required></textarea>
                <span class="char-count">{{descricaoVal.length || 0}}/750</span>
            </div>
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
        <hr>
        <div>
            <label>Fotos:</label>
            <input id="imgInp" name="files[]" type="file" multiple="multiple" accept="image/png,image/jpeg">
        </div>
        <div id="idGallery" class="gallery"></div>
        <hr>
        <div class="div-btn-cadastrar">
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
                <form name="pessoa" method="post">
                    <div class="modal-body">
                        <nav>
                            <input id="id_pessoa" type="hidden" value="">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Nome:</label> <span style="color:red;">*</span>
                                    <input id="nome_pessoa" name="nome_pessoa" type="text" class="form-control">
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
                                    <label>Celular:</label> 
                                    <input id="celular_pessoa" name="celular_pessoa" type="text" class="form-control" pattern="\([0-9]{2}\)[\s][0-9]{4,5}-[0-9]{4}" title="(XX) XXXXX-XXXX" onchange="verificaCelular(this.value)">
                                    <span id="erroCelular" class="alertErro hide">Celular inválido.</span>
                                </div>
                                <div class="col-sm-6">
                                    <label>Fixo:</label>
                                    <input id="telefone_pessoa" name="telefone_pessoa" type="text" class="form-control" pattern="\([0-9]{2}\)\s[0-9]{4}\-[0-9]{4}" title="(XX) XXXX-XXXX" onchange="verificaTelefone(this.value)">
                                    <span id="erroTelefone" class="alertErro hide">Telefone inválido.</span>
                                </div>
                            </div>
                            <span id="erroTelefone" class="alertErro hide">Telefone inválido.</span>
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
                    <button type="button" id="submitFormData" onclick="myMap()" data-dismiss="modal">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
