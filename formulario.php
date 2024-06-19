<?php
    session_start();
    
    // Inclui o arquivo de configuração do banco de dados
    include_once('config.php');

    // Verifica se o formulário foi enviado
    if(isset($_POST['submit'])) {
        // Obtém os dados do formulário
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $telefone = $_POST['telefone'];
        $sexo = $_POST['genero'];
        $data_nascimento = $_POST['data_nascimento'];

        // Prepara a consulta SQL para inserir os dados no banco de dados
        $sql = "INSERT INTO usuarios (nome, senha, email, telefone, sexo, data_nascimento) 
                VALUES ('$nome', '$senha', '$email', '$telefone', '$sexo', '$data_nascimento')";

        // Executa a consulta
        if(mysqli_query($conexao, $sql)) {
            // Redireciona para a página de login após o cadastro bem-sucedido
            header('Location: login.php');
            exit; // Termina a execução do script após o redirecionamento
        } else {
            // Se houver algum erro na execução da consulta, exibe uma mensagem de erro
            echo "Erro ao cadastrar: " . mysqli_error($conexao);
        }
    }
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="formulario.css">
</head>
<body>
    <div class="box">
        <form action="formulario.php" method="POST">
            <label for="nome">Nome Completo</label>
            <input type="text" name="nome" id="nome" required>
            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" required>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <label for="telefone">Telefone</label>
            <input type="tel" name="telefone" id="telefone" required>
            <label for="genero">Gênero</label>
            <select name="genero" id="genero" required>
                <option value="feminino">Feminino</option>
                <option value="masculino">Masculino</option>
            </select>
            <label for="data_nascimento">Data de Nascimento</label>
            <input type="date" name="data_nascimento" id="data_nascimento" required>
            <input type="submit" name="submit" value="Cadastrar">
        </form>
    </div>
</body>
</html>
