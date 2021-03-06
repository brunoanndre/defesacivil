<?php
    include 'database.php';
    require_once 'dao/UsuarioDaoPgsql.php';

    $usuariodao = new UsuarioDaoPgsql($pdo);

    $lista = $usuariodao->findAll();
?>
<div>
    <?php if(isset($_GET['sucesso'])) { ?>
        <div class="alert alert-success" role="alert">
            Usuario excluído com sucesso.
        </div>
    <?php } ?>
        <div class="box">
        <div class="row">
            <a href="index.php?pagina=cadastrarUsuario"><button style="background-color: #f38637;" class="btn btn-default atalhoCadastrarChamado">Cadastrar usuário</button></a>
        </div>
        <h3 class="text-center">Consulta de usuários</h3>
        </div>
        <div class="box">
            <table id="myTable" class="display" style="width:100%">
            <thead><tr>
                <th></th>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
            </tr></thead>
            <tbody>
            <?php
                session_start();
                if(!$lista){
                    echo '<tr><td colspan="5" class="text-center">Nenhum usuário encontrado</td></tr>';
                }
                foreach($lista as $usuario){
                        echo '<tr><td class="text-center"><a href="index.php?pagina=exibirUsuario&id='.$usuario->getId().'"><span class="glyphicon glyphicon-eye-open"></span></a></td>';
                        echo '<td class="elimina-tabela">'.$usuario->getId().'</td>';
                        echo '<td>'.$usuario->getNome().'</td>'; 
                        echo '<td>'.$usuario->getEmail().'</td>';
                        echo '<td class="elimina-tabela">'.$usuario->getTelefone().'</td></tr>';
                }
            ?>
            <tbody>
        <table>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#myTable').DataTable( {
        "language": {
            "lengthMenu": "Exibir _MENU_ Registros por página",
            "zeroRecords": "Nenhuma interdição encontrada",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhuma interdição registrada",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            }
            }
    } );
} );
</script>