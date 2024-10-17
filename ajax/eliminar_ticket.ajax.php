<?php

require_once "../controladores/ventasTickets.controlador.php";
require_once "../modelos/ventasTickets.modelo.php";

if (isset($_POST["id"])) {
    $ticket_id = $_POST["id"];
    $respuesta = ControladorVentasTickets::ctrEliminarTicket($ticket_id);

    echo $respuesta ? "El ticket ha sido eliminado correctamente." : "Error al eliminar el ticket.";
} else {
    echo "No se proporcionó un ID válido.";
}

?>
