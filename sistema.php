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
<html lang="pt_br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saúde e Alimentação</title>
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
    .form-group {
        margin-bottom: 1.5rem;
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
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Inicio</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link" href="#Imc">IMC</a>
                <a class="nav-link" href="#proteinas">Proteinas</a>
                <a class="nav-link" href="#agua">Água</a>
                <a class="nav-link" href="#carbo">Carboidratos</a>
                <a class="nav-link" href="#medidas">Medidas</a>
            </div>
            <div class="ms-auto"> 
                <a href="sair.php" class="btn  me-5">Sair</a>
            </div>
        </div>
    </div>
</nav>
<div class="container">
    <br>
    <div class="jumbotron">
        <h1 class="display-4">Calculadora de Saúde e Alimentação</h1>
</div>
<div id="Imc"></div>
    <div class="jumbotron">
        <h2>IMC</h2>
         <P>O Índice de Massa Corporal (IMC) é uma medida utilizada para avaliar se uma pessoa está dentro de um peso considerado saudável para sua altura. O resultado do IMC fornece uma indicação geral sobre a proporção de gordura corporal de uma pessoa e é amplamente utilizado como uma ferramenta de triagem para identificar possíveis problemas de peso, como obesidade ou baixo peso.</P>
         <P>Faça o calculo abaixo para saber qual é o seu IMC:</P>
</div> 

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title">Calculadora de IMC</h2>
                    <div class="form-group">
                        <label for="peso">Peso (kg):</label>
                        <input type="number" id="peso" class="form-control" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="altura">Altura (m):</label>
                        <input type="number" id="altura" class="form-control" step="0.01" required>
                    </div>
                    <button onclick="calcularIMC()" class="btn">Calcular IMC</button>
                    <div id="resultadoIMC" class="resultado"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <img src="img/IMC.png" alt="IMC" class="img-fluid" width="400" style="position: relative; left: 100px;" >>
        </div>
    </div>
    <div>
        <h3>Como interpretar o resultado de IMC</h3>
        <p>Depois de obter o resultado de IMC, deve-se interpretar o valor utilizando a seguinte tabela:</p>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th scope="col">IMC</th>
                        <th scope="col">Classificação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Menor que 18,5</td>
                        <td>Magreza</td>
                    </tr>
                    <tr>
                        <td>18,5 a 24,9</td>
                        <td>Normal</td>
                    </tr>
                    <tr>
                        <td>25 a 29,9</td>
                        <td>Sobrepeso</td>
                    </tr>
                    <tr>
                        <td>30 a 34,9</td>
                        <td>Obesidade grau I</td>
                    </tr>
                    <tr>
                        <td>35 a 39,9</td>
                        <td>Obesidade grau II</td>
                    </tr>
                    <tr>
                        <td>Maior que 40</td>
                        <td>Obesidade grau III</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div>
        <h3>Como é feito o cálculo de IMC</h3>
        <p>O IMC é calculado dividindo o peso (em kg) pela altura ao quadrado (em m), de acordo com a seguinte fórmula: IMC = peso / (altura x altura). O resultado de IMC é dado em kg/m2.</p>
    </div>
    <br>
    <br>
<div>
    <div id="proteinas"></div>
        <div class="jumbotron">
        <h2>Proteinas</h2>
        <p>As proteínas são essenciais para a construção e reparo de tecidos do corpo, como músculos, ossos e pele. Consumir proteínas adequadas é crucial para a manutenção e reparação dos tecidos, especialmente após exercícios e durante o crescimento. Fontes de proteína incluem carnes magras, aves, peixes, ovos, laticínios, legumes, nozes e sementes. Use a calculadora abaixo para determinar sua ingestão diária ideal de Proteinas:</p>
    </div>    

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Calculadora de Proteínas Diárias</h2>
                    <div class="form-group">
                        <label for="pesoProteina">Peso (kg):</label>
                        <input type="number" id="pesoProteina" class="form-control" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="nivelAtividade">Nível de Atividade:</label>
                        <select id="nivelAtividade" class="form-control">
                            <option value="sedentario">Sedentário (pouca ou nenhuma atividade física)</option>
                            <option value="leve">Leve (exercício leve 1-3 dias por semana)</option>
                            <option value="moderado">Moderado (exercício moderado 3-5 dias por semana)</option>
                            <option value="ativo">Ativo (exercício intenso 6-7 dias por semana)</option>
                            <option value="muitoAtivo">Muito Ativo (exercício muito intenso, trabalho físico pesado ou treinamento duas vezes por dia)</option>
                        </select>
                    </div>
                    <button onclick="calcularProteinas()" class="btn">Calcular</button>
                    <div id="resultadoProteina" class="resultado"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <img src="img/proteinas.jpeg" alt="Proteínas" class="img-fluid" width="300" style="position: relative; left: 100px;" >>
        </div>
    </div>
</div>

<script>
    function calcularIMC() {
        var peso = parseFloat(document.getElementById("peso").value);
        var altura = parseFloat(document.getElementById("altura").value);
        
        if (isNaN(peso) || isNaN(altura) || peso <= 0 || altura <= 0) {
            document.getElementById("resultadoIMC").innerHTML = "<div class='alert alert-danger'>Por favor, insira valores válidos para peso e altura.</div>";
            return;
        }
        
        var imc = peso / (altura * altura);
        var mensagem = "Seu IMC é: " + imc.toFixed(2) + ". ";
        
        if (imc < 18.5) {
            mensagem += "Você está abaixo do peso ideal.";
        } else if (imc < 25) {
            mensagem += "Você está dentro do peso ideal.";
        } else {
            mensagem += "Você está acima do peso ideal.";
        }
        
        document.getElementById("resultadoIMC").innerHTML = "<div class='alert alert-info'>" + mensagem + "</div>";
    }
    
    function calcularProteinas() {
        var peso = parseFloat(document.getElementById("pesoProteina").value);
        var nivelAtividade = document.getElementById("nivelAtividade").value;
        
        if (isNaN(peso) || peso <= 0) {
            document.getElementById("resultadoProteina").innerHTML = "<div class='alert alert-danger'>Por favor, insira um peso válido.</div>";
            return;
        }
        
        var fatorAtividade = 1.2; // Fator de atividade padrão para sedentário
        
        switch (nivelAtividade) {
            case "sedentario":
                fatorAtividade = 1.2;
                break;
            case "leve":
                fatorAtividade = 1.4;
                break;
            case "moderado":
                fatorAtividade = 1.6;
                break;
            case "ativo":
                fatorAtividade = 1.8;
                break;
            case "muitoAtivo":
                fatorAtividade = 2.0;
                break;
            default:
                break;
        }
        
        var proteinaMinima = peso * 0.8; // Gramas de proteína por kg de peso corporal (mínimo)
        var proteinaMaxima = peso * 2.2; // Gramas de proteína por kg de peso
        var proteinaRecomendada = (proteinaMinima + proteinaMaxima) / 2 * fatorAtividade; // Média com base no fator de atividade
    
        document.getElementById("resultadoProteina").innerHTML = "<div class='alert alert-info'>Ingestão recomendada de proteínas: " + proteinaRecomendada.toFixed(2) + "g/dia</div>";
    }

  </script>

<br>
<br>
<div class="container">
    <div class="jumbotron">
        <h2>Ingestão diária de Carboidratos</h2>
        <p>
            Os carboidratos são nutrientes essenciais que fornecem energia ao corpo, principalmente para o cérebro e os músculos. Presentes em grãos, frutas e vegetais, eles podem ser simples (energia rápida) ou complexos (energia sustentada). Consumir a quantidade adequada é vital para manter os níveis de energia e apoiar as funções corporais diárias. Use a calculadora abaixo para determinar sua ingestão diária ideal de Carboidratos:
        </p>
        <div id="carbo"></div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Calculadora de Carboidratos</h2>
                    <div class="form-group">
                        <label for="pesoCarbo">Peso (kg):</label>
                        <input type="number" id="pesoCarbo" class="form-control" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="nivelAtividadeCarbo">Nível de Atividade:</label>
                        <select id="nivelAtividadeCarbo" class="form-control">
                            <option value="sedentario">Sedentário (pouca ou nenhuma atividade física)</option>
                            <option value="leve">Leve (exercício leve 1-3 dias por semana)</option>
                            <option value="moderado">Moderado (exercício moderado 3-5 dias por semana)</option>
                            <option value="ativo">Ativo (exercício intenso 6-7 dias por semana)</option>
                            <option value="muitoAtivo">Muito Ativo (exercício muito intenso, trabalho físico pesado ou treinamento duas vezes por dia)</option>
                        </select>
                    </div>
                    <button onclick="calcularCarboidratos()" class="btn btn-primary">Calcular</button>
                    <div id="resultadoCarbo" class="resultado mt-3"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <img src="img/carbo.jpg" alt="Carboidratos" class="img-fluid" width="300" style="position: relative; left: 100px;">
        </div>
    </div>
</div>

<script>
    function calcularCarboidratos() {
        var peso = parseFloat(document.getElementById('pesoCarbo').value);
        var nivelAtividade = document.getElementById('nivelAtividadeCarbo').value;
        var fatorAtividade;
        switch (nivelAtividade) {
            case 'sedentario':
                fatorAtividade = 3;
                break;
            case 'leve':
                fatorAtividade = 4;
                break;
            case 'moderado':
                fatorAtividade = 5;
                break;
            case 'ativo':
                fatorAtividade = 6;
                break;
            case 'muitoAtivo':
                fatorAtividade = 7;
                break;
        }
        var carboidratos = peso * fatorAtividade;
        var resultadoCarbo = document.getElementById('resultadoCarbo');
        resultadoCarbo.innerHTML = '<div class="alert alert-info">Você deve consumir aproximadamente ' + carboidratos.toFixed(2) + ' gramas de carboidratos por dia.</div>';
    }
</script>
    <br>
    <br>

<div id="agua">
<div class="container">
    <div class="jumbotron">
        <h2>Ingestão diária de Água</h2>
        <p>A água desempenha um papel fundamental em nossa saúde. Calcular a ingestão diária recomendada de água pode ajudar a manter o corpo hidratado e funcionando adequadamente. Use a calculadora abaixo para determinar sua ingestão diária ideal de água:</p>
    </div>
    <div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title">Calculadora de Água</h2>
                    <div class="form-group">
                        <label for="pesoagua">Peso (kg):</label>
                        <input type="number" id="pesoagua" class="form-control" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="NivelAtividade">Nível de Atividade:</label>
                        <select id="NivelAtividade" class="form-control">
                            <option value="sedentario">Sedentário (pouca ou nenhuma atividade física)</option>
                            <option value="leve">Leve (exercício leve 1-3 dias por semana)</option>
                            <option value="moderado">Moderado (exercício moderado 3-5 dias por semana)</option>
                            <option value="ativo">Ativo (exercício intenso 6-7 dias por semana)</option>
                            <option value="muitoAtivo">Muito Ativo (exercício muito intenso, trabalho físico pesado ou treinamento duas vezes por dia)</option>
                        </select>
                    </div>
                    <button onclick="calcularIngestaoAgua()" class="btn">Calcular</button>
                    <div id="resultadoIngestaoAgua" class="resultado"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <img src="img/agua" alt="agua" class="img-fluid" class="img-fluid" width="300" style="position: relative; left: 100px;" >
        </div>
    </div>
</div>
<br>
<br>
<div id="medidas">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h2> Medidas Corporais</h2>
                        <p>O acompanhamento das medidas corporais é uma parte importante do monitoramento do progresso físico e da saúde geral. Use a seção abaixo para registrar e acompanhar suas medidas corporais:

</p>
                        <form method="post" action="codMedidas.php">
                            <div class="mb-3">
                                <label for="altura" class="form-label">Altura (cm):</label>
                                <input type="number" class="form-control" id="altura" name="altura" required>
                            </div>
                            <div class="mb-3">
                                <label for="peso" class="form-label">Peso (kg):</label>
                                <input type="number" class="form-control" id="peso" name="peso" required>
                            </div>
                            <div class="mb-3">
                                <label for="cintura" class="form-label">Cintura (cm):</label>
                                <input type="number" class="form-control" id="cintura" name="cintura" required>
                            </div>
                            <div class="mb-3">
                                <label for="quadril" class="form-label">Quadril (cm):</label>
                                <input type="number" class="form-control" id="quadril" name="quadril" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                            <a href="historicoMedidas.php" class="btn btn-secondary">Ver Histórico de Medidas</a>
                         </form>
                    </div>
                </div>
            </div>
<!-- Dentro do bloco HTML onde você deseja exibir as últimas medidas do corpo -->
<div class="col-md-6">
    <div class="card">
        <div class="card-body">
            <!-- <h2>Resultado das Medidas</h2>-->
            <div id="resultadoMedidas">
                <?php
                if(isset($resultado)) {
                    echo "<p>$resultado</p>";
                }
                ?>
            </div>
            <h3>Últimas medidas registradas:</h3>
            <?php
            if(isset($usuario_id)) {
                $query_ultimas_medidas = "SELECT altura, peso, cintura, quadril, data_medida FROM medidas_corpo WHERE usuario_id = '$usuario_id' ORDER BY data_medida DESC LIMIT 1";
                $result_ultimas_medidas = $conexao->query($query_ultimas_medidas);
                if ($result_ultimas_medidas && $result_ultimas_medidas->num_rows > 0) {
                    $row_ultimas_medidas = $result_ultimas_medidas->fetch_assoc();
                    echo "<p>Altura: " . $row_ultimas_medidas['altura'] . " cm</p>";
                    echo "<p>Peso: " . $row_ultimas_medidas['peso'] . " kg</p>";
                    echo "<p>Cintura: " . $row_ultimas_medidas['cintura'] . " cm</p>";
                    echo "<p>Quadril: " . $row_ultimas_medidas['quadril'] . " cm</p>";
                    echo "<p>Data: " . $row_ultimas_medidas['data_medida'] . "</p>";
                } else {
                    echo "<p>Nenhuma medida registrada ainda.</p>";
                }
            } else {
               echo "<p>Erro: O ID do usuário não foi encontrado.</p>";
            }
            ?>
        </div>
    </div>
</div>
    <!--<div>
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
                echo "<h3 class='mt-4 mb-3'>Histórico de Medidas ($data_medida):</h3>";
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
</div>-->


<script>
        function calcularIngestaoAgua() {
            var peso = parseFloat(document.getElementById("pesoagua").value);
            var nivelAtividade = document.getElementById("NivelAtividade").value;
            
            if (isNaN(peso) || peso <= 0) {
                document.getElementById("resultadoIngestaoAgua").innerHTML = "<div class='alert alert-danger'>Por favor, insira um peso válido.</div>";
                return;
            }
            
            var fatorAtividade = 1.0; // Fator de atividade padrão para sedentário
            
            switch (nivelAtividade) {
                case "sedentario":
                    fatorAtividade = 1.0;
                    break;
                case "leve":
                    fatorAtividade = 1.2;
                    break;
                case "moderado":
                    fatorAtividade = 1.5;
                    break;
                case "ativo":
                    fatorAtividade = 1.8;
                    break;
                case "muitoAtivo":
                    fatorAtividade = 2.0;
                    break;
                default:
                    break;
            }
            
            var ingestaoAgua = peso * 0.035 * fatorAtividade; // Recomendação de ingestão de água em litros por kg de peso
            
            document.getElementById("resultadoIngestaoAgua").innerHTML = "<div class='alert alert-info'>Ingestão recomendada de água: " + ingestaoAgua.toFixed(2) + " litros/dia</div>";
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5
</body>
</html>