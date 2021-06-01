<?php

require_once 'database.php';
require_once 'dao/IntedicaoDaoPgsql.php';

$interdicaodao = new IntedicaoDaoPgsql($pdo);

$linha = $interdicaodao->buscarTodas();

?>
<div class="box">
    <h3 class="text-center">Interdições</h3>
 <table id="myTable" class="display" style="width: 100%;" >
    <thead>
        <tr>
        <th></th>
        <th>ID</th>
        <th>Tipo</th>
        <th>Motivo</th>
        <th>Descrição</th>
        </tr>
    </thead>
    <tbody>
    <?php 
        foreach($linha as $item){
            echo '<tr>';
            echo '<td class="text-center"><a href="index.php?pagina=exibirInterdicao&id=' . $item->getId() .'"><span class="glyphicon glyphicon-eye-open"></span></a></td>';
            echo '<td> ' . $item->getId()  . ' </td>';
            echo '<td> ' . $item->getTipo()  . ' </td>';
            echo '<td> ' . $item->getMotivo()  . ' </td>';
            echo '<td> ' . $item->getDescricao()  . ' </td>';
            echo'</tr>';
        }
        
    ?>
    </tbody>
</table>
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