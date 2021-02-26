<?php
//inclui a conexao com o banco de dados
include ('database.php');

//recebe email e senha do usuario
$email = addslashes($_POST['email']);
$senha = $_POST['senha'];

//seleciona a senha q existe no banco de dados
$sql = $pdo->prepare("SELECT * FROM dados_login  WHERE email = :email AND ativo = TRUE");
$sql->bindValue(":email", $email);
$sql->execute();

if($sql->execute() == false){ //caso ocorra algum erro na conexao
    //echo pg_last_error();
    header('location:index.php?erroBD');
}else{
    if($sql->rowCount() == 1){ //caso só exista um registro com o email dado
        $linha = $sql->fetch();
        $hash = $linha['senha']; //pega a senha do banco de dados
        if(crypt($senha, $hash) === $hash){ //compara com a criptografia
            //inicia uma sessao, e salva o id do usuario logado
            session_start();
            $id = $linha['id_usuario'];
            $_SESSION['id_usuario'] = $id;

            //pega o nivel de acesso do usuario fazendo login
            $sql = $pdo->prepare("SELECT nivel_acesso FROM usuario WHERE id_usuario = :id");
            $sql->bindValue(":id", $id);
            $sql->execute();
            $linha = $sql->fetch();
            $_SESSION['nivel_acesso'] = $linha['nivel_acesso'];
            $_SESSION['login'] = true;


            //salva o login na tabela de log
            $data = date('Y-m-d H:i:s');
            $sql = $pdo->prepare("INSERT INTO log_login (id_usuario, data_hora) VALUES (:id,:datahora)");
            $sql->bindValue(":id", $id);
            $sql->bindValue(":datahora", $data);
            $sql->execute();

            //envia para a pagina principal
            //echo pg_last_error();
            if($_SESSION['nivel_acesso'] == 4){
                header('location:index.php?pagina=monitorarChamado');
            }else{
                header('Location: index.php');
            }
            
        }else{
            //retorna o erro
            header('view/login.php');
        }
    }else{
        //retorna o erro;
        header('location:index.php?erro');
    }
}
