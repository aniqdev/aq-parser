var park = {};

function changeBgColor (c) {
  var colors = {'c1':['#ff8080','#ffff80','#80ff80','#80ffff','#8080ff','#ff80c0','#8080c0','#f4d10b','#cdcdcd','#b87df2',"#F57491","#FFA053","#79C1F2"],
                'c2':["#ff8080","#ffffae","#bae699","#fadcc9","#b0b0ff","#9cebf5","#d8bae2","#fcce96","#b6e4dc","#f8c0e1","#F57491","#FFA053","#D8FAC9"]};

  $('.park-col-inpt').each(function(i) {
    $(this).val(colors[c][i]);
    var id = $(this).attr('id');
    $('.'+id).css('background-color', colors[c][i]);
  });
}
changeBgColor('c2');

$('input[name=colors]').on('change', function () {
  changeBgColor($(this).val());
});

$('.park-col-inpt').on('change', function () {
  var color = $(this).val();
  var id = $(this).attr('id');
  $('.'+id).css('background-color', color);
});

$('#park-pars').on('click', function () {
  $(this).attr('disabled', 'disabled');
  $(this).addClass('parkactive');
  $.get( "lib/park-getter.php?park=true", function() {
    location.reload();
  });
});

$('#park-pars-airport').on('click', function () {
  $(this).attr('disabled', 'disabled');
  $(this).addClass('parkactive');
  $.get( "lib/park-getter-airport.php?park=true", function() {
    location.reload();
  });
});

$('#range').on('change', function () {
  var r = $(this).val();
  var a = $('<a/>', {text:'['+r+']',href: '/index.php?action=park-table-airport&limit='+r});
  $('.range-go').html(a);
});