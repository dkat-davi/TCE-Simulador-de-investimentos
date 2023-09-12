<!DOCTYPE html>
<html lang="pt-br">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processar PHP</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>


<body>
    <header>
        <h2>Desenvolvimento <i>WEB</i></h2>
    </header>
    <main>
       
    <?php
   
   require_once 'classes/autoloader.class.php';



    R::setup('mysql:host=localhost; dbname=trabalhophp' , 'root', '');


    if (isset($_GET['aporteinicial']) && isset($_GET['aportemensal']))  
    {
    $cliente = $_GET['cliente'];
    $aporteinicial =  $_GET['aporteinicial'];
    $aportemensal =  $_GET['aportemensal'];
    $rendimento = $_GET['rendimento'];
    $periodo = $_GET['periodo'];

    $fintech = R::dispense('fintech');
    $fintech->nomecliente = $_GET['cliente'];
    $fintech->aporteinicial = $_GET['aporteinicial'];
    $fintech->aportemensal = $_GET['aportemensal'];
    $fintech->rendimento = $_GET['rendimento'];
    $fintech->periodo = $_GET['periodo'];

    $id = R::store($fintech);

    R::close();
    }

     echo "<p>ID da Simulação: $id</p>";
    echo "<p>Cliente: $cliente</p>";
    echo "<p>Aporte Mensal: $aporteinicial</p>";
    echo "<p>Aporte Mensal: $aportemensal</p>";
    echo "<p>Rendimento: $rendimento</p>";
    echo "<p>Periodo: $periodo</p>";


    if (isset($_GET['aporteinicial']) && isset($_GET['aportemensal']) && isset($_GET['rendimento']) && isset($_GET['periodo'])) {
        $aporteInicial = floatval($_GET['aporteinicial']);
        $periodo = intval($_GET['aportemensal']);
        $rendimentoMensal = floatval($_GET['rendimento']);
        $aporteMensal = floatval($_GET['periodo']);
    
        function calcularValores($valorAtual, $aporte, $rendimentoMensal)
        {
            $total = $valorAtual + $aporte;
            $rendimento = $total * ($rendimentoMensal / 100);
            $total += $rendimento;
            $valores = array($rendimento, $total);
            return $valores;
        }
    
        $resultados = array();
        $valorAtual = $aporteInicial;
    
        for ($i = 1; $i <= $periodo; $i++) {
            if ($i == 1) {
                $aporte = 0;
            } else {
                $aporte = $aporteMensal;
            }
    
            list($rendimento, $total) = calcularValores($valorAtual, $aporte, $rendimentoMensal);
    
            $resultados[] = array(
                'mes' => $i,
                'valorinicial' => $valorAtual,
                'aporte' => $aporte,
                'rendimento' => $rendimento,
                'total' => $total
            );
    
            $valorAtual = $total;
        }
    
        echo "<h2>Resultados da Simulação</h2>
              <table>
                <tr>
                  <th>Mês</th>
                  <th>Valor Inicial</th>
                  <th>Aporte Mensal</th>
                  <th>Rendimento</th>
                  <th>Total</th>
                </tr>";
    
        foreach ($resultados as $resultado) {
            echo "<tr>";
            echo "<td>" . $resultado['mes'] . "</td>";
            echo "<td>" . number_format($resultado['valorinicial'], 2, ',', '.') . "</td>";
            echo "<td>" . number_format($resultado['aporte'], 2, ',', '.') . "</td>";
            echo "<td>" . number_format($resultado['rendimento'], 2, ',', '.') . "</td>";
            echo "<td>" . number_format($resultado['total'], 2, ',', '.') . "</td>";
            echo "</tr>";
        }
    
        echo "</table>";
    }

  ?>


    <h1>Simulação : Resultado</h1>


        <hr>
    </main>
    <footer>
        <p>&copy; 2023 - João Braga && Davi Teixeira</p>
    </footer>
</body>
