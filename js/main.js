(function( $ ){

  $.fn.pickText = function( search, options ) {  

    var s = $.extend( {
      'open_tag'  : '<zz>',
      'close_tag' : '</zz>'
    }, options);

    return this.each(function() {

      var html = this.innerHTML;

      for(var i in search){
        var reg = new RegExp('('+search[i]+')', 'gim');
        html = html.replace( reg, s.open_tag+'$1'+s.close_tag);
      }

      this.innerHTML = html;

    });

  };

})( jQuery );





$( document ).ready(function() {

  // $('.row2').pickText(['steam','new','the']);

     $('.chedit').change(function() {
        if($(this).is(":checked")) {
            //console.log($(this).parent().html());
            $(this).siblings('.listitem').attr('contenteditable','true');
            $(this).siblings('.listitem').focus();
            
        } else {
          console.log('Down');
          var ngame = $(this).parent().find('.listitem').text();
          var igame = $(this).parent().find('.listitem').data('id');
          var tgame = $('#have-data-table').data('table');
          $(this).siblings('.listitem').removeAttr('contenteditable');
          console.log(ngame);
          $.post('lib/list_changer.php',
          {id: igame, name: ngame, table: tgame},
           function(data) {
            $('.result').html(data);
            console.log('Загрузка завершена.'+data);
          });

        }
        
    });



// скрипт для страницы STEAM
function getSteam (page) {
  $( "#message li:first" ).before( "<li>Начали парсить страницу "+page+"</li>" );
  $('.loading').addClass('inaction');
  $.post( "lib/steam.php?page="+page, function( data ) {
    $( "#message li:first" ).before( "<li>страница "+page+" сохранена в базе</li>" );
    if (page < data.pages) {
      getSteam(++page);
    }else{
      $('.loading').removeClass('inaction');
      $('.loading').html('Done!');
      console.dir(data);
      $( "#message li:first" ).before( "<li>или что-то пошло не так<br>"+data+"</li>" );
    }
  },'JSON');
} // getSteam()

$( "#get-steam" ).click(function() {
  getSteam(1);
  $(this).attr('disabled','true');
});
//================================
// скрипт для страницы STEAM List
function getSteamList (page,pages,mark) {
  $( "#message li:first" ).before( "<li>Начали парсить страницу "+page+"</li>" );
  $('.loading').addClass('inaction');
  $.post( "lib/slist.php?page="+page+"&pages="+pages+"&mark="+mark, function( data ) {
    //console.dir(data);
    $( "#message li:first" ).before( "<li>страница "+page+" сохранена в базе</li>" );
    if (page < data.pages) {
      getSteamList(++page,data.pages,data.mark);
    }else{
      $('.loading').removeClass('inaction');
      $('.loading').html('Done!');
      $( "#message li:first" ).before( "<li>или что-то пошло не так</li>" );
      console.dir(data);
    }
  },'JSON');
} // getSteamList()

$( "#get-slist" ).click(function() {
  getSteamList(1,0,0);
  $(this).attr('disabled','true');
});


// скрипт для страницы Ebay_prices
function getEbay_prices (start, end, scan) {
    $( "#message2 li:first" ).before( "<li>Начали парсить игры с "+start+" по "+end+"</li>" );
    $('.loading2').addClass('inaction');
    $.post( "lib/ebay_getprices.php",
     {ebay_getprices:'true',start:start,end:end,scan:scan},
      function( data ) {
      $( "#message2 li:first" ).before( "<li>игры "+start+"-"+end+" сохранены в базе</li>" );
      if($('#message2 li').length > 20) {
        $('#message2 li:last').remove();
        $('#message2 li:last').remove();
      }
      if (data.num > end) {
        getEbay_prices(start+10, end+10, data.scan);
      }else{
        console.log('Ebay done!');
        console.dir(data);
        $('.loading2').removeClass('inaction');
        $('.loading2').html('Ebay done!');
      }

    },'json');
}

$('#ebay_getprices').click(function() {
  getEbay_prices(1, 10, '0');
  $(this).attr('disabled','true');
});
//-----------------------------------------
function getEbay_purchaseHistory(itemids,ititle){

    $.post( "lib/ebay_getprices.php",
    {ebay_getpurhis:'true', itemid: itemids[0]},
    function( data ) {
      //console.dir(data);
      $('.loader').removeClass('sho');
      $('.innerinfo').addClass('sho');
      $('.weekSells').html(data.weekSells);
      var forChart1 = [['Date', 'Amount']];
      var forChart2 = [['Date', 'Price']];
      console.log('len = ', data.resTable.length);

      var i = 1;
      $.each(data.dayArr, function( day, amount ) {
        var dd = new Date(day*24*60*60*1000);
        var ddStr = dd.getDate()+'.'+(dd.getMonth()+1)+'.'+dd.getFullYear()
        forChart1[i++] = [dd, amount];
      }); // each()
      console.log('oldData = ');
      console.dir(data);

      $.each(data.resTable, function( x, value ) {
        $('.infotablebody').append(
            '<tr><td>'+
            ititle+
            '</td><td>'+
            value.curency+' '+
            value.price+
            '</td><td>'+
            value.amount+
            '</td><td>'+
            value.time+
            '</td></tr>');
        var dd = new Date(value.times*1000);
        var ddStr = dd.getDate()+'.'+(dd.getMonth()+1)+'.'+dd.getFullYear()
        forChart2[x+1] = [dd, value.price];
      }); // each()

      if(data.resTable.length) {
        ebayChart(forChart1, 1, 'Количество');
        ebayChart(forChart2, 2, 'Цена');
      }

      nextChart(data, itemids[1], 1);
      nextChart(data, itemids[2], 2);
      nextChart(data, itemids[3], 3);
      nextChart(data, itemids[4], 4);

    },'json');
    
} // getEbay_purchaseHistory()

$('.trr').on('click', function(e) {
  console.dir(e);
  e.stopPropagation();
  var itemids = $(this).parent().data('idarr');
  var ititle = $(this).parent().find('.tit').text();
  console.log('title = ',ititle);
  $('.alpha').append('<a target="_blank" href="http://offer.ebay.de/ws/eBayISAPI.dll?ViewBidsLogin&item='+itemids[0]+'"> | Страница продаж</a>').
  append('<a target="_blank" href="http://www.ebay.de/itm/'+itemids[0]+'"> | Страница товара</a>')
  //console.log(itemids);
  getEbay_purchaseHistory(itemids,ititle);
  $('.trig').addClass('sho');
  $('.loader').addClass('sho');
  $('.innerinfo').removeClass('sho');
});

$('.toclos').on('click',function() {
  $('.trig').removeClass('sho');
  $('.infotablebody').html('');
  $('.alpha').html('');
  $('#ebay_chart1').html('Нет данных для построения грфика');
  $('#ebay_chart2').html('Нет данных для построения грфика');
});

$('.tableitem').hover(
  function() {
    var ititle = $(this).attr('title');
    $('.inform').text(ititle);
  }, function() {
    $('.inform').text('');
  }
);
//===================================

    function drawChart(pappid) {

    $.ajax({
      method: "POST",
      url: "lib/chart-test.php",
      async: true,
      dataType: 'json',
      data: { post: "data", appid: pappid }
    })
      .done(function( msg ) {
        var arrdata = [];
        arrdata[0] = msg[0];
        for (var i = msg.length - 1; i > 0; i--) {
          arrdata[i] = [];
          arrdata[i][0]=msg[i]['title'];
          arrdata[i][1]=+msg[i]['price'];
        };
                // Create the data table.
        var data = google.visualization.arrayToDataTable(arrdata);

        console.dir(data);
            // Set chart options
        var options = {
          title: 'Измениния цен',
          hAxis: { titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0},
          colors: ['#366A73', '#1B2F4C']
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      });
    } // drawChart()

$(".res-item").click(function() {
  if (!$(this).hasClass("res-charted")){
    var jappid = $(this).data();
    console.log(jappid.appid);
    drawChart(jappid.appid);
    $('.res-charted').removeClass('res-charted');
    $(this).addClass('res-charted');
    var ab = $('#chart_div')
    $(this).append(ab);
  }



});

function formula (rurprice,exrate) {
  rurprice = parseFloat(rurprice);
  var res = ((rurprice/exrate)*1.0242+0.35)/((1-0.15)-0.019-0.08);
  if(res < 1.5) res = 1.5;

  return +res.toFixed(2);
}

$('.tch-table-deligator').on('click', '.row3, .row5, .row7', function(){

  if (!$(this).hasClass("slctd")){   
    $('.slctd').removeClass('slctd');
    $(this).addClass('slctd');
    var rurprice = $(this).text();
    var exrate = 0;
    if(localStorage["exrate"]) exrate = +localStorage["exrate"]; 
    var euro = formula(rurprice,exrate);
    console.log(euro);
    $('.slctd').attr('data-content', euro);
  }

});


$('#converter').keyup(function() {
  var rurprice = this.value;
  var exrate = $('#rateinp').val();
  var euro = formula (rurprice,exrate);
  $('.converter').html(euro);
});



function getPlatiRu (start, end, scan) {
    $( "#message1 li:first" ).before( "<li>Начали парсить игры с "+start+" по "+end+"</li>" );
    $.post( "lib/getjson.php",
     {getjson:'true',start:start,end:end,scan:scan},
      function( data ) {

      $( "#message1 li:first" ).before( "<li>игры "+start+"-"+end+" сохранены в базе</li>" );
      if($('#message1 li').length > 20) {
        $('#message1 li:last').remove();
        $('#message1 li:last').remove();
      }
      if (data.num > end) {
       getPlatiRu(start+10, end+10, data.scan);
      }else{
        console.log('PLati.ru done!');
        console.dir(data);
        $('.loading1').removeClass('inaction');
        $('.loading1').html('PLati.ru done!');
      }

    },'json');
}

$('#getjson').click(function() {
    $( "#message1 li:first" ).before( "<li>парсим список id gig-games</li>" );
    $('.loading1').addClass('inaction');
    $.post( "lib/getjson.php",
     {setGigGamesIds:'true'},
      function( data ) {
        $( "#message1 li:first" ).before( "<li>список id обновлен</li>" );
        getPlatiRu(1, 10, '0');
      },'json');
  
  $(this).attr('disabled','true');
});


$('.ch-tab').click(function() {
  $('.ch-tab').removeClass('active');
  $('.platitable').removeClass('visible in');
  $(this).addClass('active');
  var tab = $(this).data('tab');
  console.log(tab);
  $('#'+tab).addClass('visible in');
});


  var exrate = 0;
  if(localStorage["exrate"]) exrate = +localStorage["exrate"]; 
  $('#rateinp').val(exrate);

function setEuroColumn(exrate) {
  $('.euro tr').each(function( i ) {
    var price = $( this ).find('.row3').text();
    var euro = formula(price,+exrate);
    $( this ).find('.row9').html(euro);
  });
}
setEuroColumn(exrate);

$('#rateset').click(function(t) {
  t.preventDefault();
  exrate = $('#rateinp').val();
  localStorage.setItem("exrate", exrate);
  $('#rateset').html('saved');
  setTimeout("$('#rateset').html('set');", 1000);
  setEuroColumn (exrate);
});

// ===== change woo price =====
var GenObj = {
  js_modal_europrice : $('.js-modal-europrice'),
  js_tch_deligator : $('#js-tch-deligator')
};

var WooObj = {
  modal_woo_title : $('.modal-woo-title'),
  js_modal_woo_price : $('#js-modal-woo-price'),
  woo_change_price : $('#woo-change-price'),
  woo_remove : $('#woo-remove'),
  wooModal : $('#wooModal'),
  js_woo_id_input_holder : $('.js-woo-id-input-holder'),
  woo_item_id_input : $('#woo-item-id-input'),
  woo_item_price_input : $('#woo-item-price-input')
};

  GenObj.js_tch_deligator.on('click', '.tc-woo', function (e) {
    e.preventDefault();
    WooObj.modal_woo_title.html('<img src="images/more-loading.gif" alt="loading">');
    WooObj.js_modal_woo_price.text('.');
    WooObj.woo_change_price.attr('disabled', true);
    WooObj.woo_remove.attr('disabled', false);
    WooObj.wooModal.modal('show');
    WooObj.tr = tr = $(this).parent().parent();
    WooObj.gameId = tr.attr('data-gameid');
    WooObj.wooId = tr.attr('data-wooid');
    WooObj.rurprice = +tr.find('.row5').text();
    var exrate = 0;
    if(localStorage["exrate"]) exrate = +localStorage["exrate"]; 
    WooObj.europrice = formula(WooObj.rurprice, exrate).toFixed(1);
    if(WooObj.europrice < 1.5) WooObj.europrice = 1.5;
    WooObj.woo_item_id_input.val(WooObj.wooId);
    WooObj.js_woo_id_input_holder.removeClass('has-error');
    if(!WooObj.wooId){
      WooObj.js_woo_id_input_holder.addClass('has-error');
    }
    $('.modal-plati-title').text(tr.find('.row5').attr('title'));
    $('.modal-parser-title').text(tr.find('.row2').text());
    GenObj.js_modal_europrice.text('€'+WooObj.europrice+'0');
    WooObj.woo_item_price_input.val(WooObj.europrice);
    if(!WooObj.wooId) return false;
    $.post('ajax.php?action=ajax-woo',
      { action:'check', wooId:WooObj.wooId, gameId:WooObj.gameId },
      function (data) {
        WooObj.modal_woo_title.text(data.woo_title);
        WooObj.js_modal_woo_price.text(data.price);
        if (data.answer === 'good') WooObj.woo_change_price.attr('disabled', false);
    }, 'json');
  });

  WooObj.wooModal.on('shown.bs.modal', function () {
    if(!WooObj.wooId) WooObj.woo_item_id_input.focus();
  });

  $('#woo-check-form').on('submit',function (e) {

    e.preventDefault();
    var wooId = +WooObj.woo_item_id_input.val();
    
    if(!wooId) return false;
    $.post('ajax.php?action=ajax-woo',
      { action:'check', wooId:wooId, gameId:WooObj.gameId, update:'true' },
      function (data) {
        if (data.answer === 'good') {
          WooObj.wooId = wooId;
          WooObj.js_woo_id_input_holder.removeClass('has-error');
          $('tr[data-gameid='+WooObj.gameId+']').data('wooid', wooId);
          WooObj.woo_change_price.attr('disabled', false);
          WooObj.js_modal_woo_price.text(data.price);
        }
        WooObj.modal_woo_title.text(data.woo_title);
    }, 'json');
  });

  $('#woo-change-form').on('submit', function (e) {
    e.preventDefault();
    if(!WooObj.wooId || !WooObj.europrice) return false;
    WooObj.woo_change_price.attr('disabled', true);
    WooObj.woo_remove.attr('disabled', true);
    var europrice = parseFloat(WooObj.woo_item_price_input.val().replace(',','.'));
    $.post('ajax.php?action=ajax-woo',
      { action:'change', wooId:WooObj.wooId, price:europrice },
      function (data) {
        if (data.answer == 'good') {
          WooObj.wooModal.modal('hide');
          WooObj.tr.find('.tc-woo').parent().addClass('pchanged');
        }
    }, 'json');
  });

  WooObj.woo_remove.on('click', function () {
    if(!WooObj.wooId) return false;
    WooObj.woo_change_price.attr('disabled', true);
    WooObj.woo_remove.attr('disabled', true);
    $.post('ajax.php?action=ajax-woo',
      { action:'remove', wooId:WooObj.wooId },
      function (data) {
        if (data.answer == 'good') {
          WooObj.wooModal.modal('hide');
          WooObj.tr.find('.tc-woo').parent().addClass('pchanged');
        }
    }, 'json');
  });

// ===== /change woo price =====



// ===== change ebay price =====
var EbayObj = {
  js_modal_ebay_title : $('.js-modal-ebay-title'),
  js_modal_ebay_price : $('#js-modal-ebay-price'),
  js_ebay_change_price : $('#js-ebay-change-price'),
  js_ebay_remove : $('#js-ebay-remove'),
  ebayModal : $('#ebayModal'),
  js_ebay_item_id_input : $('#js-ebay-item-id-input'),
  js_ebay_id_input_holder : $('.js-ebay-id-input-holder')
};
  GenObj.js_tch_deligator.on('click', '.tc-ebay', function (e) {
    e.preventDefault();
    EbayObj.js_modal_ebay_title.html('<img src="images/more-loading.gif" alt="loading">');
    EbayObj.js_modal_ebay_price.text('.');
    EbayObj.js_ebay_change_price.attr('disabled', true);
    EbayObj.js_ebay_remove.attr('disabled', false);
    EbayObj.ebayModal.modal('show');
    EbayObj.tr = tr = $(this).parent().parent();
    EbayObj.gameId = tr.attr('data-gameid');
    EbayObj.ebayId = tr.attr('data-ebayid');
    EbayObj.rurprice = +tr.find('.row5').text();
    var exrate = 0;
    if(localStorage["exrate"]) exrate = +localStorage["exrate"]; 
    EbayObj.europrice = formula(EbayObj.rurprice, exrate).toFixed(1);
    if(EbayObj.europrice < 1.5) EbayObj.europrice = 1.5;
    EbayObj.js_ebay_item_id_input.val(EbayObj.ebayId);
    EbayObj.js_ebay_id_input_holder.removeClass('has-error');
    if(!EbayObj.ebayId){
      EbayObj.js_ebay_id_input_holder.addClass('has-error');
    }
    $('.modal-plati-title').text(tr.find('.row5').attr('title'));
    $('.modal-parser-title').text(tr.find('.row2').text());
    GenObj.js_modal_europrice.text('€'+EbayObj.europrice+'0');
    $('#js-ebay-item-price-input').val(EbayObj.europrice);
    if(!EbayObj.ebayId) return false;
    $.post('ajax.php?action=ajax-ebay-api-price-changer',
      { action:'check', ebayId:EbayObj.ebayId, gameId:EbayObj.gameId },
      function (data) {
        EbayObj.js_modal_ebay_title.text(data.ebay_title);
        EbayObj.js_modal_ebay_price.text(data.price);
        if (data.answer === 'good') EbayObj.js_ebay_change_price.attr('disabled', false);
    }, 'json');
  });

  EbayObj.ebayModal.on('shown.bs.modal', function () {
    if(!EbayObj.ebayId) EbayObj.js_ebay_item_id_input.focus();
  });

  $('#js-ebay-check-form').on('submit',function (e) {

    e.preventDefault();
    var ebayId = +EbayObj.js_ebay_item_id_input.val();
    
    if(!ebayId) return false;
    $.post('ajax.php?action=ajax-ebay-api-price-changer',
      { action:'check', ebayId:ebayId, gameId:EbayObj.gameId, update:'true' },
      function (data) {
        if (data.answer === 'good') {
          EbayObj.ebayId = ebayId;
          EbayObj.js_ebay_id_input_holder.removeClass('has-error');
          $('tr[data-gameid='+EbayObj.gameId+']').data('ebayid', ebayId);
          EbayObj.js_ebay_change_price.attr('disabled', false);
          EbayObj.js_modal_ebay_price.text(data.price);
        }
        EbayObj.js_modal_ebay_title.text(data.ebay_title);
    }, 'json');
  });

  $('#js-ebay-change-form').on('submit', function (e) {
    e.preventDefault();
    if(!EbayObj.ebayId || !EbayObj.europrice) return false;
    EbayObj.js_ebay_change_price.attr('disabled', true);
    EbayObj.js_ebay_remove.attr('disabled', true);
    var europrice = parseFloat($('#js-ebay-item-price-input').val().replace(',','.'));
    $.post('ajax.php?action=ajax-ebay-api-price-changer',
      { action:'change', ebayId:EbayObj.ebayId, price:europrice },
      function (data) {
        if (data.answer == 'good') {
          EbayObj.ebayModal.modal('hide');
          EbayObj.tr.find('.tc-ebay').parent().addClass('pchanged');
        }
    }, 'json');
  });

  EbayObj.js_ebay_remove.on('click', function () {
    if(!EbayObj.ebayId) return false;
    EbayObj.js_ebay_change_price.attr('disabled', true);
    EbayObj.js_ebay_remove.attr('disabled', true);
    $.post('ajax.php?action=ajax-ebay-api-price-changer',
      { action:'remove', ebayId:EbayObj.ebayId },
      function (data) {
        if (data.answer == 'good') {
          EbayObj.ebayModal.modal('hide');
          EbayObj.tr.find('.tc-ebay').parent().addClass('pchanged');
        }
    }, 'json');
  });

// ===== /change ebay price =====




// ========= change price merged ===========

var FF = {
  mergedModal : $('#mergedModal'),
  js_modal_ebay_title : $('.frow2>.fcol2'),
  js_modal_ebay_price : $('.frow2>.fcol3>b'),
  js_modal_ebay_input : $('#js-fEprice'),
  js_modal_woo_title :  $('.frow3>.fcol2'),
  js_modal_woo_price : $('.frow3>.fcol3>b'),
  js_modal_woo_input : $('#js-fWprice'),
  js_modal_parser_title : $('.frow1>.fcol2'),
  js_modal_plati_title : $('.frow4>.fcol2 .jsm-plati-title'),
  js_modal_plati_price : $('.frow4>.fcol4'),
  js_modal_ebay_prices : $('.frow1>.fcol3 tr'),
  consec : 1,
  consec_out : $('#consec'),
};

// стрелочки
$('.jsm-arr').click(function(e) {

  e.preventDefault();
  if(e.target.id == 'arrleft'){
    FF.consec -= 1;
    if(FF.consec < 1) FF.consec = 3;
  }

  if(e.target.id == 'arrright'){
    FF.consec += 1;
    if(FF.consec > 3) FF.consec = 1;
  }

  FF.rurprice = +FF.game_line['item'+FF.consec+'_price'];
  FF.js_modal_plati_title.text(FF.game_line['item'+FF.consec+'_name']).pickText(['free','row']);
  FF.js_modal_plati_title.attr('href', 'http://www.plati.ru/itm/'+FF.game_line['item'+FF.consec+'_id']);
  FF.js_modal_ebay_input.val(FF['europrice'+FF.consec]);
  FF.js_modal_woo_input.val((FF['europrice'+FF.consec]*0.95).toFixed(2));

  $('.tch-smalltable .gig').removeClass('gig');
  $('.rp'+FF.consec).addClass('gig');
  FF.consec_out.text(FF.consec);

});


// Вызов модального окна Merged Price Changer
GenObj.js_tch_deligator.on('click', '.tch-merged', {f:FF}, function(e) {
  
  FF.consec = 1;
  FF.consec_out.text(FF.consec);
  FF.game_line = {};
  FF.europrice1 = 0;
  FF.europrice2 = 0;
  FF.europrice3 = 0;
  var F = e.data.f
  F.one_removed = false;
  F.one_changed = false;
  F.mergedModal.modal('show');
  F.tr = $(this).parent();
  F.gameId = FF.gameId = F.tr.attr('data-gameid');
  F.ebayId = F.tr.attr('data-ebayid');
  F.wooId = F.tr.attr('data-wooid');
  F.rurprice = +F.tr.find('.row5').text();
  var exrate = 0;
  if(localStorage["exrate"]) exrate = +localStorage["exrate"]; 
  F.europrice = formula(F.rurprice, exrate);

  if(F.europrice < 1.5) F.europrice = 1.5;
  F.js_modal_plati_title.text(F.tr.find('.row5').attr('title')).pickText(['free','row']);
  F.js_modal_plati_title.attr('href', F.tr.find('.row8 a').attr('href'));
  F.js_modal_parser_title.text(F.tr.find('.row2').text());
  F.js_modal_ebay_title.html('<img src="images/more-loading.gif" alt="loading">');
  F.js_modal_ebay_price.text('.');
  F.js_modal_ebay_input.val(F.europrice);
  F.js_modal_woo_title.html('<img src="images/more-loading.gif" alt="loading">');
  F.js_modal_woo_price.text('.');
  F.js_modal_woo_input.val((F.europrice*0.95).toFixed(2));
  F.js_modal_ebay_prices.html(F.tr.find('td[iid]').clone());
  F.js_modal_plati_price.html('<img src="images/more-loading.gif" alt="loading">');

  if(F.ebayId){
    $.post('ajax.php?action=ajax-ebay-api-price-changer',
      { action:'check', ebayId:F.ebayId, gameId:F.gameId },
      function (data) {
        FF.game_line = data.game_line;
        FF.europrice1 = formula(FF.game_line.item1_price, exrate);
        FF.europrice2 = formula(FF.game_line.item2_price, exrate);
        FF.europrice3 = formula(FF.game_line.item3_price, exrate);
        var rur_prices = 
          '<table class="tch-smalltable"><tr>'+
            '<td class="rp1 gig">'+FF.game_line.item1_price+'</td>'+
            '<td class="rp2">'+FF.game_line.item2_price+'</td>'+
            '<td class="rp3">'+FF.game_line.item3_price+'</td>'+
          '</tr></table>';
        F.js_modal_plati_price.html(rur_prices);
        F.js_modal_ebay_title.text(data.ebay_title);
        F.js_modal_ebay_price.text(data.price);
        // if (data.answer === 'good') F.'????'.attr('disabled', false);
    }, 'json');
  }

  if(F.wooId){
    $.post('ajax.php?action=ajax-woo',
      { action:'check', wooId:F.wooId, gameId:F.gameId },
      function (data) {
        F.js_modal_woo_title.text(data.woo_title);
        F.js_modal_woo_price.text(data.price);
        // if (data.answer === 'good') WooObj.woo_change_price.attr('disabled', false);
    }, 'json');
  }

});

// Изменение инпута WooCommerce
FF.js_modal_ebay_input.on('change', function() {
  FF.js_modal_woo_input.val((parseFloat(FF.js_modal_ebay_input.val().replace(',','.'))*0.95).toFixed(2));
})

// Изменение цен
$('#fChange').on('submit', {f:FF}, function(e) {
  
  e.preventDefault();

  var F = e.data.f
  var fEprice = F.js_modal_ebay_input.val().replace(',','.');
  var fWprice = F.js_modal_woo_input.val().replace(',','.');

  if(F.ebayId && FF.rurprice){
    $.post('ajax.php?action=ajax-ebay-api-price-changer',
      { action: 'change', ebayId: F.ebayId, price: fEprice },
      function (data) {
        if (data.answer == 'good') {
          if(F.one_changed) F.mergedModal.modal('hide');
          else F.one_changed = true;
          F.tr.find('.tc-ebay').parent().addClass('pchanged');
        }else{

        }
    }, 'json');
  }else{
    F.one_changed = true;
  }

  if(F.wooId && FF.rurprice){
    $.post('ajax.php?action=ajax-woo',
      { action: 'change', wooId: F.wooId, price: fWprice },
      function (data) {
        if (data.answer == 'good') {
          if(F.one_changed) F.mergedModal.modal('hide');
          else F.one_changed = true;
          F.tr.find('.tc-woo').parent().addClass('pchanged');
        }else{

        }
    }, 'json');
  }else{
    F.one_changed = true;
  }
  
});

// Удаление товаров с продажи
$('#fRemove').on('click', {f:FF}, function(e) {
  
  e.preventDefault();

  var F = e.data.f
  if(F.ebayId){
    $.post('ajax.php?action=ajax-ebay-api-price-changer',
      { action:'remove', ebayId:F.ebayId },
      function (data) {
        if (data.answer == 'good') {
          if(F.one_removed) F.mergedModal.modal('hide');
          else F.one_removed = true;
        }else{

        }
    }, 'json');
  }else{
    F.one_removed = true;
  }

  if(F.wooId){
      $.post('ajax.php?action=ajax-woo',
      { action:'remove', wooId:F.wooId },
      function (data) {
        if (data.answer == 'good') {
          if(F.one_removed) F.mergedModal.modal('hide');
          else F.one_removed = true;
        }else{

        }
    }, 'json');
  }else{
    F.one_removed = true;
  }

});

function toBlack(plati_id) {
  $.post('ajax.php?action=ajax-woo',
    {action:'ban',plati_id:plati_id});
}

$('#fBlacklist').on('click', function() {
  if (!confirm("Уверен?")) return false;
  toBlack(FF.game_line['item'+FF.consec+'_id']);
});

$('#fBanaddon').on('click', function() {
  if (!confirm("Баним эту игру?")) return false;
  $.post('ajax.php?action=ajax-woo',
    {action : 'banaddon',
    plati_id : FF.game_line['item'+FF.consec+'_id'],
    game_name : FF.game_line['item'+FF.consec+'_name'],
    game_id : FF.gameId,
  });
});

// ========= /change price merged ===========





// =========2 change price merged 2===========

GenObj.js_tch_deligator.on('click', '.mChange', function(e) {

  e.preventDefault();

  if($(this).hasClass('color-gray')) return false;
  $(this).addClass('color-gray');
  var M = {};
  M.tr = tr = $(this).parent().parent();
  M.gameId = tr.attr('data-gameid');
  M.ebayId = tr.attr('data-ebayid');
  M.wooId = tr.attr('data-wooid');
  M.rurprice = +tr.find('.row5').text();
  var exrate = 0;
  if(localStorage["exrate"]) exrate = +localStorage["exrate"]; 
  M.europrice = formula(M.rurprice, exrate).toFixed(1);
  if(M.europrice < 1.5) M.europrice = 1.5;

  if(M.ebayId && M.rurprice){
    $.post('ajax.php?action=ajax-ebay-api-price-changer',
      { action: 'change', ebayId: M.ebayId, price: M.europrice },
      function (data) {
        if (data.answer == 'good') {
          M.tr.find('.tc-ebay').addClass('color-green');
        }else{
          M.tr.find('.tc-ebay').addClass('color-red');
        }
    }, 'json');
  }

  if(M.wooId && M.rurprice){
    $.post('ajax.php?action=ajax-woo',
      { action: 'change', wooId: M.wooId, price: M.europrice },
      function (data) {
        if (data.answer == 'good') {
          M.tr.find('.tc-woo').addClass('color-green');
        }else{
          M.tr.find('.tc-woo').addClass('color-red');
        }
    }, 'json');
  }

});


GenObj.js_tch_deligator.on('click', '.mRemove', function(e) {

  e.preventDefault();

  if($(this).hasClass('color-gray')) return false;
  $(this).addClass('color-gray');
  var M = {};
  M.tr = tr = $(this).parent().parent();
  M.gameId = tr.attr('data-gameid');
  M.ebayId = tr.attr('data-ebayid');
  M.wooId = tr.attr('data-wooid');

  if(M.ebayId){
    $.post('ajax.php?action=ajax-ebay-api-price-changer',
      { action:'remove', ebayId:M.ebayId },
      function (data) {
        if (data.answer == 'good') {
          M.tr.find('.tc-ebay').addClass('color-green');
        }else{
          M.tr.find('.tc-ebay').addClass('color-red');
        }
    }, 'json');
  }

  if(M.wooId){
      $.post('ajax.php?action=ajax-woo',
      { action:'remove', wooId:M.wooId },
      function (data) {
        if (data.answer == 'good') {
          M.tr.find('.tc-woo').addClass('color-green');
        }else{
          M.tr.find('.tc-woo').addClass('color-red');
        }
    }, 'json');
  }
});

// =========2 /change price merged 2===========
var ajax_loader = $('.ajax-loader');
  ajax_loader.removeClass('ajaxed');
$( document ).ajaxSend(function() {
  ajax_loader.addClass('ajaxed');
});

$( document ).ajaxStop(function() {
  ajax_loader.removeClass('ajaxed');
});

}); //document ready

// update games set ebay_id=null where id=4168
