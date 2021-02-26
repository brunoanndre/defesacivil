<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <form method="POST" action="processaLoginTemporario.php"> 
        <label>Nome:</label>
        <input type="text" name="nome" id="nome"><br>
        <label>Telefone:</label>
        <input type="number" name="telefone" id="telefone"><br>
        <label>CPF:</label>
        <input type="number" name="cpf" id="cpf"><br>
        <label>Acesso</label>
        <input type="number" name="acesso" id="acesso"><br>
        <label>E-mail:</label>
        <input type="email" name="email" id="email"><br>
        <label>Senha:</label>
        <input type="password" name="senha" id="senha"><br>

        <button type="submit">Cadastrar</button>
   </form>
</body>
</html>