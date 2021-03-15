<?php

require_once 'database.php';
require_once 'dao/IntedicaoDaoPgsql.php';

$interdicaodao = new IntedicaoDaoPgsql($pdo);

$linha = $interdicaodao->buscarTodas();

?>
<div class="box">
 <table id="myTable" class="display" >
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
    <?php //TERMINAR DE ARRUMAR A CONSULTA DE INTERDIÇÕES
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
$(document).ready( function () {
    $('#myTable').DataTable()({
        "language": {
            "lengthMenu": "Display _MENU_ Interdições por página",
            "zeroRecords": "Nenhuma interdição encontrada",
            "info": "Mostrando página _PAGE_ of _PAGES_",
            "infoEmpty": "Nenhuma interdição ativa",
            "infoFiltered": "(filtered from _MAX_ interdições totais)"
        }
    });
} );
</script>