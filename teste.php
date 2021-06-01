<?php 

    include 'database.php';

  $sql = $pdo->prepare('ALTER TABLE notificacao ADD COLUMN ativo boolean');
  
  if($sql->execute()){
    echo 'deu';
  }else{
    echo 'n deu';
  }
  ?>