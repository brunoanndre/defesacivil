<?php
    include 'database.php';
    require_once 'dao/UsuarioDaoPgsql.php';

    $usuariodao = new UsuarioDaoPgsql($pdo);
    $id_usuario = $_GET['id'];

    $linha = $usuariodao->findById($id_usuario);
?>

<div class="container positioning">
<div class="jumbotron text-center">
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
                <?php session_start();
                      if($_SESSION['nivel_acesso'] == 1){ ?>
                <span class="titulo">CPF: </span><?php echo $linha->getCPF(); ?>
                <hr>
                <?php } ?>
                <span class="titulo">Email: </span><?php echo $linha->getEmail(); ?>
                <br>
                <span class="titulo">Telefone: </span><?php echo $linha->getTelefone(); ?>
                <?php session_start();
                      if($_SESSION['nivel_acesso'] == 1){ ?>
                <hr>
                <span class="titulo">Nivel de acesso: </span><?php if($linha->getAcesso() == 1){echo 'Diretor';}else if($linha->getAcesso() == 2){echo 'Coordenador';}else if($linha->getAcesso() == 3){echo 'Agente';}else{echo 'Monitor';} ?>
                <?php } ?>
                <br><br>
            </div>
        </div>
    </div>
    <form action="excluir_usuario.php" method="post" onsubmit="return confirm('Você realmente deseja excluir o usuário? Essa exclusão será permanente');">
        <input type="hidden" name="id" value="<?php echo $id_usuario; ?>">
        <input type="submit" value="Excluir" class="btn btn-default">
    </form>
</div>
</div>