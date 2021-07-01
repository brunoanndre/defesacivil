<?php
    include 'database.php';

    session_start();
    $id_usuario = $_SESSION['id_usuario'];

    //BUSCA A FOTO DO USUARIO NO BD
    $sql = $pdo->prepare("SELECT foto FROM usuario WHERE id_usuario = :id_usuario");
    $sql->bindValue(":id_usuario", $id_usuario);
    $sql->execute();

    $linha = $sql->fetch();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="main.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    <link rel= "stylesheet" type="text/css" href="css/main.css">
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtw-0gGRpGk6Mdh0XH1L97V288zPTWJlg"></script>
    <link rel="icon" type="image/png" href="images/icone.png"/>
    <title>Defesa Civil</title>
</head>
<body>
    <div ng-app="myApp" ng-controller="myCtrl">
    <?php if($_GET['pagina'] == 'monitorarChamado'){ ?>
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" style="font-size:35px;" href="?pagina=home">Defesa Civil</a>
                </div>
                <?php if($_SESSION['nivel_acesso'] == 4){ ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="logout.php">Sair</a></li>
                    </ul>
                <?php }else{ ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="?pagina=consultarChamado">Voltar</a></li>
                    </ul>
                <?php } ?>
            </div>
        </nav>
    </header>
    <?php }else{ ?>
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" style="font-size: 35px" href="?pagina=home">Defesa Civil</a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Monitoramento <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                        <li><a href="?pagina=visualizarSensores">Sensores</a></li>
                        <li><a href="?pagina=monitorarChamado">Chamados</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Cadastrar <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                        <li><a href="?pagina=cadastrarChamado">Chamado</a></li>
                        <li><a href="?pagina=cadastrarOcorrencia">Ocorrência</a></li>
                        <?php if($_SESSION['nivel_acesso'] == 1){ ?>
                        <li><a href="?pagina=cadastrarUsuario">Usuários</a></li>
                        <?php } ?>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Consultar <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                        <li><a href="?pagina=consultarChamado">Chamados</a></li>
                        <li><a href="?pagina=consultarOcorrencia">Ocorrências</a></li>
                        <li><a href="?pagina=consultarInterdicao">Interdições</a></li>
                        <li><a href="?pagina=consultarNotificacao">Notificações</a></li>
                        <?php if($_SESSION['nivel_acesso'] == 1){ ?>
                        <li><a href="?pagina=consultarUsuario">Usuários</a></li>
                        <?php } ?>
                        <li><a href="?pagina=consultarDocumentos">Documentos</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <img src="data:image/png;base64,<?php echo $linha['foto']; ?>" alt="fotoperfil" class="img-circle img-perfil">
                        </a>
                        <ul class="dropdown-menu">
                        <li><a href="?pagina=perfil">Perfil</a></li>
                        <li><a href="logout.php">Sair</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <div id="conteudo" class="container">
    <?php } ?>