<?php
    include 'database.php';
    require_once 'dao/IntedicaoDaoPgsql.php';
    require_once 'dao/OcorrenciaDaoPgsql.php';
    require_once 'dao/EnderecoDaoPgsql.php';

    $enderecodao = New EnderecoDaoPgsql($pdo);
    $ocorrenciadao = New OcorrenciaDaoPgsql($pdo);
    $interdicaodao = New IntedicaoDaoPgsql($pdo);
    $id_interdicao = $_GET['id'];
    try{
        $linha = $interdicaodao->buscarInterdicaoEOcorrencia($id_interdicao);
    }catch(PDOException $e){
        echo $e->getMessage();
    }

    if($linha['ocorr_endereco_principal'] == 'Logradouro'){
        $id_logradouro = $linha['ocorr_logradouro_id'];
        $linhaLogradouro = $enderecodao->buscarPeloId($id_logradouro);
    }else{
        $id_coordenada = $ocorrenciadao->buscarPeloId($linha['id_coordenada']);
        $linhaCoordenada = $enderecodao->buscarIdCoordenada($id_coordenada);
    }

?>

<div class="printAreaInterdicao printShow">
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
            <h3 class=""><?php echo 'Interdição Nº ' . $id_interdicao . '/' . date('Y'); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <h5><strong>Rua:</strong></h5>
            <?php echo $linhaLogradouro->getLogradouro() ?>
        </div>
        <div class="col-sm-10 borderPrint">
            <h5><strong>Número:</strong></h5>
            <?php echo $linhaLogradouro->getNumero(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <h5><strong>Bairro:</strong></h5> <?php echo $linhaLogradouro->getBairro();?>
        </div>
        <div class="col-sm-10 borderPrint">
            <h5><strong>Localidade:</strong></h5> <?php echo $linhaLogradouro->getCidade(); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-10 borderPrint100">
            <h5><strong>Ocorrência:</strong></h5>
            <?php echo $linha['id_ocorrencia'] . ' - ' . $linha['ocorr_titulo'];  ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <h5><strong>Data:</strong></h5>
            <?php echo date('d/m/Y', strtotime($linha['data_hora'])); ?>
        </div>
        <div class="col-sm-10 borderPrint">
            <h5><strong>Tipo de interdição:</strong></h5>
            <?php echo $linha['tipo']; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <h5><strong>Motivo:</strong></h5>
            <?php echo $linha['motivo']; ?>
        </div>
        <div class="col-sm-10 borderPrint">
            <h5><strong>Bens afetados:</strong></h5>
            <?php echo $linha['bens_afetados']; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint100 text-center">
            <h4><strong>Descrição da Ocorrência </strong></h4>
        </div>
    </div>
    <div class="row divDescricaoArea">
        <p align="justify"><?php echo $linha['descricao_interdicao']; ?></p>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint100 text-center">
            <h4><strong>Danos aparentes</strong></h4>
        </div>
    </div>
    <div class="row divDescricaoArea">
        <p align="justify"><?php echo $linha['danos_aparentes']; ?></p>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint100 text-center">
            <h4><strong>Termo de Interdição</strong></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 borderPrint">
            <label><strong>Representante da Defesa Civil:</strong></label><br>
        </div>
        <div class="col-sm-10 borderPrint"><br>
            <label><strong>Ass:</strong></label>
        </div>
    </div>
</div>


<div class="container positioning printHide">
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
            <div class="row cabecalho">
                <div class="col-sm-6">
                    <nav class="texto-cabecalho">Estado de Santa Catarina</nav>
                    <nav class="texto-cabecalho">Prefeitura de Balneário Camboriú</nav>
                    <nav class="texto-cabecalho">Secretaria de segurança</nav>
                    <nav class="texto-cabecalho">Defesa Civil</nav>
                </div>
                <div class="col-sm-6">
                    <img src="images/logo_bc.png" alt="prefeitura-balneario-camboriu" class="img-cabecalho">
                </div>
            </div>
            <h3 class="text-center"><?php echo 'Interdição Nº ' . $id_interdicao . '/' . date('Y'); ?></h3>
            <button style="border:none;" onclick="print()"><img src="images/print.png" style="width: 50px; height:auto"></button>
    <div class="box">
        <nav>
        <h4>Dados da ocorrência:</h4>
        </nav>
        <div class="row">
            <div class="col-sm-6">
                <label>Nº da ocorrência:</label>
                <a href="index.php?pagina=exibirOcorrencia&id= <?php echo $linha['id_ocorrencia']; ?> "><?php echo  $linha['id_ocorrencia'] ?></a>                
            </div>
            <div class="col-sm-6">
                <label>Data da ocorrência:</label><span><?php echo $linha['data_ocorrencia']; ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <label>Titulo da ocorrência:</label>
                <input class="form-control" readonly value="<?php echo $linha['ocorr_titulo']; ?>">
            </div>
        </div>
        <div class="hidden">
            <span class="titulo">Localizar por: </span>
            <span ng-model="sel_endereco" ng-init="sel_endereco='<?php echo $linha['ocorr_endereco_principal']; ?>'"><?php echo $linha['ocorr_endereco_principal']; ?></span>
            <br>
        </div>
    </div>
    <div class="box">
        <h4>Endereço</h4>
        <div ng-show="sel_endereco == 'Coordenada'">
            <div class="row">
                <div class="col-sm-5">
                    <span class="titulo">Latitude: </span><span><?php if($linha['ocorr_endereco_principal'] == 'Coordenada'){ echo $linhaCoordenada->getLatitude(); } ?></span>
                </div>
                <div class="col-sm-5">
                    <span class="titulo">Longitude: </span><span><?php if($linha['ocorr_endereco_principal'] == 'Coordenada'){ echo $linhaCoordenada->getLongitude(); } ?></span>
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
    <div class="box">
        <h4>Dados interdição:</h4>
        <div class="row">
            <div class="col-sm-6">
                <label>Data e hora:</label>
                <input class="form-control" readonly value="<?php echo date("d/m/Y H:i", strtotime($linha['data_hora'])); ?>">
            </div>
            <div class="col-sm-6">
                <label>Motivo:</label>
                <input class="form-control" readonly value="<?php echo $linha['motivo']; ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <label>Bens afetados:</label>
                <input class="form-control" readonly value="<?php echo $linha['bens_afetados']; ?>">
            </div>
            <div class="col-sm-6">
                <label>Tipo de interdição:</label>
                <input class="form-control" readonly value="<?php echo $linha['tipo']; ?>">
            </div>
        </div>
        <h4 class="text-center">Descrição da interdição</h4>
        <div class="row">
            <div class="col-sm-12">
                <textarea id="descricao" name="descricao" readonly class="form-control " style="resize:none;" rows="10">
                    <?php echo $linha['descricao_interdicao'];  ?>
                </textarea>
            </div>
        </div><hr>
        <h4 class="text-center">Danos aparentes</h4>
        <div class="row">
            <div class="col-sm-12">
                <textarea id="descricao" name="descricao" readonly class="form-control " style="resize:none;" rows="10">
                   <?php echo $linha['danos_aparentes'];?>
                </textarea>
            </div>
        </div><hr>
            <span class="titulo">Status: </span><span><?php echo ($linha['interdicao_ativa'] == 't') ? 'Interditado':'Desinterditado'; ?></span>
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
        <?php echo '<a class="printHide" href="index.php?pagina=exibirOcorrencia&id='. $linha['id_ocorrencia'] . '"><input type="button" class="btn btn-default printHide" style="margin-left:40%" value="Voltar"></a>'?>
        <?php echo '<a class="printHide" href= "index.php?pagina=editarInterdicao&id='. $linha['id_interdicao'] . '"><input type="button"class=" btn btn-default printHide" value="Editar"></a>'?>
        <form action="desinterdicao.php" method="post">
            <input type="hidden" name="id_ocorrencia" value="<?php echo $linha['id_ocorrencia']; ?>">
            <input type="hidden" name="id_interdicao" value="<?php echo $linha['id_interdicao']; ?>">
            <div class="div-btn-desinterdicao">
                <input type="submit" class="btn btn-default btn-md btn-desinterdicao printHide" style="margin-top: 5px;" value="Constatar Desinterdição">
            </div>
        </form>
    <?php }?>
    <div class="btn_interdicao row" style="padding-left: 30px;">
    <?php if($linha['interdicao_ativa'] == false){ ?>
        <form action="interdicao.php" method="post">
        <input type="hidden" name="id" value="<?php echo $linha['id_interdicao']; ?>">
        <input type="submit" style="margin-left: 40%;" class="btn btn-default" value="Interditar">
        </form>
     <?php echo '<a class="printHide href="index.php?pagina=exibirOcorrencia&id='. $linha['id_ocorrencia'] .'"><input type="button" class="btn btn-default btn-md printHide" style="margin-left:37%; margin-top:5px;" value="Voltar"></a>'?>
        <?php echo '<a class="printHide" href= "index.php?pagina=editarInterdicao&id='. $linha['id_interdicao'] . '"><input type="button" style="margin-top:5px;" class=" btn btn-default printHide" value="Editar"></a>'?>

        <?php };?>
    </div>
</div>
</div>
