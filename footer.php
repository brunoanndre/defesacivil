    <footer>
        <?php if(!isset($_SESSION['login'])){ ?>
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <div class="footer-area">
            <div class="footer-left-area">
                <span><strong>DEFESA CIVIL DO MUNICÍPIO DE BALNEÁRIO CAMBORIÚ</strong></span><br>
                <span><strong>Alameda dos Estados Policial Luiz Carlos Rosa nº 25, CEP 88339-122</strong></span><br>
                <span><strong>Balneário Camboriú - SC - Fone: (47) 3268-3133</strong></span><br>
                <img src="images/logo_bc.png" width="200px">

            </div>
            <div class="footer-right-area img-rounded">
                <div><span><strong> OUTROS NÚMEROS:</strong></span><br></div>
                <div><span><strong>190 - Polícia Militar </strong></span><br></div>
                <div><span><strong>193 - Bombeiros</strong></span><br></div>
                <div> <span><strong>(48) 3664 7000 - Defesa Civil Estadual</strong></span></div>
            </div>
         </div>
        <?php } ?>
    </footer>
    
    <?php if($_GET['pagina'] != 'monitorarChamado'){ ?>
        </div>
    <?php } ?>
    </div>
    <!--<script src="angular/angular.js"></script>-->
    <script src="main.js"></script>
    <script>
        if(window.location.href == "http://defesacivil.bc.sc.gov.br/index.php?pagina=visualizarSensores"){
            ativaJson();
            setInterval(function(){ ativaJson(); }, 3600000);
        }else{
            $("#body").css("background-color", "#ffe");
        }
        if(window.location.href == "http://defesacivil.bc.sc.gov.br/index.php?pagina=monitorarChamado"){
            monitorarChamado();
            setInterval(function(){ monitorarChamado(); }, 60000);
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAu6rNYe4C_omXFiKMY6DuCk6wgklzLInY&callback=myMap"></script>
</body>
</html>
