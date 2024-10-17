// Datos del ticket que se imprimirán
const horaImpresion = new Date().toLocaleString(); // Hora de impresión actual

// Aquí defines el contenido del ticket de 58 mm, reemplazando con los datos reales
const listaDeOperaciones = [
  {
    nombre: "Iniciar",
    argumentos: [],
  },
  {
    nombre: "DeshabilitarElModoDeCaracteresChinos",
    argumentos: [],
  },
  {
    nombre: "EscribirTexto",
    argumentos: ["******** TICKET VEHÍCULO ********\n"],
  },
  {
    nombre: "EscribirTexto",
    argumentos: ["Hora de Impresión: " + horaImpresion + "\n"],
  },
  {
    nombre: "EscribirTexto",
    argumentos: ["------------------------------\n"],
  },
  {
    nombre: "EscribirTexto",
    argumentos: [
      "Tipo Vehículo: " + tipoVehiculo + "\n", // Reemplaza con el tipo de vehículo
    ],
  },
  {
    nombre: "EscribirTexto",
    argumentos: [
      "Número Placa: " + numeroPlaca + "\n", // Reemplaza con el número de placa
    ],
  },
  {
    nombre: "EscribirTexto",
    argumentos: [
      "Hora Entrada: " + horaEntrada + "\n", // Reemplaza con la hora de entrada
    ],
  },
  {
    nombre: "EscribirTexto",
    argumentos: [
      "Hora Salida: " + horaSalida + "\n", // Reemplaza con la hora de salida
    ],
  },
  {
    nombre: "EscribirTexto",
    argumentos: [
      "Monto Total: $" + montoTotal + "\n", // Reemplaza con el monto total
    ],
  },
  {
    nombre: "EscribirTexto",
    argumentos: ["------------------------------\n"],
  },
  {
    nombre: "EscribirTexto",
    argumentos: ["¡Gracias por su visita!\n"],
  },
  {
    nombre: "EscribirTexto",
    argumentos: ["******************************\n"],
  },
  {
    nombre: "Cortar", // Si la impresora tiene la función de corte automático
    argumentos: [],
  },
];

// Hacer la solicitud al servidor de impresión para el ticket de 58 mm
const respuesta = await fetch("http://localhost:8000/imprimir", {
  method: "POST",
  body: JSON.stringify({
    serial: "", // Si tienes un serial de impresora, agrégalo aquí
    //nombreImpresora: "Impresora pors", // Reemplaza con el nombre de tu impresora de 58 mm
   nombreImpresora: "POS58 Printer", // Reemplaza con el nombre de tu impresora de 58 mm
    operaciones: listaDeOperaciones,
  }),
});
