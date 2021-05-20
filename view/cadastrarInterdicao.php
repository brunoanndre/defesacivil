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
        <h4>Dados ocorrência:</h4>
        </nav>
        <div class="row">
            <div class="col-sm-4"><span class="titulo">Nº ocorrência: </span><span><?php echo $linhaOcorrencia->getId(); ?></span></div>
            <div class="col-sm-8"><span class="titulo">Título: </span><span><?php echo $linhaOcorrencia->getTitulo(); ?></span></div>
        </div><hr>
        <div>
            <span class="titulo">Endereço principal: </span><span ng-model="sel_endereco" ng-init="sel_endereco='<?php echo $linhaOcorrencia->getEnderecoPrincipal(); ?>'"><?php echo $_POST['endereco_principal']; ?></span>
            <br>
        </div>
        <div ng-show="sel_endereco == 'Coordenada'">
            <div class="row">
                <div class="col-sm-6">
                    <span class="titulo">Latitude: </span><span><?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Coordenada'){ echo $linhaCoordenada->getLatitude();} ?></span>
                </div>
                <div class="col-sm-6">
                    <span class="titulo">Longitude: </span><span><?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Coordenada'){ echo $linhaCoordenada->getLatitude();} ?></span>
                </div>
            </div>
        </div>
        <div ng-show="sel_endereco == 'Logradouro'">
            <div class="row">
                <div class="col-sm-3"><span class="titulo">CEP: </span><span><?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Logradouro'){echo $linhaLogradouro->getCep();} ?></span></div>
                <div class="col-sm-6"><span class="titulo">Logradouro: </span><span><?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Logradouro'){echo $linhaLogradouro->getLogradouro();} ?></span></div>
                <div class="col-sm-3"><span class="titulo">Número: </span><span><?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Logradouro'){echo $linhaLogradouro->getNumero();} ?></span></div>
            </div>
            <div class="row">
                <div class="col-sm-3"><span class="titulo">Bairro: </span><span><?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Logradouro'){echo $linhaLogradouro->getBairro();} ?></span> </div>
                <div class="col-sm-6"><span class="titulo">Cidade: </span><span><?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Logradouro'){echo $linhaLogradouro->getCidade();} ?></span></div>
            </div>
            <div>
                <span class="titulo">Referência: </span><span><?php if($linhaOcorrencia->getEnderecoPrincipal() == 'Logradouro'){echo $linhaLogradouro->getReferencia();} ?></span>
            </div><br>
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
                <textarea name="descricao_interdicao" class="form-control" cols="30" rows="2" maxlength="120" required></textarea>
            </div>
            <div>
                Danos aparentes: <span style="color:red;">*</span>
                <textarea name="danos_aparentes" class="form-control" cols="30" rows="2" maxlength="120" required></textarea>
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
