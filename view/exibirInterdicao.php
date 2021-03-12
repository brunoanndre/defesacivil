<?php
    include 'database.php';
    require_once 'dao/IntedicaoDaoPgsql.php';
    require_once 'dao/OcorrenciaDaoPgsql.php';
    
    $ocorrenciadao = New OcorrenciaDaoPgsql($pdo);
    $interdicaodao = New IntedicaoDaoPgsql($pdo);
    $id_interdicao = $_GET['id'];

    $linha = $interdicaodao->buscarInterdicaoEOcorrencia($id_interdicao);

    if($linha['ocorr_endereco_principal'] == 'Logradouro'){
        $id_logradouro = $linha['ocorr_logradouro_id'];
        $linhaLogradouro = $ocorrenciadao->buscaEnderecoPeloId($id_logradouro);
    }
?>

<div class="container positioning">
<div class="jumbotron campo_cadastro">
<?php if(isset($_GET['sucesso'])){ ?>
            <div class="alert alert-success" role="alert">
                Interdição cadastrada com sucesso.
            </div>
    <?php } ?>
    <?php if(isset($_GET['sucessoalt'])){ ?>
            <div class="alert alert-success" role="alert">
                Interdição alterada com sucesso.
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
        <div>
            <span class="titulo">Data e hora: </span>
            <span><?php echo date("d/m/Y H:i", strtotime($linha['data_hora'])); ?></span><br>
            <span class="titulo">Motivo: </span><span><?php echo $linha['motivo']; ?></span><br>
            <span class="titulo">Descrição da interdição: </span><br>
            <textarea name="descricao" rows="5" readonly class="readtextarea"><?php echo $linha['descricao_interdicao']; ?></textarea><br>
            <span class="titulo">Danos aparentes: </span><br>
            <textarea name="descricao" rows="5" readonly class="readtextarea"><?php echo $linha['danos_aparentes']; ?></textarea><br>
            <span class="titulo">Bens afetados: </span><span><?php echo $linha['bens_afetados']; ?></span><br>
            <span class="titulo">Tipo de interdição: </span><span><?php echo $linha['tipo']; ?></span><br>
        </div><hr>
        <div>
            <span class="titulo">Status: </span><span><?php echo ($linha['interdicao_ativa'] == 't') ? 'Interditado':'Desinterditado'; ?></span>
        </div>
        <br>
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
            </div>
        </div>
    </div>
    <?php if($linha['interdicao_ativa'] == 't'){ ?>
    <form action="editarInterdicao.php" method="GET">
        <input type="hidden" name="id" value="<?php $linha['id_interdicao'];?>">
        <input type="submit" class="btn btn-default" value="Editar Interdição">
    </form>
        <?php echo '<a class="printHide href="index.php?pagina=exibirOcorrencia&id='. $linha['id_ocorrencia'] . '"><input type="button" class="btn btn-default btn-md printHide" style="margin-left:90px" value="Voltar"></a>'?>
        <?php echo '<a class="printHide" href= "index.php?pagina=editarInterdicao&id='. $linha['id_interdicao'] . '"><input class=" btn btn-default printHide" value="Editar Interdição"></a>'?>
        <form action="desinterdicao.php" method="post">
            <input type="hidden" name="id_ocorrencia" value="<?php echo $linha['id_ocorrencia']; ?>">
            <input type="hidden" name="id_interdicao" value="<?php echo $linha['id_interdicao']; ?>">
            <div class="div-btn-desinterdicao">
                <input type="submit" class="btn btn-default btn-md btn-desinterdicao printHide" value="Constatar Desinterdição">
            </div>
        </form>
    <?php }?>
    <div class="btn_interdicao" style="padding-left: 30px;">
    <?php if($linha['interdicao_ativa'] == false){ ?>
     <?php echo '<a class="printHide href="index.php?pagina=exibirOcorrencia&id='. $linha['id_ocorrencia'] . '"><input type="button" class="btn btn-default btn-md printHide" style="margin-left:90px" value="Voltar"></a>'?>
        <?php echo '<a class="printHide" href= "index.php?pagina=editarInterdicao&id='. $linha['id_interdicao'] . '"><input class=" btn btn-default printHide" value="Editar Interdição"></a>'?>
        <input class="btn btn-default" value="Interditar">
        <?php };?>
    </div>
</div>
</div>
