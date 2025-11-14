<?php
require 'verifySession.php';
session_start();
require 'connectDb2.php';

function obtenerSumaPorMes($conn, $archivado) {
    $query = "
        SELECT 
            DATE_FORMAT(fecha, '%Y-%m') AS mes,
            SUM(presupuesto) AS total_mes,
            COUNT(*) AS eventos
        FROM agenda
        WHERE archivado = $archivado
        GROUP BY mes
        ORDER BY mes DESC
    ";
    return mysqli_query($conn, $query);
}

function obtenerSuma($conn, $archivado) {
    $query = "SELECT SUM(presupuesto) as suma FROM agenda WHERE archivado = $archivado";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['suma'] ?? 0;
}

$sumaProximo = obtenerSuma($conn, 0);
$sumaHistorial = obtenerSuma($conn, 1);
$historialMensual = obtenerSumaPorMes($conn, 1);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ganancias | Toneleros</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
</head>
<body class="bg-gray-100 text-gray-900 font-sans">
  <?php 
    require 'header.php'; 
    require 'botonHerramientas.php'; 
  ?>

  <div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-center mb-10 text-blue-800">Nuestras Ganancias</h1>

    <!-- Próximas ganancias -->
    <section class="bg-white rounded shadow p-6 mb-10">
      <h2 class="text-2xl font-semibold mb-4 text-green-600">Próximas Ganancias</h2>
      <p class="text-xl">Total: <span class="font-bold text-green-700"><?php echo $sumaProximo; ?> €</span></p>
      <p class="text-lg text-gray-700 mt-1">Por chorla: <span class="font-semibold"><?php echo round($sumaProximo / 5, 2); ?> €</span></p>
    </section>

    <!-- Historial acumulado -->
    <section class="bg-white rounded shadow p-6 mb-10">
      <h2 class="text-2xl font-semibold mb-4 text-indigo-600">Ganancias desde Feria 2024 (hasta hoy)</h2>
      <p class="text-xl">Total: <span class="font-bold text-indigo-700"><?php echo $sumaHistorial; ?> €</span></p>
      <p class="text-lg text-gray-700 mt-1">Por chorla: <span class="font-semibold"><?php echo round($sumaHistorial / 5, 2); ?> €</span></p>
    </section>

    <!-- Historial mensual -->
    <section class="bg-white rounded shadow p-6">
      <h2 class="text-2xl font-semibold mb-6 text-gray-800">Resumen mensual de ingresos</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-700">
          <thead class="bg-gray-200 uppercase text-xs">
            <tr>
              <th class="px-4 py-3">Mes</th>
              <th class="px-4 py-3">Total Ganado</th>
              <th class="px-4 py-3">Media por Actuación</th>
              <th class="px-4 py-3">Por Chorla</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($fila = mysqli_fetch_assoc($historialMensual)): 
              $media = $fila["total_mes"] / $fila["eventos"];
              $chorla = $fila["total_mes"] / 5;
            ?>
              <tr class="border-t">
                <td class="px-4 py-3"><?php echo $fila["mes"]; ?></td>
                <td class="px-4 py-3 font-medium text-green-700"><?php echo $fila["total_mes"]; ?> €</td>
                <td class="px-4 py-3"><?php echo round($media, 2); ?> €</td>
                <td class="px-4 py-3"><?php echo round($chorla, 2); ?> €</td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </section>
  </div>
</body>
</html>
