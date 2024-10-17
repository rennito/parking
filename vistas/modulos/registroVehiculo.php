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
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalRegistrarEntradaVehiculo">
                    Registrar Carro
                </button>
            </div>

            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
                    <thead>
                        <tr>
                            <th style="width:10px">#</th>
                            <th>Tipo de Vehículo</th>
                            <th>Nombre del Vehículo</th>
                            <th>Número de Placa</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $item = null;
                        $valor = null;

                        $vehiculos = ControladorCarro::ctrMostrarEntradasVehiculos($item, $valor);

                        foreach ($vehiculos as $key => $value) {
                            // Si tu ID del vehículo se llama "vehiculo_id", usa eso en lugar de "id"
                            $vehiculoId = isset($value["vehiculo_id"]) ? $value["vehiculo_id"] : 'N/A';

                            echo '<tr>
                                    <td>' . ($key + 1) . '</td>
                                    <td>' . $value["tipo_vehiculo"] . '</td>
                                    <td>' . $value["nombre_vehiculo"] . '</td>
                                    <td>' . $value["numero_placa"] . '</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-warning btnEditarVehiculo" data-toggle="modal" data-target="#modalEditarVehiculo" idVehiculo="' . $vehiculoId . '"><i class="fa fa-pencil"></i></button>
                                                    <button class="btn btn-danger btnEliminarVehiculo" idVehiculo="' . $value["id"] . '">
                                                    <i class="fa fa-trash"></i> Eliminar </button>
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




<!--=====================================
MODAL CONFIRMACIÓN DE ELIMINACIÓN
======================================-->

<div id="modalConfirmarEliminacion" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm"> <!-- Cambiado a modal-sm para hacerlo más pequeño -->
        <div class="modal-content">
            <div class="modal-header" style="background-color: #3c8dbc; color: white;">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center">¿Estás seguro de que deseas eliminar este vehículo?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarEliminarVehiculo">Eliminar</button>
            </div>
        </div>
    </div>
</div>
