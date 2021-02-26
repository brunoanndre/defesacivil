<?php 
require_once 'database.php';
?>
<div class="container positioning">
    <div class="jumbotron campo_cadastro">

    <?php if(isset($_GET['CPFInvalido']) || isset($_GET['emailexistente'])){
        $sql = $pdo->prepare("SELECT nome, cpf, telefone, email, foto FROM usuario u 
                INNER JOIN dados_login dl ON u.id_usuario = dl.id_usuario 
                WHERE u.id_usuario = :id");
        $sql->bindValue(":id", $_SESSION['id_usuario']);
        $sql->execute();

        $linha = $sql->fetch(); 
    } ?>
    <form method="post" action="processa_editar_perfil.php" enctype="multipart/form-data">
        <div class="box">
            <h3 class="text-center" style="margin:5px;">Perfil</h3>
        </div>
        <div class="box">
            <div class="row">
                <div class="col-sm-6 text-center">
                <img src="data:image/png;base64,<?php echo $linha['foto']; ?>" alt="fotoperfil" class="img-circle img-perfil-expandida">
                <input id="foto" name="foto" type="file" accept="image/png,image/jpeg" value="Localização do Arquivo..." size="30" maxlength="30">
                </div>
                <div class="col-sm-6">
                    <br><span class="titulo">Nome: </span> 
                    <input id="nome" name="nome" type="text" autocomplete="off" class="form-control" value="<?php echo $_POST['nome']; echo $linha['nome']; ?>"  required pattern="[a-zA-Z\u00C0-\u00FF\s]+" title="Apenas letras e espaço">
                    <hr> 
                    <span class="titulo">CPF: </span>
                    <input id="cpf" name="cpf" type="text" value="<?php echo $_POST['cpf']; echo $linha['cpf'];  ?>" class="form-control"> 
                    <?php if(isset($_GET['CPFInvalido'])) { ?>
                    <span class="alertErro">CPF inválido!</span>
                    <?php }?>
                    <hr>
                    <span class="titulo">Email: </span>
                    <input name="email" type="email" class="form-control" value="<?php echo $_POST['email']; echo $linha['email']; ?>" required pattern="\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+" title="email@dominio.com">
                    <?php if(isset($_GET['emailexistente'])) { ?>
                    <span class="alertErro">O email informado já existe!</span>
                    <?php } ?>
                    <br>
                    <span class="titulo">Telefone: </span>
                    <input id="telefone" name="telefone" type="text" class="form-control" value="<?php echo $_POST['telefone']; echo $linha['telefone']; ?>" required pattern="\([0-9]{2}\)[\s][0-9]{4,5}-[0-9]{4}" title="(XX) XXXXX-XXXX">
                    <span id="erroTelefone" class="alertErro hide">Telefone inválido.</span>
                    <hr>

                    <span class="titulo">Nivel de acesso: </span><?php if($_SESSION['nivel_acesso']==1){echo 'Diretor';}else if($_SESSION['nivel_acesso']==2){echo 'Coordenador';}else{echo 'Agente';} ?><br>
                    <br>
                </div>
            </div>
        </div>
       <divl class="btn-salvar-editarPerfil">
       <input type="submit" class="btn btn-default btn-md" value="Salvar">
       </div>
    </form>
    </div>

</div>