<?php  
require('database.php');
require_once 'dao/UsuarioDaoPgsql.php';

$usuarioDAO = new UsuarioDaoPgsql($pdo);

session_start();

if($_SESSION['nivel_acesso'] == 1){
    $nome = filter_input(INPUT_POST, "nome");
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $telefone = filter_input(INPUT_POST, "telefone");
    $cpf = filter_input(INPUT_POST,"cpf");
    $acesso = $_SESSION['nivel_acesso'];
    $id = $_SESSION['id_usuario'];
    $erros = '';
    $foto = $_FILES["foto"]["tmp_name"];
}else{
    $id = $_SESSION['id_usuario'];
    $telefone = filter_input(INPUT_POST, "telefone");
    $foto = $_FILES["foto"]["tmp_name"];
}


if($foto){
$binary = file_get_contents($foto);
$base64 = base64_encode($binary);
}

if($email){
    $usuario = $usuarioDAO->findByEmail($email);

    if($usuario == true){

        $usuarioEmail = $usuarioDAO->findById($id);
        $usuarioEmail = $usuarioEmail->getEmail();
        if($usuarioEmail === $email){
            $erros .= '';
        }else{
            $erros .= "&emailexistente";
        }
    }
}

if($cpf){
    function validaCPF($cpf) { 
        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
        
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    validaCPF($cpf);
    if(!validaCPF($cpf)){
        $erros .= "&CPFInvalido";
    }
}


if(strlen($erros) > 0){
header('Location: index.php?pagina=EditarPerfil'.$erros."&id=".$_SESSION['id_usuario']);
} else{
    //ATUALIZAR O EMAIL NA TABELA DADOS_LOGIN

    if($_SESSION['nivel_acesso'] == 1){
        $usuario = $usuarioDAO->findById($id);
        $usuario->setEmail($email);
        $usuarioDAO->updateEmail($email,$id);

    }

    if($foto !== ""){
        if($_SESSION['nivel_acesso'] == 1){

            $usuarioEditado = new Usuario();
            $usuarioEditado->setId($id);
            $usuarioEditado->setNome($nome);
            $usuarioEditado->setCPF($cpf);
            $usuarioEditado->setTelefone($telefone);
            $usuarioEditado->setFoto($base64);
            $usuarioEditado->setAcesso($acesso);

            if($usuarioDAO->updateComFoto($usuarioEditado)){
                header("Location: index.php?pagina=perfil&sucesso");
            }
        }else{
            $usuarioEditado = new Usuario();
            $usuarioEditado->setId($id);
            $usuarioEditado->setTelefone($telefone);
            $usuarioEditado->setFoto($base64);

            if($usuarioDAO->updateTelefoneFoto($usuarioEditado)){
                header("Location: index.php?pagina=perfil&sucesso");
            }
        }

    }else{
        if($_SESSION['nivel_acesso'] == 1){
        $usuarioEditado = new Usuario();
        $usuarioEditado->setId($id);
        $usuarioEditado->setNome($nome);
        $usuarioEditado->setCPF($cpf);
        $usuarioEditado->setTelefone($telefone);
        $usuarioEditado->setAcesso($acesso);

        $usuarioDAO->updateSemFoto($usuarioEditado);
        header("Location: index.php?pagina=perfil&sucesso");
        }else{
            $usuarioEditado = new Usuario();
            $usuarioEditado->setId($id);
            $usuarioEditado->setTelefone($telefone);

            if($usuarioDAO->updateTelefone($usuarioEditado)){
                header("Location: index.php?pagina=perfil&sucesso");
            }
        }
    }
}




