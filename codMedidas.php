<?php
session_start();
include_once('config.php');

// Verificar se o usuário está logado
if(!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
    header('Location: login.php');
    exit();
}

// Obter o ID do usuário
$logado = $_SESSION['email'];
$query_id = "SELECT id FROM usuarios WHERE email = '$logado'";
$result_id = $conexao->query($query_id);

if ($result_id && $result_id->num_rows > 0) {
    $row_id = $result_id->fetch_assoc();
    $usuario_id = $row_id['id'];
} else {
    // Se o ID do usuário não for encontrado, redirecione para uma página de erro ou faça algo apropriado
    header('Location: erro.php');
    exit();
}

// Processamento do formulário de medidas do corpo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se todos os campos do formulário foram enviados
    if(isset($_POST['altura']) && isset($_POST['peso']) && isset($_POST['cintura']) && isset($_POST['quadril'])) {
        // Obter os dados do formulário
        $altura = $conexao->real_escape_string($_POST['altura']);
        $peso = $conexao->real_escape_string($_POST['peso']);
        $cintura = $conexao->real_escape_string($_POST['cintura']);
        $quadril = $conexao->real_escape_string($_POST['quadril']);

        // Inserir as medidas do corpo no banco de dados
        $query_insert = "INSERT INTO medidas_corpo (usuario_id, altura, peso, cintura, quadril, data_medida) VALUES ('$usuario_id', '$altura', '$peso', '$cintura', '$quadril', NOW())";
        if ($conexao->query($query_insert) === TRUE) {
            $_SESSION['resultado'] = "Medidas do corpo registradas com sucesso!";
        } else {
            $_SESSION['resultado'] = "Erro ao registrar as medidas do corpo: " . $conexao->error;
        }
    } else {
        // Se algum campo estiver faltando, exibir uma mensagem de erro ou fazer algo apropriado
        $_SESSION['resultado'] = "Todos os campos do formulário são obrigatórios.";
    }
}

// Redirecionar de volta para sistema.php após o processamento do formulário
header('Location: sistema.php');
exit();
?>
