<?php 
    require_once 'dao/OcorrenciaDaoPgsql.php';
    require_once 'dao/EnderecoDaoPgsql.php';

    $enderecodao = New EnderecoDaoPgsql($pdo);
    $ocorrenciadao = New OcorrenciaDaoPgsql($pdo);
    $id_ocorrencia = filter_input(INPUT_GET, 'id');
    
    $linhaOcorrencia = $ocorrenciadao->buscarPeloId($id_ocorrencia);

    if($linhaOcorrencia->getEnderecoPrincipal() == 'Logradouro'){
        $linhaLogradouro = $enderecodao->buscarPeloId($linhaOcorrencia->getLogradouroid());
    }else{
        $linhaCoordenada = $enderecodao->buscarIdCoordenada($linhaOcorrencia->getIdCoordenada());
    }
?>

<div class="container positioning">
<div class="jumbotron campo_cadastro">
    <?php if(isset($_GET['erroDB'])){ ?>
        <div class="alert alert-danger" role="alert">
            Falha ao cadastrar interdição.
        </div>
    <?php } ?>
    <div class="box">
            <div class="row cabecalho">
                <div class="col-sm-6">
                    <nav class="texto-cabecalho">Estado de Santa Catarina</nav>
                    <nav class="texto-cabecalho">Prefeitura de Balneário Camboriú</nav>
                    <nav class="texto-cabecalho">Secretaria de segunrança</nav>
                    <nav class="texto-cabecalho">Defesa Civil</nav>
                </div>
                <div class="col-sm-6">
                    <img src="images/balneario-camboriu.png" alt="prefeitura-balneario-camboriu" class="img-cabecalho">
                </div>
            </div>
            <h3 class="text-center">Registro de interdição</h3>
        </div>
        <div class="box">
        <nav>
        <h4>Dados da ocorrência:</h4>
        </nav>
        <div class="row">
            <div class="col-sm-6">
                <label>Nº da ocorrência:</label>
                <a href="index.php?pagina=exibirOcorrencia&id=<?php echo $id_ocorrencia; ?> "><?php echo  $id_ocorrencia ?></a>                
            </div>
            <div class="col-sm-6">
                <label>Data da ocorrência: </label><span><?php echo ' ' . date('d/m/Y',strtotime($linhaOcorrencia->getData())) ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <label>Titulo da ocorrência:</label>
                <input class="form-control" readonly value="<?php echo $linhaOcorrencia->getTitulo(); ?>">
            </div>
        </div>
        <div class="hidden">
            <span class="titulo">Localizar por: </span>
            <span ng-model="sel_endereco" ng-init="sel_endereco='<?php echo $linhaOcorrencia->getEnderecoPrincipal(); ?>'"><?php echo $linhaOcorrencia->getEnderecoPrincipal(); ?></span>
            <br>
        </div>
    </div>
    <div class="box">
        <h4>Endereço</h4>
        <div ng-show="sel_endereco == 'Coordenada'">
            <div class="row">
                <div class="col-sm-5">
                    <span class="titulo">Latitude: </span><span><?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Coordenada'){ echo $linhaCoordenada->getLatitude(); } ?></span>
                </div>
                <div class="col-sm-5">
                    <span class="titulo">Longitude: </span><span><?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Coordenada'){ echo $linhaCoordenada->getLongitude(); } ?></span>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn-default btn-small inline open-AddBookDialog" data-toggle="modal" data-id="map"><span class="glyphicon glyphicon-map-marker"></span></button>
                </div>
            </div>
        </div>
        <div ng-show="sel_endereco == 'Logradouro'">
            <div class="row">
                <div class="col-sm-6">
                    <label>Cidade:</label><input class="form-control " readonly value="<?php echo $linhaLogradouro->getCidade(); ?>"> 

                </div>
                <div class="col-sm-6">
                    <label >Bairro:</label><input class="form-control " readonly value="<?php echo $linhaLogradouro->getBairro(); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                <label >CEP:</label><input class="form-control " readonly value="<?php echo $linhaLogradouro->getCep(); ?>">
                </div>
                <div class="col-sm-6">
                <label>Endereço:</label>
                <input class="form-control " readonly value="<?php echo $linhaLogradouro->getLogradouro(); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label>Número:</label>
                    <input class="form-control" readonly value="<?php echo $linhaLogradouro->getNumero(); ?>">
                </div>
                <div class="col-sm-6">
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
        </div>
    </div>
    <form method="post" action="processa_cadastrar_interdicao.php" onsubmit="return validarFormCadastroInterdicao()">
        <div class="box">
            <input type="hidden" name="id_ocorrencia" value="<?php echo $id_ocorrencia; ?>">
            <div class="row">
                <div class="col-sm-4">
                    <span>Data: <span style="color:red;">*</span></span>
                    <input id="data" name="data" type="date" class="form-control" max="<?php echo date('Y-m-d'); ?>" required>  
                </div>
                <div class="col-sm-4">
                    <span>Horário: <span style="color:red;">*</span></span> 
                    <input type="time" name="horario" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="box">
            <div>
                Motivo: <span style="color:red;">*</span>
                <label for="motivo"></label>
                <select name="motivo" class="form-control endereco-principal" required>
                    <option value="Colapso de edificação">Colapso de edificação</option>
                    <option value="Incêndio/Explosão">Incêndio/Explosão</option>
                    <option value="Deslizamento de solo e/ou rocha">Deslizamento de solo e/ou rocha</option>
                    <option value="Inundação">Inundação</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>
            <div>
                Descrição da interdição: <span style="color:red;">*</span>
                <textarea name="descricao_interdicao" class="form-control" cols="30"  required></textarea>
            </div>
            <div>
                Danos aparentes: <span style="color:red;">*</span>
                <textarea name="danos_aparentes" class="form-control" cols="30" required></textarea>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    Bens afetados: <span style="color:red;">*</span>
                    <label for="bens_afetados"></label>
                    <select name="bens_afetados" class="form-control" required>
                        <option value="Particular">Particular</option>
                        <option value="Público">Público</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <span>Tipo de interdição: <span style="color:red;">*</span></span>
                    <label for="tipo"></label>
                    <select name="tipo" class="form-control" required>
                        <option value="Parcial">Parcial</option>
                        <option value="Total">Total</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="div-btn-cadastrar">
        <input type="submit" class="btn-cadastrar btn-default btn-md" value="Cadastrar">
        </div>
    </form>
</div>
</div>
