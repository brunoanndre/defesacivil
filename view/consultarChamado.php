<?php
    include 'database.php';
    include 'dao/ChamadoDaoPgsql.php';
    include 'dao/UsuarioDaoPgsql.php';

    $usuariodao = new UsuarioDaoPgsql($pdo);
    $chamadodao = new ChamadoDaoPgsql($pdo);

    $pesquisa_chamado = addslashes($_POST['pesquisa_chamado']);

    if(isset($_POST['pesquisa_chamado']) && $pesquisa_chamado != null){
        $parametro = 'normal';

        if($_POST['finalizado'] != true){
            $parametro = 'usado_false';
        }
        $consulta_chamados = $chamadodao->buscarConsulta($parametro);
    }else{
        $parametro = 'normal';
        if($_POST['finalizado'] == true){
            if($_POST['cancelado'] == true){
                $parametro = 'usado_true';
            }else{
                $parametro = 'usado_true_cancelado_false';
            }
        }else{
            if($_POST['cancelado'] == true){
                $parametro = 'usado_cancelado_true';
            }else{
                $parametro = 'usado_false';
            }
        }
        $consulta_chamados = $chamadodao->buscarConsulta($parametro);
    }
?>

<div>
<?php if(isset($_GET['sucesso'])) { ?>
        <div class="alert alert-success" role="alert">
            Chamado cancelado com sucesso.
        </div>
    <?php } ?>
<h3 class="text-center">Consulta de chamados</h3>

        <form class="input-group" method="post" action="index.php?pagina=consultarChamado&n=0">
            <span class="ocorrencias_encerradas">Encerrados: </span>
            <input name="finalizado" onchange="this.form.submit()" value="true" type="checkbox" <?php if($_POST['finalizado']==true)echo 'checked'; ?>>
            <span class="ocorrencias_encerradas">Cancelados: </span>
            <input name="cancelado" onchange="this.form.submit()" value="true" type="checkbox" <?php if($_POST['cancelado']==true)echo 'checked'; ?>>
        </form>
    </div>
    <div class="box">
        <table id="myTable" class="row-border" style="width:100%">
            <thead><tr>
                <th><!--<span class="glyphicon glyphicon-fullscreen"></span>--></th>
                <th>ID</th>
                <th style="width: 100px;">Data</th>
                <th>Origem</th>
                <th>Endereço</th>
                <th>Distribuição</th>
                <th>Solicitante</th>
                <th>Descrição</th>
            </tr></thead>
            <tbody>
            <?php
                if($consulta_chamados == false){
                    echo '<tr><td colspan="5" class="text-center">Nenhum chamado encontrado</td></tr>';
                }
                foreach($consulta_chamados as $item){
                    $linhaDistribuicao = $usuariodao->findById($item->getDistribuicao());

                    if($item->getUsado() == true){
                        if($item->getCancelado() == true)
                            echo '<tr style="background-color:#FFA07A;">';
                        else
                            echo '<tr style="background-color:#8FBC8F;">';
                    }else{
                        if($item->getPrioridade() == "Alta")
                            echo '<tr style="background-color:#ff5050;">';
                        else if($item->getPrioridade() == "Média")
                            echo '<tr style="background-color:#fff050;">';
                        else
                            echo '<tr style="background-color:#88ff50;">';
                    }

                    echo '<td class="text-center"><a href="index.php?pagina=exibirChamado&id='.$item->getId().'"><span class="glyphicon glyphicon-eye-open"></span></a></td>';
                    echo '<td>'.$item->getId().'</td>';
                    echo '<td>'.$item->getData().'</td>';
                    echo '<td>'.$item->getOrigem().'</td>';
                    echo '<td>'.$item->getLogradouro().'</td>';
                    echo '<td>'.$linhaDistribuicao->getNome().'</td>';
                    echo '<td>'.$item->getNomePessoa().'</td>';
                    echo '<td class="elimina-tabela">'.$item->getDescricao().'</td></tr>';
                }
            ?>
            <tbody>
        <table>
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
    },
        }
    } );
} );
</script>
