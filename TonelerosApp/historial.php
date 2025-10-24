<?php
require 'verifySession.php';
session_start();
require 'connectDb2.php';

$consulta = "SELECT *, DATE_FORMAT(fecha, '%d/%m') as fechaFormateada FROM agenda WHERE archivado = 1 ORDER BY fecha DESC";
$datos = mysqli_query($conn, $consulta);
$suma = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historial Toneleros</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
</head>
<body class="bg-gray-100 text-gray-900 font-sans">

  <?php require 'header.php'; ?>

  <div class="max-w-7xl mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
      <a href="agenda.php" class="text-blue-600 hover:underline text-lg">
        <i class="fas fa-arrow-left mr-2"></i>Volver
      </a>
      <a href="ingresos.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded inline-flex items-center text-sm">
        <i class="fas fa-euro-sign mr-2"></i>Ingresos
      </a>
    </div>

    <h2 class="text-3xl font-bold mb-6 text-center">Historial de Actuaciones</h2>

    <div class="overflow-x-auto shadow-lg rounded-lg bg-white">
      <table class="min-w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-200 text-gray-700 uppercase">
          <tr>
            <th class="px-4 py-3">Tipo Evento</th>
            <th class="px-4 py-3">Fecha</th>
            <th class="px-4 py-3">Dirección</th>
            <th class="px-4 py-3">Teléfono</th>
            <th class="px-4 py-3">Presupuesto Bruto</th>
            <th class="px-4 py-3">A Cobrar</th>
            <th class="px-4 py-3 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while($fila = mysqli_fetch_assoc($datos)): 
            $suma += $fila["presupuesto"];
          ?>
            <tr class="border-t">
              <td class="px-4 py-3"><?php echo $fila["tipo"]; ?></td>
              <td class="px-4 py-3"><?php echo $fila["fechaFormateada"]; ?></td>
              <td class="px-4 py-3"><?php echo $fila["direccion"]; ?></td>
              <td class="px-4 py-3"><?php echo $fila["tlf"]; ?></td>
              <td class="px-4 py-3"><?php echo $fila["presupuesto"]; ?>€</td>
              <td class="px-4 py-3 text-green-700 font-semibold">
                <?php echo $fila["presupuesto"] - $fila["senal"]; ?>€
              </td>
              <td class="px-4 py-3 space-x-1 text-center">
                <a href="editar.php?idAct=<?php echo $fila["id"]; ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">Editar</a>
                <a href="detalle.php?idAct=<?php echo $fila["id"]; ?>" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs">Detalles</a>
                <a href="borrar.php?idAct=<?php echo $fila["id"]; ?>" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">Borrar</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div class="mt-6 text-center">
      <h5 class="text-xl font-medium">Ganancias totales:</h5>
      <h3 class="text-2xl font-bold text-green-600"><?php echo $suma; ?> €</h3>
    </div>
  </div>
</body>
</html>
