<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Histórico</title>
  <link rel="stylesheet" href="styles/global.css">
  <link rel="stylesheet" href="styles/historico.css">
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
      <form>
        <fieldset class="data-wrapper">
          <legend>Simulação a se recuperar</legend>

          <label for="simulacaoid">ID da simulação</label>
          <input type="number" name="simulacaoid" id="simulacaoid" value=<?= "\"" . (isset($_GET['simulacaoid']) ? $_GET['simulacaoid'] : "\"\"") . "\"" ?>>
          <button type="submit">Recuperar</button>

        </fieldset>
      </form>

      <?php
      if (isset($_GET['simulacaoid'])) {
        require_once 'classes/autoloader.class.php';

        R::setup('mysql:host=localhost; dbname=fintech', 'root', '');

        $id = $_GET['simulacaoid'];
        $simulation = R::load('fintech',  $id);

        if ($simulation->id === 0) {
          echo "<p class=\"error\">ID INEXISTENTE, TENTE NOVAMENTE!</p>";
        } else {

          echo "<fieldset class=\"data-wrapper\">";
          echo "<legend>Dados da Simulação</legend>";
          echo "<p>ID da Simulação: $simulation->id</p>";
          echo "<p>Cliente: $simulation->nomecliente</p>";
          echo "<p>Aporte Mensal: $simulation->aporteinicial</p>";
          echo "<p>Aporte Mensal: $simulation->aportemensal</p>";
          echo "<p>Rendimento: $simulation->rendimento</p>";
          echo "<p>Periodo: $simulation->periodo</p>";
          echo "</fieldset>";

          $aporteInicial = floatval($simulation->aporteinicial);
          $aportemensal = intval($simulation->aportemensal);
          $rendimentoMensal = floatval($simulation->rendimento);
          $periodo = floatval($simulation->periodo);

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
                <caption>Resultado da Simulação</caption>
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

        R::close();
      }
      ?>
    </main>
    <footer>
      <p>&copy;Copyright 2023 - <a href="https://github.com/jbragas618" target="_blank">João Braga</a> & <a href="https://github.com/dkat-davi" target="_blank">Davi Kalel</a></p>
    </footer>
  </div>
</body>

</html>