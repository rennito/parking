<?php



require_once "controladores/ventasTickets.controlador.php";
require_once "controladores/graficaVentas.controlador.php";



require_once "controladores/carro.controlador.php";
require_once "modelos/carro.modelo.php";



require_once "vendor/autoload.php";


require_once "controladores/plantilla.controlador.php";
require_once "controladores/usuarios.controlador.php";



require_once "controladores/carroGeneraTicket.controlador.php";
require_once "modelos/carroGeneraTicket.modelo.php";






require_once "modelos/ventasTickets.modelo.php";
require_once "modelos/graficaVentas.modelo.php";

require_once "modelos/usuarios.modelo.php";





$plantilla = new ControladorPlantilla();
$plantilla -> ctrPlantilla();