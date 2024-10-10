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
        <h1>
            Registrar Vehículo
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Registrar Vehículo</li>
        </ol>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalRegistrarVehiculo">
                    Registrar Vehículo
                </button>
            </div>

            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
                    <thead>
                        <tr>
                            <th style="width:10px">#</th>
                            <th>Tipo de Vehículo</th>
                            <th>Nombre del Vehículo</th>
                            <th>Número de Placa</th> <!-- Cambiado aquí -->
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $item = null;
                        $valor = null;

                        $vehiculos = ControladorVehiculos::ctrMostrarVehiculos($item, $valor);

                        foreach ($vehiculos as $key => $value) {
                            echo '<tr>
                                    <td>' . ($key + 1) . '</td>
                                    <td>' . $value["tipo_vehiculo"] . '</td>
                                    <td>' . $value["nombre_vehiculo"] . '</td>
                                    <td>' . $value["numero_placa"] . '</td> <!-- Cambiado aquí -->
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-warning btnEditarVehiculo" data-toggle="modal" data-target="#modalEditarVehiculo" idVehiculo="' . $value["id"] . '"><i class="fa fa-pencil"></i></button>
                                            <button class="btn btn-danger btnEliminarVehiculo" idVehiculo="' . $value["id"] . '"><i class="fa fa-times"></i></button>
                                        </div>  
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

<!--=====================================
MODAL REGISTRAR VEHÍCULO
======================================-->

<div id="modalRegistrarVehiculo" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formRegistrarVehiculo">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Registrar Vehículo</h4>
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
                        <!-- Número del Vehículo (Placa) -->
                        <div class="form-group">
                            <label for="numeroPlaca">Número de Placa</label> <!-- Cambiado aquí -->
                            <input type="text" class="form-control input-lg" name="numeroPlaca" id="numeroPlaca" placeholder="Ingresar número de placa" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Registrar Vehículo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['idVehiculo']) && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $eliminarVehiculo = new ControladorVehiculos();
    $eliminarVehiculo->ctrEliminarVehiculo($_POST['idVehiculo']);
}
?>
