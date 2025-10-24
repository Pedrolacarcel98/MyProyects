
<?php

$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';
$city = isset($_GET['city']) ? $_GET['city'] : '';
$soloCerrados = isset($_GET['soloCerrados']) ? 1 : 0;


$consulta = "SELECT *, DATE_FORMAT(fecha, '%d/%m') as fechaFormateada FROM agenda WHERE tipo != 'FeriaS' AND archivado != 1";


if ($name != '') {
    $consulta .= " AND LOWER(pContacto) LIKE LOWER('%" . mysqli_real_escape_string($conn, $name) . "%')";
}

if ($city != '') {
    $consulta .= " AND LOWER(direccion) LIKE LOWER('%" . mysqli_real_escape_string($conn, $city) . "%')";
}

if ($month != '') {
    $consulta .= " AND MONTH(fecha) = '$month'";
}

if ($year != '') {
    $consulta .= " AND YEAR(fecha) = '$year'";
}



if ($soloCerrados) {
    $consulta .= " AND cerrada = 1";
}

$consulta .= " ORDER BY fecha";

// Ejecutar la consulta
$datos = mysqli_query($conn, $consulta);
if($datos && mysqli_num_rows($datos) == 0){
    echo "<p style='color: red; font-size: 18px; text-align: center;'>No se encontraron resultados.</p>";
}


?>

    <!-- FILTROS -->
        
        

    <div class="dropdown">
        <button class="btn btn-info dropdown-toggle" type="button" id="filterDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            FILTRAR
        </button>
        <div class="dropdown-menu" aria-labelledby="filterDropdown">
            <a class="dropdown-item" href="#" data-toggle="collapse" data-target="#filterForm">Filtros</a>
        </div>
    </div>

    <!-- Formulario de filtros -->
    <div id="filterForm" class="collapse mt-3">
        <form action="#" method="GET">
            
            <!-- Mes -->
            <div class="form-group">
                <label for="month">Mes</label>
                <select class="form-control" id="month" name="month">
                    <option value="">Selecciona un mes</option>
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </div>
            
            <!-- Año -->
            <div class="form-group">
                <label for="year">Año</label>
                <select class="form-control" id="year" name="year">
                    <option value="">Selecciona un año</option>
                    <?php
                        $currentYear = date('Y');
                        for ($i = $currentYear - 5; $i <= $currentYear + 2; $i++) {
                            echo "<option value=\"$i\" " . ($year == $i ? 'selected' : '') . ">$i</option>";
                        }
                    ?>
                </select>
            </div>

            <!-- Nombre -->
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nombre del cliente" value="<?php echo htmlspecialchars($name); ?>">
            </div>
            


            <!-- Ciudad -->
            <div class="form-group">
                <label for="city">Ciudad</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Dirección/Ciudad/Pueblo" value="<?php echo htmlspecialchars($city); ?>">
            </div>
            
            
            <!-- Checkbox -->

            <div class="form-group">
                <input type="checkbox" id="soloCerrados" name="soloCerrados" <?php echo $soloCerrados ? 'checked' : ''; ?>>
                <label for="soloCerrados">Mostrar solo eventos cerrados</label>
            </div>
            
            <!-- Botón de aplicar filtro -->
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary btn-lg">Aplicar</button>
            </div>
        </form>
    </div>

</div>

