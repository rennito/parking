<?php

if ($_SESSION["perfil"] == "Especial") {
    echo '<script>
        window.location = "inicio";
    </script>';
    return;
}

?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Registrar Entrada del Vehículo</h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Registrar Entrada del Vehículo</li>
        </ol>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalRegistrarEntradaVehiculo">
                    Registrar Entrada
                </button>
            </div>

            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tipo de Vehículo</th>
                            <th>Nombre del Vehículo</th>
                            <th>Número de Placa</th>
                            <th>Hora de Entrada</th>
                            <th>Hora de Salida</th>
                            <th>Monto Total</th>
                            <th>Estado de Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $item = null;
                        $valor = null;

                        $vehiculos = ControladorVehiculos::ctrMostrarEntradasVehiculos($item, $valor);

                        foreach ($vehiculos as $key => $value) {
                            echo '<tr>
                                    <td>' . ($key + 1) . '</td>
                                    <td>' . $value["tipo_vehiculo"] . '</td>
                                    <td>' . $value["nombre_vehiculo"] . '</td>
                                    <td>' . $value["numero_placa"] . '</td>
                                    <td>' . $value["hora_entrada"] . '</td>
                                    <td>' . ($value["hora_salida"] ? $value["hora_salida"] : 'En curso') . '</td>
                                    <td>' . $value["monto_total"] . '</td>
                                    <td>' . ($value["estado_pago"] == 1 ? 'Pagado' : 'No Pagado') . '</td>
                                    <td>
                                        <div class="btn-group">';
                            if (!$value["hora_salida"]) {
                                echo '<button class="btn btn-success btnMarcarSalida" idVehiculo="' . $value["id"] . '"><i class="fa fa-check"></i></button>';

                            }
                            echo '</div>
                                    </td>
                                  </tr>';
                        }
                        
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal para registrar la entrada -->
<div id="modalRegistrarEntradaVehiculo" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        <form id="formRegistrarEntradaVehiculo" enctype="multipart/form-data">
    <div class="modal-header" style="background:#3c8dbc; color:white">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Registrar Entrada del Vehículo</h4>
    </div>
    <div class="modal-body">
        <div class="box-body">
            <!-- Tipo de Vehículo -->
            <div class="form-group">
                <label for="tipoVehiculo">Tipo de Vehículo</label>
                <select class="form-control input-lg" name="tipoVehiculo" id="tipoVehiculo" required>
                    <option value="">Seleccionar tipo de vehículo</option>
                    <option value="Sedan">Sedan</option>
                    <option value="Hatchback">Hatchback</option>
                    <option value="SUV">SUV</option>
                    <option value="Moto">Moto</option>
                    <option value="Pick-up">Pick-up</option>
                </select>
            </div>
            <!-- Nombre del Vehículo -->
            <div class="form-group">
                <label for="nombreVehiculo">Nombre del Vehículo</label>
                <input type="text" class="form-control input-lg" name="nombreVehiculo" id="nombreVehiculo" placeholder="Ingresar nombre del vehículo" required>
            </div>
            <!-- Número de Placa -->
            <div class="form-group">
                <label for="numeroPlaca">Número de Placa</label>
                <input type="text" class="form-control input-lg" name="numeroPlaca" id="numeroPlaca" placeholder="Ingresar número de placa" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
        <button type="submit" class="btn btn-primary">Registrar Entrada</button>
    </div>
</form>

        </div>
    </div>
</div>

