<?php

require_once 'database.php';
require_once 'dao/IntedicaoDaoPgsql.php';

$interdicaodao = new IntedicaoDaoPgsql($pdo);

$linha = $interdicaodao->buscarTodas();
foreach($linha as $item){
    echo "<pre>";
    var_dump($linha);

}
die;
?>
<div class="box">
 <table id="myTable" class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Motivo</th>
            <th>Descrição</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>teste</td>
            <td> teste</td>
            <td>teste</td>
            <td>teste</td>
        </tr>
        <tr>
            <td>t</td>
            <td>t</td>
            <td>t</td>
            <td></td>
        </tr>
    </tbody>
</table>
</div>
<script>
$(document).ready( function () {
    $('#myTable').DataTable();
} );
</script>