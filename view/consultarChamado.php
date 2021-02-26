<?php
    include 'database.php';
    
    $pesquisa_chamado = addslashes($_POST['pesquisa_chamado']);
    $pesquisa_filtro = $_POST['filtro'];

    $items_por_pagina = 7;
    $pagina = intval($_GET['n']);
    $offset = $pagina * $items_por_pagina;
    $numero_total = 1;

    if(isset($_POST['pesquisa_chamado']) && $pesquisa_chamado != null){
        $query = "SELECT chamado.id_chamado,TO_CHAR(chamado.data_hora, 'DD/MM/YYYY') as dataa,
                        chamado.origem,chamado.descricao, chamado.prioridade, chamado.nome_pessoa, 
                        chamado.usado, chamado.distribuicao, usuario.nome as usuario
                        FROM chamado 
                        INNER JOIN usuario ON (chamado.agente_id = usuario.id_usuario)";
        
        if($pesquisa_filtro == 'data')
            $query = $query." WHERE TO_CHAR(data_hora, 'DD/MM/YYYY') >= '$pesquisa_chamado'";
        else
            $query = $query." WHERE $pesquisa_filtro ILIKE '$pesquisa_chamado%'";

        if($_POST['finalizado'] != true)
            $query = $query." AND chamado.usado = false";

        $sql = $pdo->prepare($query);
        $sql->execute();

        $numero_total = $sql->rowCount();
        $consulta_chamados = $pdo->prepare($query . "LIMIT $items_por_pagina OFFSET $offset");
        $consulta_chamados->execute();
    }else{
        $query = "SELECT chamado.id_chamado,TO_CHAR(chamado.data_hora, 'DD/MM/YYYY') as dataa,
        chamado.origem,chamado.descricao, chamado.prioridade, chamado.nome_pessoa, 
        chamado.usado, chamado.cancelado, usuario.nome as usuario, chamado.distribuicao
        FROM chamado 
        INNER JOIN usuario ON (chamado.agente_id = usuario.id_usuario)";
        
        if($_POST['finalizado'] == true){
            if($_POST['cancelado'] == true)
                $query = $query." WHERE chamado.usado = true";
            else
                $query = $query." WHERE chamado.usado = true AND chamado.cancelado = false";
        }else{
            if($_POST['cancelado'] == true)
                $query = $query." WHERE chamado.usado = true AND chamado.cancelado = true";
            else
                $query = $query." WHERE chamado.usado = false";
        }
            
        $consulta_chamados = $pdo->prepare($query);
        $consulta_chamados->execute();

        $numero_total = $consulta_chamados->rowCount();

        $query = $query." ORDER BY chamado.data_hora DESC
        LIMIT $items_por_pagina OFFSET $offset";

        $consulta_chamados = $pdo->prepare($query);
        $consulta_chamados->execute();
    }

    if($numero_total <= 0)
        $numero_total = 1;

    $numero_de_paginas = ceil($numero_total / $items_por_pagina);
?>

<div class="container positioning">

<div class="jumbotron campo_cadastro">
<?php if(isset($_GET['sucesso'])) { ?>
        <div class="alert alert-success" role="alert">
            Chamado cancelado com sucesso.
        </div>
    <?php } ?>
