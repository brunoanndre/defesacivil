<?php
    include ('database.php');
    
    $pesquisa_ocorrencia = addslashes($_POST['pesquisa_ocorrencia']);
    $pesquisa_filtro = $_POST['filtro'];

    $items_por_pagina = 7;
    $pagina = intval($_GET['n']);
    $offset = $pagina * $items_por_pagina;
    $numero_total = 1;

    if(isset($_POST['pesquisa_ocorrencia']) && $pesquisa_ocorrencia != null){
        $query = "SELECT ocorrencia.id_ocorrencia,ocorrencia.ocorr_prioridade, 
        TO_CHAR(ocorrencia.data_ocorrencia, 'DD/MM/YYYY') as data_ocorrencia,
        usuario.nome, cobrade.subgrupo, ocorrencia.nome_pessoa1 
        FROM ocorrencia 
        INNER JOIN usuario ON ocorrencia.agente_principal = usuario.id_usuario
        INNER JOIN cobrade ON ocorrencia.ocorr_cobrade = cobrade.codigo";
        $sql = $pdo->prepare($query);
        
        if($pesquisa_filtro == 'data_ocorrencia'){
            $query = $query." WHERE TO_CHAR(ocorrencia.data_ocorrencia, 'DD/MM/YYYY') >= '$pesquisa_ocorrencia'";
            $sql = $pdo->prepare($query);
            $sql->bindValue(":pesquisa_ocorrencia", $pesquisa_ocorrencia);
        }else{
            $query = $query." WHERE $pesquisa_filtro ILIKE '$pesquisa_ocorrencia%' AND ocorrencia.ativo = TRUE";
            $sql = $pdo->prepare($query);
            $sql->bindValue(":pesquisa_filtro", $pesquisa_filtro);
            $sql->bindValue(":pesquisa_ocorrencia", $pesquisa_ocorrencia);
        }
        if($_POST['encerrada'] != true)
        $query = $query." AND ocorrencia.ocorr_encerrado = FALSE";
        $consulta_ocorrencias = $pdo->prepare($query);
        $consulta_ocorrencias->execute();

        $numero_total = $consulta_ocorrencias->rowCount();
    
        $consulta_ocorrencias = $pdo->prepare($query." ORDER BY
        CASE WHEN (ocorrencia.ocorr_prioridade = 'Alta') THEN 1 
        WHEN (ocorrencia.ocorr_prioridade = 'Média') THEN 2 
        WHEN (ocorrencia.ocorr_prioridade = 'Baixa') THEN 3 END 
        LIMIT :items_por_pagina OFFSET :offset");
        $consulta_ocorrencias->bindValue(":items_por_pagina", $items_por_pagina);
        $consulta_ocorrencias->bindValue(":offset", $offset);
        $consulta_ocorrencias->execute();

    }else{
        $query = "SELECT ocorrencia.id_ocorrencia,ocorrencia.ocorr_prioridade, TO_CHAR(ocorrencia.data_ocorrencia, 'DD/MM/YYYY') as data_ocorrencia,
        usuario.nome,cobrade.subgrupo, ocorrencia.nome_pessoa1 
        FROM ocorrencia 
        INNER JOIN usuario ON ocorrencia.agente_principal = usuario.id_usuario 
        INNER JOIN cobrade ON ocorrencia.ocorr_cobrade = cobrade.codigo 
        WHERE ocorrencia.ativo = TRUE";

        if($_POST['encerrada'] != true)
        $query .=" AND ocorrencia.ocorr_encerrado = FALSE";
        $sql = $pdo->prepare($query);
        $sql->execute();
        $numero_total = $sql->rowCount();

        $query = $query." ORDER BY 
        CASE WHEN (ocorrencia.ocorr_prioridade = 'Alta') THEN 1 
        WHEN (ocorrencia.ocorr_prioridade = 'Média') THEN 2 
        WHEN (ocorrencia.ocorr_prioridade = 'Baixa') THEN 3 END 
        LIMIT :items_por_pagina OFFSET :offset";


        $consulta_ocorrencias = $pdo->prepare($query);
        $consulta_ocorrencias->bindValue(":items_por_pagina", $items_por_pagina);
        $consulta_ocorrencias->bindValue(":offset", $offset);
        $consulta_ocorrencias->execute();
    }

    if($numero_total <= 0)
        $numero_total = 1;

    $numero_de_paginas = ceil($numero_total / $items_por_pagina);
?>
<div class="container positioning">
<div class="jumbotron campo_cadastro">
<h3 class="text-center">Consulta de ocorrências</h3>
    <div class="box">
        <form class="input-group" method="post" action="index.php?pagina=consultarOcorrencia&n=0">
            <input type="text" class="form-control" name="pesquisa_ocorrencia" placeholder="Pesquisa" value="<?php echo $_POST['pesquisa_ocorrencia']; ?>">
            <span>Filtrar por: </span>
            <select name="filtro" onchange="this.form.submit()" ng-model="sel_filtro" ng-init="sel_filtro='<?php if(isset($_POST['filtro'])){echo $_POST['filtro'];}else{echo 'cobrade.subgrupo';} ?>'">
                <option value="cobrade.subgrupo">Cobrade</option>
                <option value="nome_pessoa1">Solicitante</option>
                <option value="usuario.nome">Agente</option>
                <option value="data_ocorrencia">Data</option>
            </select>
            <span class="ocorrencias_encerradas">Mostrar ocorrências encerradas: </span>
            <input name="encerrada" onchange="this.form.submit()" value="true" type="checkbox" <?php if($_POST['encerrada']==true)echo 'checked'; ?>>
        </form>
    </div>
    <div class="box">
        <table id="tabela" class="table table-striped table-bordered" style="width:100%">
            <thead><tr>
                <th><!--<span class="glyphicon glyphicon-fullscreen"></span>--></th>
                <th onclick="sortTable(0)">ID<span class="glyphicon glyphicon-sort sort-icon"></span></th>
                <th onclick="sortTable(1)">Cobrade<span class="glyphicon glyphicon-sort sort-icon"></span></th>
                <th onclick="sortTable(2)">Solicitante<span class="glyphicon glyphicon-sort sort-icon"></span></th>
                <th onclick="sortTable(3)" class="elimina-tabela">Agente<span class="glyphicon glyphicon-sort sort-icon elimina-tabela"></span></th>
                <th onclick="sortTable(4)">Data<span class="glyphicon glyphicon-sort sort-icon"></span></th>
            </tr></thead>
            <tbody>
            <?php
                $i = 0;
                if($consulta_ocorrencias->rowCount() == 0)
                    echo '<tr><td colspan="5" class="text-center">Nenhuma ocorrência encontrada.</td></tr>';
                    $linha = $consulta_ocorrencias->fetchAll();
                    foreach($linha as $item){
                    echo '<tr style="background-color:';
                    if($linha[$i]['ocorr_prioridade'] == "Alta")
                        echo '#ff5050;">';
                    else if($linha[$i]['ocorr_prioridade'] == "Média")
                        echo '#fff050;">';
                    else
                        echo '#88ff50;">';
                    echo '<td class="text-center"><a href="index.php?pagina=exibirOcorrencia&id='.$linha[$i]['id_ocorrencia'].'"><span class="glyphicon glyphicon-eye-open"></span></a></td>';
                    echo '<td>'.$linha[$i]['id_ocorrencia'].'</td>';
                    echo '<td>'.$linha[$i]['subgrupo'].'</td>';
                    echo '<td>'.$linha['nome_pessoa1'].'</td>';
                    echo '<td class="elimina-tabela">'.$linha[$i]['nome'].'</td>';
                    echo '<td>'.$linha[$i]['data_ocorrencia'].'</td></tr>';
                    $i++;
                }
            ?>
            <tbody>
        <table>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li>
                <a href="index.php?pagina=consultarOcorrencia&n=0">
                    <span>Inicio</span>
                </a>
                </li>
                <?php for($i=0; $i<$numero_de_paginas;$i++){ 
                    $estilo = "";
                    if($pagina == $i)
                        $estilo = 'class="active"';
                ?>
                <li <?php echo $estilo; ?> ><a href="index.php?pagina=consultarOcorrencia&n=<?php echo $i; ?>"><?php echo $i+1; ?></a></li>
                <li>
                <?php } ?>
                <a href="index.php?pagina=consultarOcorrencia&n=<?php echo $numero_de_paginas-1 ?>">
                    <span>Fim</span>
                </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
</div>  