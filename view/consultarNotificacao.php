<?php 
    require_once 'dao/NotificacaoDaoPgsql.php';
    require_once 'dao/EnderecoDaoPgsql.php';

    $enderecodao = New EnderecoDaoPgsql($pdo);
    $notificacaodao = New NotificacaoDaoPgsql($pdo);

    $listaNotificacao = $notificacaodao->buscarConsulta();

?>


<div class="box">
    <h3 class="text-center">Notificações</h3>
 <table id="myTable" class="display" style="width: 100%;" >
    <thead>
        <tr>
        <th></th>
        <th>ID</th>
        <th>Data de emissão</th>
        <th>Data de vencimento</th>
        <th>Notificado</th>
        <th>Endereço</th>
        </tr>
    </thead>
    <tbody>
    <?php 
        foreach($listaNotificacao as $item){
            $endereco = $enderecodao->buscarPeloId($item->getIdEndereco());
            echo '<tr>';
            echo '<td class="text-center"><a href="index.php?pagina=exibirNotificacao&id=' . $item->getId() .'"><span class="glyphicon glyphicon-eye-open"></span></a></td>';
            echo '<td> ' . $item->getId()  . ' </td>';
            echo '<td> ' .  date("d/m/Y", strtotime($item->getDataEmissao()))   . ' </td>';
           if($item->getDataVencimento())  echo '<td>' . date("d/m/Y", strtotime($item->getDataVencimento())). '</td>'; else echo '<td></td>';
            echo '<td> ' . $item->getNotificado()  . ' </td>';
            echo '<td> ' . $endereco->getLogradouro() . ', ' . $endereco->getNumero() . ', ' .  $endereco->getBairro() . ' </td>';
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
            "zeroRecords": "Nenhuma notificação encontrada",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhuma notificaçãoh registrada",
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