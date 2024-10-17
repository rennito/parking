$(document).ready(function () {
  // Inicialización del Date Range Picker
  $('#daterange-btn2').daterangepicker(
    {
      ranges: {
        Hoy: [moment(), moment()],
        Ayer: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Últimos 7 Días': [moment().subtract(6, 'days'), moment()],
        'Últimos 30 Días': [moment().subtract(29, 'days'), moment()],
        'Este Mes': [moment().startOf('month'), moment().endOf('month')],
        'Mes Anterior': [
          moment().subtract(1, 'month').startOf('month'),
          moment().subtract(1, 'month').endOf('month'),
        ],
      },
      startDate: moment().subtract(29, 'days'), // Rango por defecto
      endDate: moment(),
      locale: {
        format: 'YYYY-MM-DD', // Formato de la fecha
        applyLabel: 'Aplicar',
        cancelLabel: 'Cancelar',
        customRangeLabel: 'Rango personalizado',
        daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        monthNames: [
          'Enero',
          'Febrero',
          'Marzo',
          'Abril',
          'Mayo',
          'Junio',
          'Julio',
          'Agosto',
          'Septiembre',
          'Octubre',
          'Noviembre',
          'Diciembre',
        ],
        firstDay: 1,
      },
    },
    function (start, end) {
      // Asignar las fechas seleccionadas a los campos ocultos
      $('#fechaInicial').val(start.format('YYYY-MM-DD'));
      $('#fechaFinal').val(end.format('YYYY-MM-DD'));

      // Mostrar las fechas seleccionadas en el botón
      $('#daterange-btn2 span').html(
        start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'),
      );
    },
  );

  // Verificación antes de enviar el formulario
  $('#reporteForm').on('submit', function (e) {
    // Asegurarse de que los campos de fechas no estén vacíos
    let fechaInicial = $('#fechaInicial').val();
    let fechaFinal = $('#fechaFinal').val();

    if (!fechaInicial || !fechaFinal) {
      e.preventDefault(); // Evita que se envíe el formulario
      alert(
        'Por favor, selecciona un rango de fechas antes de descargar el reporte.',
      );
    }
  });
});
