<table id="myTable" class="row-border" style="width:100%">
    <thead><tr>
        <th></th>
        <th>ID</th>
        <th style="width: 100px;">Data</th>
        <th>Dias em aberto</th>
        <th>Origem</th>
        <th>Solicitante</th>
        <th>Agente</th>
        <th>Distribuição</th>
        <th>Endereço</th>
        <th>Descrição</th>
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