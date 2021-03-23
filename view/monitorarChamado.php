<table id="myTable" class="row-border" style="width:100%">
    <thead><tr>
        <th>ID</th>
        <th>Data</th>
        <th>Dias em aberto</th>
        <th>Origem</th>
        <th>Solicitante</th>
        <th>Agente</th>
        <th>Responsável</th>
        <th>Endereço</th>
        <th>Descricao</th>
    </tr></thead>
    <tbody id="requestChamado">
    <?php include 'requestChamado.php' ?>
    <tbody>
<table>


<script>
$(document).ready(function() {
    $('#myTable').DataTable( {
        "language": {
            "lengthMenu": "Exibir _MENU_ Registros por página",
            "zeroRecords": "Nenhum chamado encontrada",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum chamado registrada",
            "infoFiltered": "(filtered from _MAX_ total records)"
        }
    } );
} );
</script>