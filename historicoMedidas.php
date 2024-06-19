<?php
    session_start();
    include_once('config.php');
    // print_r($_SESSION);
    if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true))
    {
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: login.php');
    }
    $logado = $_SESSION['email'];
    if(!empty($_GET['search']))
    {
        $data = $_GET['search'];
        $sql = "SELECT * FROM usuarios WHERE id LIKE '%$data%' or nome LIKE '%$data%' or email LIKE '%$data%' ORDER BY id DESC";
    }
    else
    {
        $sql = "SELECT * FROM usuarios ORDER BY id DESC";
    }
    $result = $conexao->query($sql);

    // Consulta para obter o nome e o ID do usuário logado
    $query_nome = "SELECT id, nome FROM usuarios WHERE email = '$logado'";
    $result_nome = $conexao->query($query_nome);

    if ($result_nome && $result_nome->num_rows > 0) {
        $row_nome = $result_nome->fetch_assoc();
        $nome_usuario = $row_nome['nome'];
        $usuario_id = $row_nome['id']; // Armazenar o ID do usuário
    } else {
        echo "<p>Erro: O ID do usuário não foi encontrado.</p>";
        // Encerre o script ou redirecione o usuário para lidar com o erro, se necessário
        exit();
    }

    // Exibir as últimas medidas do corpo
    if(isset($usuario_id)) {
        $query_ultimas_medidas = "SELECT altura, peso, cintura, quadril, data_medida FROM medidas_corpo WHERE usuario_id = '$usuario_id' ORDER BY data_medida DESC LIMIT 1";
        $result_ultimas_medidas = $conexao->query($query_ultimas_medidas);
        if ($result_ultimas_medidas && $result_ultimas_medidas->num_rows > 0) {
            $row_ultimas_medidas = $result_ultimas_medidas->fetch_assoc();
            // Exibir os resultados
          //  echo "<p>Altura: " . $row_ultimas_medidas['altura'] . " cm</p>";
          //  echo "<p>Peso: " . $row_ultimas_medidas['peso'] . " kg</p>";
          //  echo "<p>Cintura: " . $row_ultimas_medidas['cintura'] . " cm</p>";
          //  echo "<p>Quadril: " . $row_ultimas_medidas['quadril'] . " cm</p>";
           // echo "<p>Data: " . $row_ultimas_medidas['data_medida'] . "</p>";
        } else {
            echo "<p>Nenhuma medida registrada ainda.</p>";
        }
    } else {
        echo "<p>Erro: O ID do usuário não foi encontrado.</p>";
    }
    $query_medidas = "SELECT altura, peso, cintura, quadril, DATE_FORMAT(data_medida, '%d-%m-%Y') AS data_medida_formatada FROM medidas_corpo WHERE usuario_id = '$usuario_id' ORDER BY data_medida DESC";
    $result_medidas = $conexao->query($query_medidas);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Medidas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding-top: 20px;
        }
        .jumbotron {
            background-color: #fff;
            padding: 2rem 1rem;
            margin-bottom: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
        }
        h1, h2, h3 {
            color: #007bff;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn {
            background-color: #007bff;
            color: #fff;
            transition: background-color 0.3s ease;
        }
        .resultado {
            font-weight: bold;
            margin-top: 20px;
        }
        .table th {
            color: #007bff;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand {
            color: #110202;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4">Histórico de Medidas</h1>
    </div>
    <?php
    if ($result_medidas && $result_medidas->num_rows > 0) {
        $medidas_por_data = array();
        while ($row_medidas = $result_medidas->fetch_assoc()) {
            $data_medida_formatada = $row_medidas['data_medida_formatada'];
            if (!isset($medidas_por_data[$data_medida_formatada])) {
                $medidas_por_data[$data_medida_formatada] = array();
            }
            $medidas_por_data[$data_medida_formatada][] = $row_medidas;
        }
        foreach ($medidas_por_data as $data_medida => $medidas) {
            echo "<h3 class='mt-4 mb-3'> Medidas ($data_medida):</h3>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-hover'>";
            echo "<thead class='table-primary'><tr><th>Altura (cm)</th><th>Peso (kg)</th><th>Cintura (cm)</th><th>Quadril (cm)</th><th>Data</th></tr></thead><tbody>";
            foreach ($medidas as $medida) {
                echo "<tr>";
                echo "<td>" . $medida['altura'] . "</td>";
                echo "<td>" . $medida['peso'] . "</td>";
                echo "<td>" . $medida['cintura'] . "</td>";
                echo "<td>" . $medida['quadril'] . "</td>";
                echo "<td>" . $medida['data_medida_formatada'] . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
            echo "</div>";
        }
    } else {
        echo "<p class='text-center mt-4'>Nenhuma medida registrada ainda.</p>";
    }
    ?>
</div>
</body>
</html>
