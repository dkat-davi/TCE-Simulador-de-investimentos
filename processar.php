<!DOCTYPE html>
<html lang="pt-br">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processar PHP</title>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/processar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>


<body>
    <div class="container">
        <header>
            <h1><a href="./index.html">Simulador de Investimentos</a></h1>
            <nav>
                <ul>
                    <li><a href="entrada.html"><i class="fa-solid fa-sack-dollar"></i>&nbsp;Fazer Simulação</a></li>
                    <li><a href="historico.php"><i class="fa-solid fa-file-lines"></i>&nbsp;Recuperar Simulação</a></li>
                </ul>
            </nav>
        </header>
        <main>

            <?php

            require_once 'classes/autoloader.class.php';

            R::setup('mysql:host=localhost; dbname=fintech', 'root', '');

            if (isset($_GET['aporteinicial']) && isset($_GET['aportemensal'])) {
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

            echo "<fieldset class=\"data-wrapper\">";
            echo "<legend>Dados da Simulação</legend>";
            echo "<p>ID da Simulação: $id</p>";
            echo "<p>Cliente: $cliente</p>";
            echo "<p>Aporte Mensal: $aporteinicial</p>";
            echo "<p>Aporte Mensal: $aportemensal</p>";
            echo "<p>Rendimento: $rendimento</p>";
            echo "<p>Periodo: $periodo</p>";
            echo "</fieldset>";

            if (isset($_GET['aporteinicial']) && isset($_GET['aportemensal']) && isset($_GET['rendimento']) && isset($_GET['periodo'])) {
                $aporteInicial = $_GET['aporteinicial'];
                $aportemensal = $_GET['aportemensal'];
                $rendimentoMensal = $_GET['rendimento'];
                $periodo = $_GET['periodo'];

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
                        $aporte = $aportemensal;
                    }

                    list($rendimento, $total) = calcularValores($valorAtual, $aporte, $rendimentoMensal);

                    $resultados[] = array(
                        'mes' => $i,
                        'valorinicial' => $valorAtual,
                        'rendimento' => $rendimento,
                        'total' => $total
                    );

                    $valorAtual = $total;
                }

                echo "<div class=\"table-wrapper\">
                <table>
                <caption>Resultados da Simulação</caption>
                <tr>
                  <th>Mês</th>
                  <th>Aplicação (R$)</th>
                  <th>Rendimento (R$)</th>
                  <th>Total (R$)</th>
                </tr>";

                foreach ($resultados as $resultado) {
                    echo "<tr>";
                    echo "<td>" . $resultado['mes'] . "</td>";
                    echo "<td>" . number_format($resultado['valorinicial'], 2, ',', '.') . "</td>";
                    echo "<td>" . number_format($resultado['rendimento'], 2, ',', '.') . "</td>";
                    echo "<td>" . number_format($resultado['total'], 2, ',', '.') . "</td>";
                    echo "</tr>";
                }

                echo "</table></div>";
            }

            ?>

            <hr>
        </main>
        <footer>
            <p>&copy;Copyright 2023 - <a href="https://github.com/jbragas618" target="_blank">João Braga</a> & <a href="https://github.com/dkat-davi" target="_blank">Davi Kalel</a></p>
        </footer>
    </div>
</body>

</html>