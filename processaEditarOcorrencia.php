<?php
include ('database.php');


$result = pg_query($connection,"SELECT * FROM usuario WHERE nome = 'Yuri Tabaczenski Silva Da Silva'");

var_dump(pg_num_rows($result));
if(pg_num_rows($result) > 0){
    $row = pg_fetch_assoc($result);
    echo "<pre>";
    var_dump($row);
}