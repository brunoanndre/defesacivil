<?php 
require_once 'database.php';
require_once 'dao/UsuarioDaoPgsql.php';

$usuariodao = New UsuarioDaoPgsql($pdo);
session_start();
$id_usuario = $_POST['id_usuario'];

$linhaUsuario = $usuariodao->findById($id_usuario);

?>
<div class="container positioning">
    <div class="jumbotron campo_cadastro">

    <form method="post" action="processa_editar_usuario.php" enctype="multipart/form-data">
        <div class="box">
            <h3 class="text-center" style="margin:5px;">Usuário</h3>
        </div>
        <div class="box">
            <div class="row">
                <div class="col-sm-6 text-center">
                <img src="data:image/png;base64,<?php echo $linhaUsuario->getFoto(); ?>" alt="fotoperfil" class="img-circle img-perfil-expandida">
                <input id="foto" name="foto" type="file" accept="image/png,image/jpeg" value="Localização do Arquivo..." size="30" maxlength="30">
                </div>
                <div class="col-sm-6">
                    <br><span class="titulo">Nome: </span> 
                    <input id="nome" name="nome" type="text" autocomplete="off" class="form-control" value="<?php echo $linhaUsuario->getNome() ?>"  required pattern="[a-zA-Z\u00C0-\u00FF\s]+" title="Apenas letras e espaço">
                    <hr> 
                    <span class="titulo">CPF: </span>
                    <input id="cpf" name="cpf" type="text" value="<?php  echo $linhaUsuario->getCPF();  ?>" class="form-control"> 
                    <?php if(isset($_GET['CPFInvalido'])) { ?>
                    <span class="alertErro">CPF inválido!</span>
                    <?php }?>
                    <hr>
                    <span class="titulo">Email: </span>
                    <input name="email" type="email" class="form-control" value="<?php echo $linhaUsuario->getEmail(); ?>" required pattern="\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+" title="email@dominio.com">
                    <?php if(isset($_GET['emailexistente'])) { ?>
                    <span class="alertErro">O email informado já existe!</span>
                    <?php } ?>
                    <br>
                    <span class="titulo">Telefone: </span>
                    <input id="telefone" name="telefone" type="text" class="form-control" value="<?php echo $linhaUsuario->getTelefone(); ?>" required pattern="\([0-9]{2}\)[\s][0-9]{4,5}-[0-9]{4}" title="(XX) XXXXX-XXXX">
                    <span id="erroTelefone" class="alertErro hide">Telefone inválido.</span>
                    <hr>

                    <span class="titulo">Nivel de acesso: </span>
                    <select id="acesso" class="form-control" name="acesso" ng-model="acesso" ng-init="acesso ='<?php echo $linhaUsuario->getAcesso(); ?>'" required>
                    <option value="1">Diretor</option>
                    <option value="2">Coordenador</option>
                    <option value="3">Agente</option>
                    </select>
                    <br>
                </div>
            </div>
        </div>
       <divl class="btn-salvar-editarPerfil">
       <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
       <input type="submit" class="btn btn-default btn-md" value="Salvar">
       </div>
    </form>
    </div>

</div>                