<h3 class="text-center">Consulta de chamados</h3>
    <div class="box">
        <form class="input-group" method="post" action="index.php?pagina=consultarChamado&n=0">
            <input type="text" class="form-control" name="pesquisa_chamado" placeholder="Pesquisa" value="<?php echo $_POST['pesquisa_chamado']; ?>">
            <span>Filtrar por: </span>
            <select name="filtro" onchange="this.form.submit()" ng-model="sel_filtro" ng-init="sel_filtro='<?php if(isset($_POST['filtro'])){echo $_POST['filtro'];}else{echo 'data';} ?>'">
                <option value="data">Data</option>
                <option value="chamado.origem">Origem</option>
                <option value="usuario.nome">Agente</option>
                <option value="chamado.distribuicao">Distribuição</option>
                <option value="chamado.nome_pessoa">Solicitante</option>
                <option value="chamado.descricao">Descrição</option>
            </select>
            <span class="ocorrencias_encerradas">Encerrados: </span>
            <input name="finalizado" onchange="this.form.submit()" value="true" type="checkbox" <?php if($_POST['finalizado']==true)echo 'checked'; ?>>
            <span class="ocorrencias_encerradas">Cancelados: </span>
            <input name="cancelado" onchange="this.form.submit()" value="true" type="checkbox" <?php if($_POST['cancelado']==true)echo 'checked'; ?>>
        </form>
    </div>
    <div class="box">
        <table id="tabela" class="table table-striped table-bordered" style="width:100%">
            <thead><tr>
                <th><!--<span class="glyphicon glyphicon-fullscreen"></span>--></th>
                <th onclick="sortTable(0)">ID<span class="glyphicon glyphicon-sort sort-icon"></span></th>
                <th onclick="sortTable(1)">Data<span class="glyphicon glyphicon-sort sort-icon"></span></th>
                <th onclick="sortTable(2)">Origem<span class="glyphicon glyphicon-sort sort-icon"></span></th>
                <th onclick="sortTable(3)">Agente<span class="glyphicon glyphicon-sort sort-icon"></span></th>
                <th onclick="sortTable(4)">Distribuição<span class="glyphicon glyphicon-sort sort-icon"></span></th>
                <th onclick="sortTable(5)">Solicitante<span class="glyphicon glyphicon-sort sort-icon"></span></th>
                <th onclick="sortTable(6)" class="elimina-tabela">Descrição<span class="glyphicon glyphicon-sort sort-icon elimina-tabela"></span></th>
            </tr></thead>
            <tbody>
            <?php
                $i = 0;
                $linha = $consulta_chamados->fetchAll();
                if($consulta_chamados->rowCount() == 0)
                    echo '<tr><td colspan="5" class="text-center">Nenhum chamado encontrado</td></tr>';
                foreach($linha as $item){
                    $id_agente = $item['distribuicao'];
                    $sql = $pdo->prepare("SELECT nome FROM usuario WHERE id_usuario = :id_agente");
                    $sql->bindValue(":id_agente", $id_agente);
                    $sql->execute();

                    $linhaDistribuicao = $sql->fetch();

                    echo '<tr style="background-color:';
                    if($item['usado'] == "t"){
                        if($item['cancelado'] == "t")
                            echo '#FFA07A;">';
                        else
                            echo '#8FBC8F;">';
                    }else{
                        if($item['prioridade'] == "Alta")
                            echo '#ff5050;">';
                        else if($item['prioridade'] == "Média")
                            echo '#fff050;">';
                        else
                            echo '#88ff50;">';
                    }
                    echo '<td class="text-center"><a href="index.php?pagina=exibirChamado&id='.$item['id_chamado'].'"><span class="glyphicon glyphicon-eye-open"></span></a></td>';
                    echo '<td>'.$item['id_chamado'].'</td>';
                    echo '<td>'.$item['dataa'].'</td>';
                    echo '<td>'.$item['origem'].'</td>';
                    echo '<td>'.$item['usuario'].'</td>';
                    echo '<td>'.$linhaDistribuicao['nome'].'</td>';
                    echo '<td>'.$item['nome_pessoa'].'</td>';
                    echo '<td class="elimina-tabela">'.$item['descricao'].'</td></tr>';
                    $i += 1;
                }
            ?>
            <tbody>
        <table>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li>
                <a href="index.php?pagina=consultarChamado&n=0">
                    <span>Inicio</span>
                </a>
                </li>
                <?php for($i=0; $i<$numero_de_paginas;$i++){ 
                    $estilo = "";
                    if($pagina == $i)
                        $estilo = 'class="active"';
                ?>
                <li <?php echo $estilo; ?> ><a href="index.php?pagina=consultarChamado&n=<?php echo $i; ?>"><?php echo $i+1; ?></a></li>
                <li>
                <?php } ?>
                <a href="index.php?pagina=consultarChamado&n=<?php echo $numero_de_paginas-1 ?>">
                    <span>Fim</span>
                </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
</div>  