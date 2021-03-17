<?php

require_once 'database.php';
require_once 'dao/IntedicaoDaoPgsql.php';
require_once 'dao/OcorrenciaDaoPgsql.php';
require_once 'dao/EnderecoDaoPgsql.php';

$enderecodao = New EnderecoDaoPgsql($pdo);    
$ocorrenciadao = New OcorrenciaDaoPgsql($pdo);
$interdicaodao = New IntedicaoDaoPgsql($pdo);

$id_interdicao = $_GET['id'];

$linha = $interdicaodao->buscarInterdicaoEOcorrencia($id_interdicao);

if($linha['ocorr_endereco_principal'] == 'Logradouro'){
    $id_logradouro = $linha['ocorr_logradouro_id'];
    $linhaLogradouro = $enderecodao->buscarPeloId($id_logradouro);
}

$data = date("d/m/Y", strtotime($linha['data_hora']));

?>
<div class="container positioning">
<div class="jumbotron campo_cadastro">
    <?php if(isset($_GET['erroDB'])){ ?>
            <div class="alert alert-danger" role="alert">
                Falha ao alterar a interdição.
            </div>
            <?php } ?>   
    <div class="box">
            <div class="row cabecalho">
                <div class="col-sm-6 printHide">
                    <nav class="texto-cabecalho">Estado de Santa Catarina</nav>
                    <nav class="texto-cabecalho">Prefeitura de Balneário Camboriú</nav>
                    <nav class="texto-cabecalho">Secretaria de segurança</nav>
                    <nav class="texto-cabecalho">Defesa Civil</nav>
                </div>
                <div class="col-sm-6 print-interdicao-img">
                    <img src="images/balneario-camboriu.png" alt="prefeitura-balneario-camboriu" class="img-cabecalho">
                </div>
            </div>
            <h3 class="text-center">Registro de interdição</h3>
            <button class="printHide" style="background-color: white; border:none;" onclick="print()"><img src="images/print.png" style="width: 50px; height:auto"></button>
        </div>
    <div class="box">
        <nav>
        <h4>Dados ocorrência:</h4>
        </nav>
        <div class="row">
            <div class="col-sm-4"><span class="titulo">Nº ocorrência: </span><span><?php echo $linha['id_ocorrencia']; ?></span></div>
            <div class="col-sm-8"><span class="titulo">Título: </span><span><?php echo $linha['ocorr_titulo']; ?></span></div>
        </div><hr>
        <div>
            <span class="titulo">Endereço principal: </span><span ng-model="sel_endereco" ng-init="sel_endereco='<?php echo $linha['ocorr_endereco_principal']; ?>'"><?php echo $linha['ocorr_endereco_principal']; ?></span>
            <br>
        </div>
        <div ng-show="sel_endereco == 'Coordenada'">
            <div class="row">
                <div class="col-sm-5">
                    <span class="titulo">Latitude: </span><span><?php echo $linha['ocorr_coordenada_latitude']; ?></span>
                </div>
                <div class="col-sm-5">
                    <span class="titulo">Longitude: </span><span><?php echo $linha['ocorr_coordenada_longitude']; ?></span>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn-default btn-small inline open-AddBookDialog" data-toggle="modal" data-id="map"><span class="glyphicon glyphicon-map-marker"></span></button>
                </div>
            </div>
        </div>
        <div ng-show="sel_endereco == 'Logradouro'">
            <div class="row">
                <div class="col-sm-3"><span class="titulo">CEP: </span><span><?php echo $linhaLogradouro->getCep(); ?></span></div>
                <div class="col-sm-6"><span class="titulo">Logradouro: </span><span><?php echo $linhaLogradouro->getLogradouro(); ?></span></div>
                <div class="col-sm-3"><span class="titulo">Número: </span><span><?php echo $linhaLogradouro->getNumero(); ?></span></div>
            </div>
            <div class="row">
                <div class="col-sm-3"><span class="titulo">Bairro: </span><span><?php echo $linhaLogradouro->getBairro(); ?></span> </div>
                <div class="col-sm-6"><span class="titulo">Cidade: </span><span><?php echo $linhaLogradouro->getCidade(); ?></span></div>
            </div>
            <div>
                <span class="titulo">Referência: </span><span><?php echo $linhaLogradouro->getReferencia(); ?></span>
            </div><br>
        </div>
    </div>
    <div class="box">
        <nav>
            <h4>Dados interdição:</h4>
        </nav>
        <div>
            <span class="titulo">Nº interdição: </span><span><?php echo $linha['id_interdicao']; ?></span>
        </div><hr>
        <form method="post" action="processa_editar_interdicao.php" onsubmit="return validarFormCadastroInterdicao()">
        <div class="box">
        <input type="hidden" name="id_interdicao" value="<?php echo $linha['id_interdicao']; ?>">
            <div class="row">
                <div class="col-sm-4">
                    <span>Data: <span style="color:red;">*</span></span>
                    <input  name="data" id="data_interdicao" class="form-control" value="<?php echo date("d/m/Y", strtotime($linha['data_hora']))?>" maxlength="10" required onkeydown ="formatarDataInterdicao()">  
                </div>
                <div class="col-sm-4">
                    <span>Horário: <span style="color:red;">*</span></span> 
                    <input type="time" name="horario" class="form-control" value="<?php echo date("H:i", strtotime($linha['data_hora'])); ?>" required>
                </div>
            </div>
        </div>
        <div class="box">
            <div>
                Motivo: <span style="color:red;">*</span>
                <label for="motivo"></label>
                <select name="motivo" class="form-control endereco-principal" required>
                    <option <?php if($linha['motivo'] == 'Colapso de edificação'){ echo "selected";}?> value="Colapso de edificação">Colapso de edificação</option>
                    <option <?php if($linha['motivo'] == 'Incêndio/Explosão'){ echo "selected";}?> value="Incêndio/Explosão">Incêndio/Explosão</option>
                    <option <?php if($linha['motivo'] == 'Deslizamento de solo e/ou rocha'){ echo "selected";}?> value="Deslizamento de solo e/ou rocha">Deslizamento de solo e/ou rocha</option>
                    <option value="Inundação">Inundação</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>
            <div>
                Descrição da interdição: <span style="color:red;">*</span>
                <textarea name="descricao_interdicao" class="form-control" cols="30" rows="2" maxlength="120" style="resize:none;" required><?php echo $linha['descricao_interdicao']; ?></textarea>
            </div>
            <div>
                Danos aparentes: <span style="color:red;">*</span>
                <textarea name="danos_aparentes" class="form-control" cols="30" rows="2" maxlength="120" required style="resize:none;"><?php echo $linha['danos_aparentes']; ?></textarea>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    Bens afetados: <span style="color:red;">*</span>
                    <label for="bens_afetados"></label>
                    <select name="bens_afetados" class="form-control" required>
                        <option <?php if($linha['bens_afetados'] == 'Particular'){ echo "selected";}?>>Particular</option>
                        <option <?php if($linha['bens_afetados'] == 'Público'){ echo "selected"; }?>>Público</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <span>Tipo de interdição: <span style="color:red;">*</span></span>
                    <label for="tipo"></label>
                    <select name="tipo" class="form-control" required>
                        <option <?php if($linha['tipo'] == 'Parcial'){ echo "selected";}?> value="Parcial">Parcial</option>
                        <option <?php if($linha['tipo'] == 'Total'){ echo "selected";}?> value="Total">Total</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="div-btn-cadastrar">
        <input type="submit" class="btn-cadastrar btn-default btn-md" value="Salvar">
        </div>
    </form>
</div>
