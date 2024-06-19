<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
        <h1>Login</h1>
     </header>
        <section>
           <form action="testLogin.php" method="post">
           <label for="email">Email</label>
           <input type="text" name="email" id="email">
           <label for="senha">Senha</label>
           <input type="password" name="senha" id="senha" maxlength="10">
           <input class="inputSubmit" type="submit" name="submit" value="Enviar">
        </form>
        </section>
        <div class="box">
        <a href="formulario.php">Cadastre-se</a>
        </div>
   
</body>
</html>