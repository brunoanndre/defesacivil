<?php
    include 'database.php';
    require_once 'dao/UsuarioDaoPgsql.php';

    $usuariodao = new UsuarioDaoPgsql($pdo);

    $pesquisa_usuario = '';
    if(isset($_POST['pesquisa_usuario']))
        $pesquisa_usuario = addslashes($_POST['pesquisa_usuario']);

    $items_por_pagina = 10;
    $pagina = intval($_GET['n']);
    $offset = $pagina * $items_por_pagina;
    $numero_total = 1;

    $numero_total =   $usuariodao->consultaUsuarioNumeroPaginas($pesquisa_usuario);
    
    if($numero_total <= 0)
        $numero_total = 1;
    $numero_de_paginas = ceil($numero_total / $items_por_pagina);

    $lista = $usuariodao->findAll($pesquisa_usuario,$items_por_pagina,$offset);
?>
<div class="container positioning">
    <div class="jumbotron campo_cadastro">
    <?php if(isset($_GET['sucesso'])) { ?>
        <div class="alert alert-success" role="alert">
            Usuario excluído com sucesso.
        </div>
    <?php } ?>
        <div class="box">
            <form class="input-group" method="post" action="index.php?pagina=consultarUsuario&n=0">
                <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>   
                <input type="text" class="form-control" name="pesquisa_usuario" placeholder="Pesquisar usuário" value="<?php echo $_POST['pesquisa_usuario']; ?>">
            </form>
        </div>
        <div class="box">
            <table id="tabela" class="table table-striped table-bordered" style="width:100%">
            <thead><tr>
                <th></th>
                <th onclick="sortTable(0)" class="elimina-tabela">ID<span class="glyphicon glyphicon-sort sort-icon elimina-tabela"></th>
                <th onclick="sortTable(1)">Nome<span class="glyphicon glyphicon-sort sort-icon"></th>
                <th onclick="sortTable(2)">Email<span class="glyphicon glyphicon-sort sort-icon"></th>
                <th onclick="sortTable(3)" class="elimina-tabela">Telefone<span class="glyphicon glyphicon-sort sort-icon elimina-tabela"></th>
            </tr></thead>
            <tbody>
            <?php
                session_start();
                if(!$lista){
                    echo '<tr><td colspan="5" class="text-center">Nenhum usuário encontrado</td></tr>';
                }
                foreach($lista as $usuario){
                    if(strcmp($usuario->getId(),$_SESSION['id_usuario']) != 0){
                        echo '<tr><td class="text-center"><a href="index.php?pagina=exibirUsuario&id='.$usuario->getId().'"><span class="glyphicon glyphicon-eye-open"></span></a></td>';
                        echo '<td class="elimina-tabela">'.$usuario->getId().'</td>';
                        echo '<td>'.$usuario->getNome().'</td>'; 
                        echo '<td>'.$usuario->getEmail().'</td>';
                        echo '<td class="elimina-tabela">'.$usuario->getEmail().'</td></tr>';
                    }
                }
            ?>
            <tbody>
        <table>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li>
                <a href="index.php?pagina=consultarUsuario&n=0">
                    <span>Inicio</span>
                </a>
                </li>
                <?php for($i=0; $i<$numero_de_paginas;$i++){ 
                    $estilo = "";
                    if($pagina == $i)
                        $estilo = 'class="active"';
                ?>
                <li <?php echo $estilo; ?> ><a href="index.php?pagina=consultarUsuario&n=<?php echo $i; ?>"><?php echo $i+1; ?></a></li>
                <li>
                <?php } ?>
                <a href="index.php?pagina=consultarUsuario&n=<?php echo $numero_de_paginas-1 ?>">
                    <span>Fim</span>
                </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
</div>