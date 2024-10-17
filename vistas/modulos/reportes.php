<?php
// Solo iniciar la sesión si no está ya activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION["perfil"] == "Especial" || $_SESSION["perfil"] == "Vendedor") {
  echo '<script>window.location = "inicio";</script>';
  return;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reportes de ventas</title>
</head>
<body>

<div class="content-wrapper">

  <section class="content-header">
    <h1>Reportes de ventas</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Reportes de ventas</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <form method="POST" action="ajax/generar_reporte.ajax.php" target="_blank" id="reporteForm">
          <div class="input-group">
            <button type="button" class="btn btn-default" id="daterange-btn2">
              <span><i class="fa fa-calendar"></i> Rango de fecha</span>
              <i class="fa fa-caret-down"></i>
            </button>
          </div>

          <input type="hidden" name="fechaInicial" id="fechaInicial">
          <input type="hidden" name="fechaFinal" id="fechaFinal">

          <div class="box-tools pull-right">
            <button class="btn btn-success pull-right" type="submit" style="margin-top:5px">
              <i class="fa fa-file-excel-o"></i> Descargar reporte en Excel
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>

</div>

</body>
</html>
