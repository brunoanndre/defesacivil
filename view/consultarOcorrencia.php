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

<?php
    include ('database.php');
    include 'dao/OcorrenciaDaoPgsql.php';

    $ocorrenciadao = new OcorrenciaDaoPgsql($pdo);
    
    $pesquisa_ocorrencia = addslashes($_POST['pesquisa_ocorrencia']);

    if(isset($_POST['pesquisa_ocorrencia']) && $pesquisa_ocorrencia != null){
        $parametro = 'normal';
        
        if($_POST['encerrada'] != true){
            $parametro = 'encerrada_false';
        }
        $consulta_ocorrencias =$ocorrenciadao->buscarConsulta($parametro);
    }else{
        $parametro = 'ativo_true';

        if($_POST['congelada'] == true){
            $parametro = 'ativo_congelada_true';
        }else{
            if($_POST['encerrada'] != true){
                $parametro = 'ativo_encerrada_false';
            }else{
                $parametro = 'ativo_encerrada_true';
            }
        }
    

        $consulta_ocorrencias= $ocorrenciadao->buscarConsulta($parametro);
    }   
?>

<div>
<h3 class="text-center">Consulta de ocorrências</h3>
    <div class="box">
        <form class="input-group" method="post" action="index.php?pagina=consultarOcorrencia&n=0">
            <span class="ocorrencias_encerradas">Mostrar ocorrências encerradas: </span>
            <input name="encerrada" onchange="this.form.submit()" value="true" type="checkbox" <?php if($_POST['encerrada']==true)echo 'checked'; ?>><br>
            <span class="ocorrencias_encerradas"> Mostrar ocorrências congeladas: </span>
            <input name="congelada" onchange="this.form.submit()" value="true" type="checkbox" <?php if($_POST['congelada'] == true)echo 'checked'; ?>>
        </form>
    </div>
    <div class="box">
        <table id="myTable" class="row-border" style="width:100%">
            <thead><tr>
                <th><!--<span class="glyphicon glyphicon-fullscreen"></span>--></th>
                <th>ID</th>
                <th>Cobrade</th>
                <th>Descrição</th>
                <th>Solicitante</th>
                <th>Agente</th>
                <th>Data</th>
            </tr></thead>
            <tbody>
            <?php
                if($consulta_ocorrencias == false){
                    echo '<tr><td colspan="5" class="text-center">Nenhuma ocorrência encontrada.</td></tr>';
                }
                    foreach($consulta_ocorrencias as $item){
                    if($item->getPrioridade() == "Alta")
                        echo '<tr style="background-color:#ff5050;">';
                    else if($item->getPrioridade() == "Média")
                        echo '<tr style="background-color: #fff050;">';
                    else
                        echo '<tr style="background-color: #88ff50;">';
                    echo '<td class="text-center"><a href="index.php?pagina=exibirOcorrencia&id='.$item->getId().'"><span class="glyphicon glyphicon-eye-open"></span></a></td>';
                    echo '<td>'.$item->getId().'</td>';
                    echo '<td>'.$item->getCobrade().'</td>';
                    echo '<td>' .$item->getDescricao(). '</td>';
                    echo '<td>'.$item->getPessoa1().'</td>';
                    echo '<td class="elimina-tabela">'.$item->getNomeAgentePrincipal().'</td>';
                    echo '<td>'.$item->getData().'</td></tr>';
                }
            ?>
            <tbody>
        <table>
    </div>
</div>
