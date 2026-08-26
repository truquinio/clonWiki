nciones · JS
$(function () {
 
  function limpiarHtml(html) {
    var tmp = document.createElement('div');
    tmp.innerHTML = html || '';
    return (tmp.textContent || tmp.innerText || '').trim();
  }
 
  function construirTexto(data, idioma) {
    // Igual que hacía el PHP: si el idioma pedido existe como clave,
    // usar solo esa sección; si no, usar todas las que vengan.
    var secciones = data;
    if (data[idioma]) {
      secciones = {};
      secciones[idioma] = data[idioma];
    }
 
    var partes = [];
    Object.keys(secciones).forEach(function (idiomaSeccion) {
      var entradas = secciones[idiomaSeccion] || [];
      entradas.forEach(function (entrada) {
        if (entrada.partOfSpeech) {
          partes.push('[' + entrada.partOfSpeech + ']');
        }
        (entrada.definitions || []).forEach(function (def, i) {
          partes.push((i + 1) + '. ' + limpiarHtml(def.definition));
        });
      });
    });
 
    return partes.join('\n');
  }
 
  function buscarDefinicion() {
    var palabra = $('#palabra').val().trim();
    var idioma = $('#idioma').val();
 
    if (palabra === '') {
      $('#definicion').val('');
      return;
    }
 
    $('#definicion').val('Buscando...');
 
    var url = 'https://' + idioma + '.wiktionary.org/api/rest_v1/page/definition/' + encodeURIComponent(palabra);
 
    $.ajax({
      url: url,
      method: 'GET',
      dataType: 'json',
      timeout: 8000
    }).done(function (data) {
      var texto = construirTexto(data, idioma);
      $('#definicion').val(texto || 'No se encontraron definiciones.');
    }).fail(function (jqXHR) {
      if (jqXHR.status === 404) {
        $('#definicion').val('No se encontraron definiciones para "' + palabra + '".');
      } else {
        $('#definicion').val('Error al conectar con el servidor.');
      }
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


/*$(function () {

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

});*/
