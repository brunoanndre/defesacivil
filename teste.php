<?php 

    include 'database.php';

  $sql = $pdo->prepare("ALTER TABLE interdicao ALTER COLUMN danos_aparentes TYPE varchar");

  try{
    if($sql->execute()){
      $lista = $sql->fetchAll(PDO::FETCH_ASSOC);
ECHO 'DEU';
echo '<pre>';
var_dump($lista);

    }else{
      echo ' n deu';
    }
  }catch(PDOException $e){
    echo $e->getMessage();
  }



  ?>