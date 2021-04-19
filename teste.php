<?php 

    include 'database.php';

    try{
        $sql = $pdo->prepare("alter table chamado add constraint fk_chamado_coordenada foreign key (id_coordenada) references endereco_coordenada(id_coordenada)");
      $sql->execute();

      $sql = $pdo->prepare("select * from chamado");
      $sql->execute();

      $lista = $sql->fetch(PDO::FETCH_ASSOC);
      echo '<pre>';
      var_dump($lista);
      die;
    }catch(PDOException $e){
      echo $e;
    }

  ?>