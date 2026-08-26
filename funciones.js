$(function () {

  function buscarDefinicion() {
    var palabra = $('#palabra').val().trim();
    var idioma = $('#idioma').val();

    if (palabra === '') {
      $('#definicion').val('');
      return;
    }

    $('#definicion').val('Buscando...');

    $.post(
      './php/diccionario.php',
      {
        modo: 'get',
        palabra: palabra,
        idioma: idioma,
      },
      function (data) {
        $('#definicion').val(data.definicion);
      },
      'json'
    ).fail(function () {
      $('#definicion').val('Error al conectar con el servidor.');
    });
  }

  // Buscar al presionar Enter en el campo de la palabra
  $('#palabra').on('keypress', function (e) {
    if (e.which === 13) {
      e.preventDefault();
      buscarDefinicion();
    }
  });

  // Buscar al perder el foco (comportamiento original)
  $('#palabra').on('change', buscarDefinicion);

  // Buscar con el botón
  $('#buscar-btn').on('click', buscarDefinicion);

  // Volver a buscar si cambian el idioma y ya hay una palabra escrita
  $('#idioma').on('change', function () {
    if ($('#palabra').val().trim() !== '') {
      buscarDefinicion();
    }
  });

});
