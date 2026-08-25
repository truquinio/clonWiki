$('#palabra').change(function () {

  //console.log('palabra');

  $.post("./php/basededatos.php",
    {
      modo: "get",
      palabra: $("#palabra").val(),
    },
    function (data) {
      $("#definicion").val(data.definicion);
    },
    "json"
  );
});

//----------------------------------------------------------------

$("#definicion").change(function () {

  //console.log("definicion");

  $.post("./php/basededatos.php",
    {
      modo: "set",
      palabra: $("#palabra").val(),
      definicion: $("#definicion").val()
    },
    function (data) {
      //$("#definicion").html(data.definicion);
    },
    "json"
  );
});