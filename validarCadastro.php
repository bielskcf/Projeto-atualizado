<?php

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verifica se todos os campos necessários foram enviados
    if(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['telefone']) && isset($_POST['genero']) && isset($_POST['data_nascimento'])) {

        // Dados de conexão com o banco de dados
        $servidor = "localhost";
        $usuario = "root";
        $senhaBD = "";
        $dbname = "bdprojeto";

        // Cria uma conexão com o banco de dados
        $conecta = new mysqli($servidor, $usuario, $senhaBD, $dbname);

        // Verifica se a conexão foi estabelecida com sucesso
        if($conecta->connect_error) {
            die('Erro de conexão: ' . $conecta->connect_error);
        }

        // Prepara a consulta SQL usando instrução preparada
        $sql = "INSERT INTO usuarios (username, email, senha, telefone, sexo, data_nascimento) VALUES (?, ?, ?, ?, ?, ?)";
        
        // Prepara a instrução SQL para execução
        $stmt = $conecta->prepare($sql);

        // Verifica se a preparação foi bem-sucedida
        if($stmt) {
            // Liga os parâmetros aos marcadores de posição na consulta SQL
            $stmt->bind_param("ssssss", $nome, $email, $telefone, $sexo, $data_nasc);

            // Obtém os valores dos campos do formulário
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senhaUsuario = $_POST['senha']; 
            $telefone = $_POST['telefone'];
            $sexo = $_POST['genero'];
            $data_nasc = $_POST['data_nascimento'];

        

            // Executa a instrução preparada para inserir os dados
            if($stmt->execute()) {
                echo "<script>alert('Usuário cadastrado com sucesso');</script>";
                header('Location: http://localhost/ProjetoSiteX/index.php');
                exit;
            } else {
                echo "<script>alert('Erro ao cadastrar usuário');</script>";
                echo "Erro: " . $stmt->error;
            }

            // Fecha a instrução preparada
            $stmt->close();
        } else {
            echo "<script>alert('Erro na preparação da consulta');</script>";
        }

        // Fecha a conexão com o banco de dados
        $conecta->close();    
    } else {
        echo "<script>alert('Todos os campos são obrigatórios');</script>";
    }
}

?>