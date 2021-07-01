<?php
    include 'database.php';
    require_once 'dao/UsuarioDaoPgsql.php';

    $usuariodao = new UsuarioDaoPgsql($pdo);
    $id_usuario = $_GET['id'];

    $linha = $usuariodao->findById($id_usuario);
?>

<div class="container positioning">
<div class="jumbotron text-center">
    <?php if(isset($_GET['sucesso'])){ ?>
            <div class="alert alert-success" role="alert">
                Usuario editado com sucesso.
            </div>
    <?php } ?>
    <div class="box">
        <h3 class="text-center" style="margin:5px;">Usuário</h3>
    </div>
    <div class="box">
        <div class="row">
            <div class="col-sm-6">
                <img src="data:image/png;base64,<?php echo $linha->getFoto(); ?>" alt="fotoperfil" class="img-circle img-perfil-expandida">
            </div>
            <div class="col-sm-6">
                <br><span class="titulo">Nome: </span><?php echo $linha->getNome(); ?>
                <hr>
                <span class="titulo">CPF: </span><?php echo $linha->getCPF(); ?>
                <hr>
                <span class="titulo">Email: </span><?php echo $linha->getEmail(); ?><hr>
                <br>
                <span class="titulo">Telefone: </span><?php echo $linha->getTelefone(); ?>
                <hr>
                <span class="titulo">Nivel de acesso: </span><?php if($linha->getAcesso() == 1){echo 'Diretor';}else if($linha->getAcesso() == 2){echo 'Coordenador';}else if($linha->getAcesso() == 3){echo 'Agente';}else{echo 'Monitor';} ?>
                <br><br>
            </div>
        </div>
    </div>
    <div style="display: flex; justify-content: center;">
    <?php if($_SESSION['nivel_acesso'] == 1){ ?>
    <form action="index.php?pagina=EditarUsuario" method="post">
    <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
    <input type="submit" value="Editar" class="btn btn-default">
    </form>
    <form action="excluir_usuario.php" method="post" onsubmit="return confirm('Você realmente deseja excluir o usuário?');">
        <input type="hidden" name="id" value="<?php echo $id_usuario; ?>">
        <?php if(strcmp($id_usuario,$_SESSION['id_usuario']) != 0){ ?> 
        <input type="submit" value="Excluir" class="btn btn-default">
        <?php } ?>
    </form>
    <?php }?>
    </div>
</div>
</div>