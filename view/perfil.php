<?php 
    include 'database.php';

    session_start();
    $id = $_SESSION['id_usuario'];
            
    $consulta_login = $pdo->prepare("SELECT u.*, dl.email FROM usuario u 
            INNER JOIN dados_login dl ON u.id_usuario = dl.id_usuario WHERE u.id_usuario = :id");
    $consulta_login->bindValue(":id", $id);
    $consulta_login->execute();
    


    $linha = $consulta_login->fetch();  
?>

<div class="container positioning">
<div class="jumbotron text-center">
    <div class="box">
        <h3 class="text-center" style="margin:5px;">Perfil</h3>
    </div>
    <?php if(isset($_GET['sucesso'])){ ?>
            <div class="alert alert-success" role="alert">
                Perfil alterado com sucesso.
            </div>
    <?php } ?>
    <?php if(isset($_GET['erroDB'])){ ?>
            <div class="alert alert-danger" role="alert">
                Falha ao alterar o perfil.
            </div>
            <?php } ?>

    <div class="box">
        <div class="row">
            <div class="col-sm-6 text-center">
            <img src="data:image/png;base64,<?php echo $linha['foto']; ?>" alt="fotoperfil" class="img-circle img-perfil-expandida">
            </div>
            <div class="col-sm-6">
                <br><span class="titulo">Nome: </span><?php echo $linha['nome']; ?>
                <hr>
                <span class="titulo">CPF: </span><?php echo $linha['cpf']; ?>
                <hr>
                <span class="titulo">Email: </span><?php echo $linha['email']; ?>
                <br>
                <span class="titulo">Telefone: </span><?php echo $linha['telefone']; ?>
                <hr>
                <span class="titulo">Nivel de acesso: </span><?php if($linha['nivel_acesso']==1){echo 'Diretor';}else if($linha['nivel_acesso']==2){echo 'Coordenador';}else{echo 'Agente';} ?><br>
                <br>
            </div>
        </div>
    </div>
    <div class="perfil-btn">
        <form method="post" action="index.php?pagina=EditarPerfil">
        <input type="hidden" name="foto" id="foto" value="<?php echo $linha['foto']; ?>">
        <input type="hidden" name="nome" id="nome" value="<?php echo $linha['nome']; ?>">
        <input type="hidden" name="cpf" id="cpf" value="<?php echo $linha['cpf']; ?>">
        <input type="hidden" name="email" id="email" value="<?php echo $linha['email'] ?>">
        <input type="hidden" name="telefone" id="telefone" value="<?php echo $linha['telefone'] ?>">
        <input class="btn btn-default" type="submit" value="Editar Perfil">
        </form>
        <a class="btn btn-default" href="?pagina=alterarSenha">Alterar senha</a>
    </div>
</div>
</div>