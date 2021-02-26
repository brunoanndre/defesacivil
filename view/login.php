<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
    <link rel= "stylesheet" type="text/css" href="../css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="images/icone.png"/>
    <title>Defesa Civil</title>
</head>
<body>
    <div ng-app="myApp" ng-controller="myCtrl">
    <header class="login-header">
            <div class="col-md-3">
                <img src="../images/logo.jpg" alt="DefesaCivil" class="img-rounded corner-img">
            </div>
            <div class="div-login-title"><span class="page-title">Defesa Civil</span></div>
    </header>
    <div class="row">
        <div class="col-md-4">
                <h2 class="head-alertas">Alertas emitidos</h2>
                <div id="noticias" class="container-noticias"></div>
        </div>
            <div class="col-md-5" style="text-align:center;">
                <div class="login-box">
                    <div class="jumbotron login-inputs-area">
                    <h4 class="head-login">Acesso Interno</h4>
                        <form method="post" action="../processa_login.php">
                            <div class="input-group">
                                <input type="text" class="form-control" name="email" placeholder="Email" autofocus>
                            </div>
                            <div class="input-group">
                                <input type="password" class="form-control" name="senha" placeholder="Senha">
                            </div>
                            <div class="esqueceuSenhaArea"><a class="esqueceuSenha" href="index.php?pagina=esqueceuSenha"><u>Esqueceu a senha?</u></a></div>
                            <?php if(isset($_GET['erro'])){ ?>
                                <div class="alert alert-danger" role="alert">
                                    Email e/ou  senha errados.
                                </div>
                            <?php } ?>
                            <?php if(isset($_GET['erroBD'])){ ?>
                                <div class="alert alert-danger" role="alert">
                                    Problema ao conectar com o banco de dados.
                                </div>
                            <?php } ?>

                            <input type="submit" value="Entrar" class="btn btn-default btn-md btn-login">
                        </form>
       
                    </div>
                </div>
            </div>
    </div>

    <!--<div class="row">
        <div class="col-md-3"></div>
        <div id="noticias" class="col-md-4 container-noticias"></div>
        <div class="col-md-5"></div>
    </div>-->
    
    
