<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      Administrar tickets de vehículos
    </h1>

    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Administrar tickets</li>
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
        <a href="registroentrada">
          <button type="button" class="btn btn-primary">
            Agregar ticket
          </button>
        </a>
      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         <tr>
           <th>#</th>
           <th>Tipo de Vehículo</th>
           <th>Nombre del Vehículo</th>
           <th>Número de Placa</th>
           <th>Acciones</th>
         </tr> 
        </thead>

        <tbody>

        <?php
          // Llamamos al controlador para obtener los datos de los tickets y vehículos
          $tickets = ControladorVentasTickets::ctrMostrarTickets();
          
          // Iteramos sobre los resultados para mostrarlos en la tabla
          foreach ($tickets as $key => $ticket) {
            echo '<tr>
                    <td>'.($key + 1).'</td>
                    <td>'.$ticket["tipo_vehiculo"].'</td>
                    <td>'.$ticket["nombre_vehiculo"].'</td>
                    <td>'.$ticket["numero_placa"].'</td>
                    <td>
                      <div class="btn-group">
                        <a class="btn btn-success" href="pdf/parking-t58.php?id='.$ticket["ticket_id"].'">Visualizar PDF</a>
                       <button class="btn btn-danger btnEliminarTicket" data-id="'.$ticket["ticket_id"].'">Eliminar</button>
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
