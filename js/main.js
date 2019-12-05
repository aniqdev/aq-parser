(function( $ ){

	$.fn.pickText = function( search, options ) { 

		var s = $.extend( {
			'open_tag'  : '<zz>',
			'close_tag' : '</zz>'
		}, options);

		if(!Array.isArray(search)) search = [search];

		return this.each(function() {

			var html = this.innerHTML;

			for(var i in search){
				// var reg = new RegExp('('+search[i]+')', 'gim');
				// html = html.replace( reg, s.open_tag+'$1'+s.close_tag);
				html = html.replace( search[i], s.open_tag+search[i]+s.close_tag);
			}

			this.innerHTML = html;

		});

	};

})( jQuery );

function log(a) {
	return console.log(a);
}

function dir(a) {
	return console.dir(a);
}

function round_hood_price(prc) {
	var z = {0:'0',1:'0',2:'0',3:'5',4:'5',
					 5:'5',6:'5',7:'5',8:'9',9:'9'};
	prc = ((prc < 5) ? (prc-0.05) : (prc*0.99)).toFixed(2);
	return prc.slice(0,prc.length-1) + z[prc.slice(-1)];
}

function ajax_woo_url() {
	// return 'https://hot-body.net/parser/ajax.php?action=ajax-woo';
	return 'https://parser.gig-games.de/ajax.php?action=ajax-woo';
}


$( document ).ready(function() {

 var dataex = $('#rateset').attr('dataex');
 if (dataex) {
	localStorage.setItem("exrate", dataex);
 }

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

// скрипт для страницы STEAM2
function getSteam2 (offset, script, table) {
	$( "#message li:first" ).before( "<li>Начали парсить offset "+offset+"</li>" );
	$('.loading').addClass('inaction');
	$.post( "/ajax.php?action="+script+"&offset="+offset,
				{offset:offset,table:table},
				function( data ) {
		$( "#message li:first" ).before( "<li>offset "+offset+" сохранена в базе</li>" );
		if($('#message li').length > 200) {
			$('#message li:last').remove();
			$('#message li:last').remove();
		}
		if (script === 'ajax-steam') offset = +offset+10;
		else offset = +offset+30;
		if (offset < data.count) {
			getSteam2(offset, script, table);
		}else{
			$('.loading').removeClass('inaction');
			$('.loading').html('Done!');
			console.dir(data);
			$( "#message li:first" ).before( "<li>или что-то пошло не так</li>" );
		}
	},'JSON');
} // getSteam2()

$( "#get-steam2" ).click(function() {
	getSteam2(0, 'steam2');
	$('.get-steam-btn').attr('disabled','true');
});

$( "#get-steam3" ).click(function() {
	getSteam2(0, 'steam3');
	$('.get-steam-btn').attr('disabled','true');
});

$('.js-get-steam2').click(function() {
	// this.value содержит имя таблицы MySQL
	var offset = $('#offset').val();
	getSteam2(offset, 'ajax-steam', this.value);
	$('.get-steam-btn').attr('disabled','true');
});
//================================

// скрипт для страницы STEAM List
function getSteamList (page,pages,scan) {
	$( "#message li:first" ).before( "<li>Начали парсить страницу "+page+"</li>" );
	$('.loading').addClass('inaction');
	$.post( "ajax.php?action=ajax-slist&page="+page+"&pages="+pages+"&scan="+scan, function( data ) {
		//console.dir(data);
		$( "#message li:first" ).before( "<li>страница "+page+" сохранена в базе</li>" );
		if (page < data.pages) {
			getSteamList(++page,data.pages,data.scan);
		}else{
			$('.loading').removeClass('inaction');
			$('.loading').html('Done!');
			$( "#message li:first" ).before( "<li>или что-то пошло не так</li>" );
			console.dir(data);
		}
	},'JSON');
} // getSteamList()

$( "#get-slist" ).click(function() {
	getSteamList('1','0','0');
	$(this).attr('disabled','true');
});


// скрипт для страницы Ebay_prices_multi
function getEbay_prices_m (start, end, scan) {
		$( "#message2 li:first" ).before( "<li>Начали парсить игры с "+start+" по "+end+"</li>" );
		$('.loading2').addClass('inaction');
		$.post( "lib/ebay_getprices_multi.php",
		 {ebay_getprices:'true',start:start,end:end,scan:scan},
			function( data ) {
			$( "#message2 li:first" ).before( "<li>игры "+start+"-"+end+" сохранены в базе</li>" );
			if($('#message2 li').length > 20) {
				$('#message2 li:last').remove();
				$('#message2 li:last').remove();
			}
			if (data.num > end) {
				getEbay_prices_m(start+10, end+10, data.scan);
			}else{
				console.log('Ebay done!');
				console.dir(data);
				$('.loading2').removeClass('inaction');
				$('.loading2').html('Ebay done!');
			}

		},'json');
}

$('#ebay_getprices_multi').click(function() {
	getEbay_prices_m(1, 10, '0');
	$('.ebay_getprices').attr('disabled','true');
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
	var res = ((rurprice/exrate)*1.00952+0.4165)/(1-(0.1+0.019+0.078)*1.19);
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
	$('#getjson').attr('disabled','true');
	$('#getjson_multi').attr('disabled','true');
	$('#getjson_multi3').attr('disabled','true');
	$( "#message1 li:first" ).before( "<li>парсим список id gig-games</li>" );
	$('.loading1').addClass('inaction');
	$.post( "lib/getjson.php",
	 {setGigGamesIds:'true'},
		function( data ) {
			$( "#message1 li:first" ).before( "<li>список id обновлен</li>" );
			getPlatiRu(1, 10, '0');
		},'json');
	
});



function getPlatiRu_m (start, end, scan, count) {
	$( "#message1 li:first" ).before( "<li>Начали парсить игры с "+start+" по "+end+"</li>" );
	$.post( "lib/getjson-multi2.php",
	 {getjson:'true',start:start,end:end,scan:scan},
		function( data ) {

			$( "#message1 li:first" ).before( "<li>игры "+start+"-"+end+" сохранены в базе</li>" );
			if($('#message1 li').length > 20) {
				$('#message1 li:last').remove();
				$('#message1 li:last').remove();
			}
			if (data.num > end) {
			 getPlatiRu_m(start+10, end+10, data.scan, data.num);
			}else{
				console.log('PLati.ru done!');
				console.dir(data);
				$('.loading1').removeClass('inaction');
				$('.loading1').html('PLati.ru done!');
			}

	},'json');
}

$('#getjson_multi').click(function() {
		$('#getjson').attr('disabled','true');
		$('#getjson_multi').attr('disabled','true');
		$('#getjson_multi3').attr('disabled','true');
		$('.loading1').addClass('inaction');
		getPlatiRu_m(1, 10, '0', 0);
});


function getPlatiRu_m3 (start, end, scan, count) {
	$( "#message1 li:first" ).before( "<li>Начали парсить игры с "+start+" по "+end+"</li>" );
	$.post( "ajax.php?action=getjson-multi3",
	 {getjson:'true',start:start,end:end,scan:scan},
		function( data ) {

			$( "#message1 li:first" ).before( "<li>игры "+start+"-"+end+" сохранены в базе</li>" );
			if($('#message1 li').length > 20) {
				$('#message1 li:last').remove();
				$('#message1 li:last').remove();
			}
			if (data.num > end) {
			 getPlatiRu_m3(start+10, end+10, data.scan, data.num);
			}else{
				console.log('PLati.ru done!');
				console.dir(data);
				$('.loading1').removeClass('inaction');
				$('.loading1').html('PLati.ru done!');
			}

	},'json');
}

$('#getjson_multi3').click(function() {
		$('#getjson').attr('disabled','true');
		$('#getjson_multi').attr('disabled','true');
		$('#getjson_multi3').attr('disabled','true');
		$('.loading1').addClass('inaction');
		getPlatiRu_m3(1, 10, '0', 0);
});


function getPlatiRu_steam (start, end, scan) {
	$( "#message1 li:first" ).before( "<li>Начали парсить игры с "+start+" по "+end+"</li>" );
	$.post( "ajax.php?action=getjson-steam-de",
	 {getjson:'true', start:start, end:end, scan:scan},
		function( data ) {

			$( "#message1 li:first" ).before( "<li>игры "+start+"-"+end+" сохранены в базе</li>" );
			if($('#message1 li').length > 20) {
				$('#message1 li:last').remove();
				$('#message1 li:last').remove();
			}
			if (data.count > end) {
			 getPlatiRu_steam(start+10, end+10, data.scan);
			}else{
				console.log('PLati.ru done!');
				console.dir(data);
				$('.loading1').removeClass('inaction');
				$('.loading1').html('PLati.ru done!');
			}

	},'json');
}

$('#getsteam_multi').click(function() {
		$(this).attr('disabled','true');
		$('.loading1').addClass('inaction');
		getPlatiRu_steam(1, 10, '0', 0);
});

// $('.ch-tab').click(function() {
// 	$('.ch-tab').removeClass('active');
// 	$('.platitable').removeClass('visible in');
// 	$(this).addClass('active');
// 	var tab = $(this).data('tab');
// 	console.log(tab);
// 	$('#'+tab).addClass('visible in');
// });


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
	$.post('ajax.php?action=ajax-woo', { action:'change_exrate', exrate:exrate });
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
		$.post(ajax_woo_url(),
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
		$.post(ajax_woo_url(),
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
		$.post(ajax_woo_url(),
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
		$.post(ajax_woo_url(),
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



// ========= HOOD MODAL =========
var HoodObj = {
	hoodModal:$('#hoodModal'),
	modal_hood_title: $('#modal-hood-title'),
	modal_hood_price: $('#modal-hood-price'),
	hood_check_form: $('#hood-check-form'),
	hood_item_id_input: $('#hood-item-id-input'),
	hood_id_input_holder: $('.js-hood-id-input-holder'),
};
// open modal
GenObj.js_tch_deligator.on('click', '.tc-hood', function(e){

	var tr = $(this).parent().parent();
	var hood_id = tr.attr('data-hoodid');
	HoodObj.ebay_id = tr.attr('data-ebayid');
	// console.log(hood_id);
	HoodObj.hood_item_id_input[0].value = hood_id;
	HoodObj.hoodModal.modal('show');
	HoodObj.modal_hood_title.html('<img src="images/more-loading.gif" alt="loading">');

	if (hood_id) {
		HoodObj.hood_id_input_holder.removeClass('has-error');
		HoodObj.hood_id_input_holder.addClass('has-success');
		$.post('/ajax.php?action=ajax-hood',
			{hood_get_price:'true',
			 hood_id:hood_id},function (data) {
			 	if (data.status === 'success') {
			 		HoodObj.modal_hood_price.html(data.price);
			 		HoodObj.modal_hood_title.html(data.title);
			 	}else{
			 		HoodObj.modal_hood_title.html(data.error);
			 	}
			 },'json');
	}else{
		HoodObj.hood_id_input_holder.removeClass('has-success');
		HoodObj.hood_id_input_holder.addClass('has-error');
	}
});
// check id
$('#hood-check').on('click', (e) => {
	e.preventDefault();
  var inp_val = HoodObj.hood_item_id_input[0].value;
  console.log(inp_val);
	$.post('/ajax.php?action=ajax-hood',
			{hood_get_price: 'true',
			 hood_check: 'true',
			 ebay_id: HoodObj.ebay_id,
		   hood_id: inp_val},function (data) {
			 	if (data.status === 'success') {
			 		HoodObj.modal_hood_price.html(data.price);
			 		HoodObj.modal_hood_title.html(data.title);
			 	}else{
			 		HoodObj.modal_hood_title.html(data.error);
			 	}
			 },'json');
});
// ========= /HOOD MODAL =========


// ========= change price merged ===========
var FF = {
	mergedModal : $('#mergedModal'),
	js_modal_ebay_title : $('.frow2>.fcol2'),
	js_modal_ebay_price : $('.frow2>.fcol3>b'),
	js_modal_ebay_input : $('#js-fEprice'),
	js_modal_woo_title :  $('.frow3>.fcol2'),
	js_modal_woo_price : $('.frow3>.fcol3>b'),
	js_modal_woo_input : $('#js-fWprice'),
	js_modal_hood_title :  $('.frow5>.fcol2'),
	js_modal_hood_price : $('.frow5>.fcol3>b'),
	js_modal_hood_input : $('#js-fHprice'),
	js_modal_parser_title : $('.frow1>.fcol2'),
	js_modal_plati_title : $('.frow4>.fcol2 .jsm-plati-title'),
	js_modal_plati_price : $('.frow4>.fcol4'),
	js_modal_ebay_prices : $('#js_modal_ebay_prices'),
	js_modal_buy_button : $('#fBuyItem'), // форма
	js_modal_ebay_link : $('#m-ebli'),
	js_csrf_buy_time_input : $('#csrf-buy-time'),
	js_modal_black_white : $('#js_modal_black_white'),
	consec : 1,
	consec_out : $('#consec'),

	draw_ebay_names:function(ebay_prices_tds) {
		var ebay_prices_names = '';
		ebay_prices_tds.each(function() {
		  ebay_prices_names += '<div class="'+($(this).hasClass('gig')?'gig':'')+'"><i lang="white" id="'+$(this).attr('iid')+'" class="ok glyphicon glyphicon-ok-circle" title="add to white list"></i> '+
		  	'<i lang="black" id="'+$(this).attr('iid')+
		  		'" class="rem glyphicon glyphicon-remove-circle" title="add to black list"></i> '+
		  		this.title+
		  		' <b>'+$(this).text()+'</b>'+
		  	'</div>';
		});
		FF.js_modal_black_white.html(ebay_prices_names);
	},

	reload_ebay_names:function(tds_str) {
		FF.js_modal_ebay_prices.html(tds_str);
		var ebay_prices_tds = FF.js_modal_ebay_prices.find('td');
		FF.draw_ebay_names(ebay_prices_tds);
	}
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
	FF.js_modal_plati_title.attr('href', 'http://www.plati.ru/itm/'+FF.game_line['item'+FF.consec+'_id']+'?ai=163508');
	FF.js_modal_buy_button.attr('action', 'http://parser.gig-games.de/index.php?action=invoice&platiid='+FF.game_line['item'+FF.consec+'_id']);
	FF.js_modal_ebay_input.val(FF['europrice'+FF.consec]);
	FF.js_modal_woo_input.val((FF['europrice'+FF.consec]*0.95).toFixed(2));
	FF.js_modal_hood_input.val(round_hood_price(FF['europrice'+FF.consec]));

	$('.tch-smalltable .gig').removeClass('gig');
	$('.rp'+FF.consec).addClass('gig');
	FF.consec_out.text(FF.consec);

	$('.fEprice-i').removeClass('show');
});

FF.js_modal_ebay_input.change(function() {
	$('.fEprice-i').removeClass('show');
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
	F.one_removed = 0;
	F.one_changed = false;
	$('.fEprice-i').removeClass('show');
	F.mergedModal.modal('show');
	F.tr = $(this).parent();
	F.js_modal_ebay_link[0].href = F.tr.find('a:last')[0].href;
	F.gameId = FF.gameId = F.tr.attr('data-gameid');
	F.ebayId = F.tr.attr('data-ebayid');
	F.wooId = F.tr.attr('data-wooid');
	F.hoodId = F.tr.attr('data-hoodid');
	F.js_csrf_buy_time_input.val(((new Date().getTime())/1000).toFixed(0));
	F.js_modal_buy_button.attr('action', 'http://parser.gig-games.de/index.php?action=invoice&platiid='+F.tr.attr('data-plati1id'));
	F.rurprice = +F.tr.find('.row5').text();
	// цена склада
	var wh_rurprice = +F.tr.find('.row4').text();
	if(wh_rurprice && wh_rurprice < F.rurprice){
		F.rurprice = wh_rurprice;
		$('.fEprice-i').addClass('show');
	} 
	var exrate = 0;
	if(localStorage["exrate"]) exrate = +localStorage["exrate"];
	F.europrice = formula(F.rurprice, exrate);
	if(F.europrice < 1.5) F.europrice = 1.5;
	window.recom_price = F.europrice;
	F.js_modal_plati_title.text(F.tr.find('.row5').attr('title')).pickText(['free','row']);
	F.js_modal_plati_title.attr('href', F.tr.find('.row8 a').attr('href'));
	F.js_modal_parser_title.text(F.tr.find('.row2').text());
	// сброс строки ebay
	if(F.ebayId) F.js_modal_ebay_title.html('<img src="images/more-loading.gif" alt="loading">');
	else {F.js_modal_ebay_title.html($('#magic_input'));window.magic_gameId=F.gameId;}
	F.js_modal_ebay_price.text('.');
	F.js_modal_ebay_input.val(F.europrice);
	//сброс строки woocommerce
	if(F.wooId) F.js_modal_woo_title.html('<img src="images/more-loading.gif" alt="loading">');
	else F.js_modal_woo_title.html('no id');
	F.js_modal_woo_price.text('.');
	F.js_modal_woo_input.val((F.europrice*0.95).toFixed(2));
	// сброс строки hood
	if(F.hoodId) F.js_modal_hood_title.html('<img src="images/more-loading.gif" alt="loading">');
	else F.js_modal_hood_title.html('no id');
	F.js_modal_hood_price.text('.');
	F.js_modal_hood_input.val(round_hood_price(F.europrice));

	var ebay_prices_tds = F.tr.find('[iid]');
	F.draw_ebay_names(ebay_prices_tds);
	F.js_modal_ebay_prices.html(ebay_prices_tds.clone());
	F.js_modal_plati_price.html('<img src="images/more-loading.gif" alt="loading">');

	$.post('ajax.php?action=ajax-ebay-api-price-changer',
		{ action:'get_game_line', ebayId:F.ebayId, gameId:F.gameId },
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
	}, 'json');

	if(F.ebayId){
		$.post('ajax.php?action=ajax-ebay-api-price-changer',
			{ action:'check', ebayId:F.ebayId, gameId:F.gameId },
			function (data) {
				F.js_modal_ebay_title.html('<div class="clip">'+data.ebay_title+'</div>');
				F.js_modal_ebay_price.text(data.price);
				// if (data.answer === 'good') F.'????'.attr('disabled', false);
		}, 'json');
	}

	if(F.wooId){
		$.post(ajax_woo_url(),
			{ action:'check', wooId:F.wooId, gameId:F.gameId },
			function (data) {
				F.js_modal_woo_title.text(data.woo_title);
				F.js_modal_woo_price.text(data.price);
				// if (data.answer === 'good') WooObj.woo_change_price.attr('disabled', false);
		}, 'json');
	}

	if(F.hoodId){
		$.post('/ajax.php?action=ajax-hood',
			{hood_get_price:'true',
			 hood_id:F.hoodId},function (data) {
				F.js_modal_hood_title.text(data.title);
				F.js_modal_hood_price.text(parseFloat(data.price).toFixed(2));
			 },'json');
	}

});


FF.js_modal_black_white.on('click','.ok,.rem', function(e) {
	$.post('/ajax-controller.php',
		{function:'black_white_list',
		game_id:FF.gameId,
		ebay_id:this.id, 
		category:this.lang, 
		title:$(this).parent().text()},
	  function(data) {});
});

$('#modal_ebay_repars').on('click', function() {
	$.post('/ajax-controller.php',
		{function:'ebay_reparse_one',game_id:FF.gameId},
	  function(data) {
	  	FF.reload_ebay_names(data.tds_str);
	  },'json');
});

$('#modal_ebay_price_up').on('click', function() {
	var competitor_price = $('#js_modal_ebay_prices td').eq(1).text(); // первая цена ибея
	var recom_price = window.recom_price // рекомендованная
	var dif = competitor_price - window.recom_price; // разница
	if(!competitor_price || !dif) return;
 	var set_price = 0;
	if (dif >= 0.1) set_price = competitor_price - 0.1;
	else if (dif > 0 && dif < 0.1) set_price = competitor_price - 0.01;
	if(set_price > recom_price * 1.2) set_price = recom_price * 1.2;
	if(!set_price) return;
	FF.js_modal_ebay_input.val(set_price.toFixed(2));
	FF.js_modal_woo_input.val((set_price*0.95).toFixed(2));
	FF.js_modal_hood_input.val(round_hood_price(set_price));
});

// Изменение инпута WooCommerce
FF.js_modal_ebay_input.on('change', function() {
	FF.js_modal_woo_input.val((parseFloat(FF.js_modal_ebay_input.val().replace(',','.'))*0.95).toFixed(2));
	FF.js_modal_hood_input.val(round_hood_price(FF.js_modal_ebay_input.val().replace(',','.')));
});

// Изменение цен
$('#fChange').on('submit', {f:FF}, function(e) {
	
	e.preventDefault();

	var F = e.data.f
	var fEprice = F.js_modal_ebay_input.val().replace(',','.');
	var fWprice = F.js_modal_woo_input.val().replace(',','.');
	var fHprice = F.js_modal_hood_input.val().replace(',','.');

	if(F.ebayId && FF.rurprice){
		$.post('ajax.php?action=ajax-ebay-api-price-changer',
			{ action: 'change', ebayId: F.ebayId, price: fEprice },
			function (data) {
				if (data.answer == 'good') {
					F.tr.find('.tc-ebay').parent().addClass('pchanged');
				}
		}, 'json');
	}

	if(F.wooId && FF.rurprice){
		$.post(ajax_woo_url(),
			{ action: 'change', wooId: F.wooId, price: fWprice },
			function (data) {
				if (data.answer == 'good') {
					F.tr.find('.tc-woo').parent().addClass('pchanged');
				}
		}, 'json');
	}

	if(F.hoodId && FF.rurprice){
		$.post('ajax.php?action=ajax-hood',
			{ hood_change_price: 'true', hoodId: F.hoodId, newPrice: fHprice },
			function (data) {
				if (data.status == 'success') {
					F.tr.find('.tc-hood').parent().addClass('pchanged');
				}
		}, 'json');
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
					if(F.one_removed > 1) F.mergedModal.modal('hide');
					else F.one_removed += 1;
				}
		}, 'json');
	}

	if(F.wooId){
			$.post(ajax_woo_url(),
			{ action:'remove', wooId:F.wooId },
			function (data) {
				if (data.answer == 'good') {
					if(F.one_removed > 1) F.mergedModal.modal('hide');
					else F.one_removed  += 1;
				}
		}, 'json');
	}

	if(F.hoodId){
		$.post('ajax.php?action=ajax-hood',
			{ hood_remove: 'true', hoodId: F.hoodId },
			function (data) {
				if (data.status == 'success') {
					if(F.one_removed > 1) F.mergedModal.modal('hide');
					else F.one_removed  += 1;
				}
		}, 'json');
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
	M.hoodId = tr.attr('data-hoodid');
	M.rurprice = +tr.find('.row5').text();
	var exrate = 0;
	if(localStorage["exrate"]) exrate = +localStorage["exrate"]; 
	M.europrice = formula(M.rurprice, exrate).toFixed(2);
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
		$.post(ajax_woo_url(),
			{ action: 'change', wooId: M.wooId, price: (M.europrice*0.95).toFixed(2) },
			function (data) {
				if (data.answer == 'good') {
					M.tr.find('.tc-woo').addClass('color-green');
				}else{
					M.tr.find('.tc-woo').addClass('color-red');
				}
		}, 'json');
	}

	if(M.hoodId && M.rurprice){
		$.post('ajax.php?action=ajax-hood',
			{ hood_change_price: 'true', hoodId: M.hoodId, newPrice: round_hood_price(M.europrice) },
			function (data) {
				if (data.status == 'success') {
					M.tr.find('.tc-hood').addClass('color-green');
				}else{
					M.tr.find('.tc-hood').addClass('color-red');
				}
		}, 'json');
	}

});

function diyIcon(M, answer, platt) {
	if (answer === 'good' || answer === 'success') {
		M.tr.find('.tc-'+platt).addClass('color-green');
	}else{
		M.tr.find('.tc-'+platt).addClass('color-red');
	}
}

function mRemoveOneRow() {
	
	if($(this).hasClass('color-gray')) return false;
	$(this).addClass('color-gray');
	var M = {};
	M.tr = tr = $(this).parent().parent();
	M.gameId = tr.attr('data-gameid');
	M.ebayId = tr.attr('data-ebayid');
	M.wooId = tr.attr('data-wooid');
	M.hoodId = tr.attr('data-hoodid');

	if(M.ebayId){
		$.post('ajax.php?action=ajax-ebay-api-price-changer',
			{ action:'remove', ebayId:M.ebayId },
			function (data) {
				diyIcon(M, data.answer, 'ebay');
		}, 'json');
	}

	if(M.wooId){
			$.post(ajax_woo_url(),
			{ action:'remove', wooId:M.wooId },
			function (data) {
				diyIcon(M, data.answer, 'woo');
		}, 'json');
	}

	if(M.hoodId){
		$.post('ajax.php?action=ajax-hood',
			{ hood_remove: 'true', hoodId: M.hoodId },
			function (data) {
				diyIcon(M, data.status, 'hood');
		}, 'json');
	}
}

GenObj.js_tch_deligator.on('click', '.mRemove', function(e) {
	e.preventDefault();
	mRemoveOneRow.call(this);
});

$('#mRemoveAll').click(function () {
	$(this).attr('disabled', 'disabled');
	$('.mRemove').each(mRemoveOneRow);
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

//============================================
$('.orders-table').on('click','.tch-orders-buy',function() {
	var ebayid = this.getAttribute('ebayid');
	var orderid = this.getAttribute('gigparser-orderid');
	document.getElementById('tch-order-itemid').value = ebayid;
	document.getElementById('tch-order-orderid').value = orderid;
	$('tr[data-ebayid="'+ebayid+'"]').find('.tch-merged').eq(0).trigger('click');
});


$('#js-inv-sendemail-form').on('submit', function(e) {
	e.preventDefault();
	var send_data = $( this ).serialize();
	$.post('/ajax.php?action=ajax-invoice-sender',
		send_data,
		function(data) {

			if (data.sendemail_ans) $('#js-inv-sendemail').addClass('glyphicon-ok');
			else $('#js-inv-sendemail').addClass('glyphicon-warning-sign');
			
			if (data.sendebay_ans.Ack === 'Success') $('#js-inv-sendebay').addClass('glyphicon-ok');
			else $('#js-inv-sendebay').addClass('glyphicon-warning-sign');
			
		},'JSON');
});


$('#js-inv-sendemail').on('click', function(e) {
	e.preventDefault();
	var $this = $(this);
	$.post('/ajax.php?action=ajax-invoice-sender',
		{sendemail:'1',
		ebay_orderid:$('[name="ebay_orderid"]').val(),
		user_email:$('[name="user_email"]').val(),
		email_subject:$('[name="email_subject"]').val(),
		email_body:$('[name="email_body"]').val()},
		function(data) {
			if (data.sendemail_ans) $this.addClass('glyphicon-ok');
			else $this.addClass('glyphicon-warning-sign');
			
		},'JSON');
});


$('#js-inv-sendebay').on('click', function(e) {
	e.preventDefault();
	var $this = $(this);
	$.post('/ajax.php?action=ajax-invoice-sender',
		{sendebay:'1',
		ebay_user:$('[name="ebay_user"]').val(),
		ebay_item:$('[name="ebay_item"]').val(),
		ebay_subject:$('[name="ebay_subject"]').val(),
		ebay_body:$('[name="ebay_body"]').val()},
		function(data) {
			if (data.sendebay_ans.Ack === 'Success') $this.addClass('glyphicon-ok');
			else $this.addClass('glyphicon-warning-sign');
		},'JSON');
});


// Mark as Shipped
$('.orders-table').on('click', '.mas', function(e) {
	e.preventDefault();
	$this = $(this);
	var tr = $this.parent().parent();
	var orderid = $this.attr('orderid');
	$.post('/ajax.php?action=ajax-mark-orders',
		{mark_as_shipped : 'true',
		ebay_order_id : orderid},
		function(data) {
			if(data.Ack == 'Success') tr.remove();
		},'JSON');
});

$('#update-ebay-games').on('click', function(e) {
	e.preventDefault();
	$this = $(this);
	$this.addClass('load');
	$.post('/ajax.php?action=ajax-ebay-api-price-changer',
		{update_ebay_games : 'true'},
		function(data) {
			if(data.answer == 'good'){
				$this.removeClass('load');
				$this.append('<i class="glyphicon glyphicon-ok"></i>');
			} 
		},'JSON');
});

$('.fill-input').on('click', function() {
	var ebayid = $(this).attr('ebayid');
	$(this).parent().parent().find('.list-id-input').val(ebayid);
});

$('.list-id-save').on('click', function() {
	$this = $(this);
	var ebayid = $this.parent().find('.list-id-input').val();
	var gameid = $this.parent().parent().find('.listitem').data('id');
	$.post('/ajax.php?action=ajax-ebay-api-price-changer',
		{insert_ebayID_to_games : 'true',
		game_id : gameid,
		ebay_id : ebayid},
		function(data) {
			if(data.answer == 'good'){
				$this.parent().parent().find('.propos').text('id ok')
			}
		},'JSON');
});

// $('#fBuyItem').on('submit', {f:FF}, function(e) {

// 	var F = e.data.f
// 	console.dir(F);
// 	if(F.ebayId){
// 		$.post('ajax.php?action=ajax-ebay-api-price-changer',
// 			{ action:'change_quantity3', ebayId:F.ebayId },
// 			function (data) {
// 				if (data.answer == 'good') {

// 				}
// 		}, 'json');
// 	}

// });

$('#ebay-msg-answer-form').on('submit', function function_name(e) {
	e.preventDefault();
	var $this = $(this);
	$this.find('button').attr('disabled',true);
	var data_send = $this.serialize();
	$.post('/ajax.php?action=ajax-invoice-sender',
		data_send,
		function(data) {
			if(data.reload === 'yes') window.location.reload();
			$this.html('<div class="alert alert-info"><b>'+
				data.sendebay_ans.Ack+'</b></div>');
		},'JSON');
});


$('#em-deligator').on('change', '.q-radio', function(e) {
  console.log(this.value);
  console.log(this.name);
  var value = this.value;
  var name = this.name;
  send_obj = {msg_id : name};
  send_obj['mark_as_'+value] = 1;
  $.post('ajax.php?action=ajax-mark-messages',
  	send_obj,
  	function (data) {});
});


$('#hm-deligator').on('change', '.q-radio', function(e) {
  console.log(this.value);
  console.log(this.name);
  var value = this.value;
  var name = this.name;
  send_obj = {msg_id : name};
  send_obj['mark_hood_as_'+value] = 1;
  $.post('ajax.php?action=ajax-mark-messages',
  	send_obj,
  	function (data) {});
});


$('.orders-table').on('click', '.op-markorder', function(e) {
	console.log(this.name);
	console.log(this.value);
	var $this = this;
	var name = this.name;
	var html = this.innerHTML;
	var send_data = {ebay_order_id:this.value};
	if (name === 'mark_as_shipped') {
		if (html === '+') {
			send_data.mark_as_shipped = 'true';
		}else{
			send_data.mark_as_shipped = 'false';
		}
	}
	if (name === 'mark_as_paid') {
		if (html === '+') {
			send_data.mark_as_paid = 'true';
		}else{
			send_data.mark_as_paid = 'false';
		}
	}
	$.post('/ajax.php?action=ajax-mark-orders',
		send_data,
		function(data) {
			if(data.Ack == 'Success'){
				if(html === '-'){
					$this.innerHTML = '+'
				}else if(html === '+'){
					$this.innerHTML = '-'
				}
			}
		},'JSON');
});

$('[data-toggle="tooltip"]').tooltip();



function ins_msg(msg) {
	$( "#message li:first" ).before( "<li>"+msg+"</li>" );
	// if($('#message li').length > 1000) {
	// 	$('#message li:last').remove();
	// }
}

function ebay_recovery(offset) {
	$.post('ajax.php?action=ajax-ebay-recovery',
		{offset:offset},
		function (data) {
			if (offset < data.count) {
			// if (offset < 3000) {
				if (data.added.success) {
					ins_msg(offset+': '+data.added.resp.Ack+
						'<br><a href="http://www.ebay.de/itm/'+
						data.added.resp.ItemID+
						'" target="_blank">'+
						data.added.item.Title+'</a>');
				}else{
					ins_msg(offset+': '+data.added.resp);
				}
				// ebay_recovery(offset + 1);
			}else{
				ins_msg('Done!');
			}
		},'json');
}

$('#js_recovery').on('click', function() {
	console.log('go!');
	ebay_recovery(217);
});


$('#tpl_select').on('change', function() {
	document.all.em_textarea.value = this.value;
});


$('#tpl_save').on('click', function() {
	var tpl_name = document.all.tpl_name.value;
	var tpl_text = document.all.em_textarea.value;
	if (tpl_name && tpl_text) {
		$.post('ajax.php?action=ebay-messages',
			{action:'save_template', tpl_name:tpl_name,
			tpl_text:document.all.em_textarea.value},
			function(data) {
				// body...
			},'json');
	}
});


// ====== вывод количества непрочитанных сообщений =====
if (location.host !== 'parser') {
	$.post('ajax.php?action=ebay-messages&show=not_answerd',
		{action:'new_msg_count'},
		function(data) {
			$('.js-ebay-count').html(data);
		});

	$.post('ajax.php?action=hood-messages',
		{action:'new_msg_count'},
		function(data) {
			$('.js-hood-count').html(data);
		});
}
// ====== /вывод количества непрочитанных сообщений =====



}); //document ready

















var EbayMessages = {
	init: function () {
		this.setUpListeners();
	},

	setUpListeners: function () {
		$('.trusted-user-star').on('click', this.starClick);
		$('.js-bad-user-alert').on('click', this.alertClick);
		$('.js-show-thumbnail').on('click', this.showThumbnail)
	},

	starClick: function() {
		EbayMessages.changeUserStar(this.name, $(this).hasClass('glyphicon-star-empty'));

		$('.trusted-user-star[name="'+this.name+'"').toggleClass('glyphicon-star');
		$('.trusted-user-star[name="'+this.name+'"').toggleClass('glyphicon-star-empty');
	},

	alertClick: function() {
		EbayMessages.changeUserAlert(this.name, !$(this).hasClass('is_problematic'));

		$('.js-bad-user-alert[name="'+this.name+'"').toggleClass('is_problematic');
	},

	changeUserStar: function(user_id, is_trusted) {
		$.post('/ajax-controller.php',
			{function:'set_trusted_user', user_id:user_id, is_trusted:is_trusted},
			function(data) {});
	},

	changeUserAlert: function(user_id, is_problematic) {
		$.post('/ajax-controller.php',
			{function:'set_problematic_user', user_id:user_id, is_problematic:is_problematic},
			function(data) {});
	},

	showThumbnail: function(e) {
		e.preventDefault();
		document.all.big_pic_img.src = this.href;
		$('#pictureModal').modal('show');
	},
}







var AwaitingList = {
	init: function () {
		this.setUpListeners();
	},

	setUpListeners: function () {
		$('.js-remove-row').on('click', this.removeRow);
	},

	removeRow: function() {
		if (!confirm("Бонус отправлен?")) return false;
		$('#id'+this.name).remove();
		AwaitingList.setBonusSent(this.value); // id sql таблицы
	},

	setBonusSent: function(sql_id) {
		$.post('/ajax-controller.php',
			{function:'set_bonus_sent', sql_id:sql_id},
			function(data) {});
	},
}




function aqsGetCookie(name){
	var re = new RegExp(name + "=([^;]+)");
	var value = re.exec(document.cookie);
	return (value != null) ? decodeURIComponent(value[1]) : null;
}

function aqsSetCookie(name, value){
	document.cookie=name + "=" + encodeURIComponent(value) + "; path=/; expires=" + new Date(Date.now() + 2592000000).toGMTString(); // plus 30 days
}

function execAnalytics() {
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  // koeln-webstudio.de
  ga('create', 'UA-54895581-3', 'auto');
  ga('send', 'pageview');
}


var aqsCookieNotice = {
	init: function (opts) {
		this.opts = $.extend( {
			'message'  : 'We use cookies to tailor our services and online advertising.<br> By using our website you agree to the usage of these cookies as described in our <a href="#">data privacy statement</a>.',

		}, opts);
		this.setWrapperElement();
		if (localStorage.getItem('aqsCookieNotice') === 'yes') this.yes_click();
		else this.showMessage();
		this.setUpListeners();
	},

	setUpListeners: function () {
		this.wrapperElement.on('click', '.cn-yes', this.yes_click.bind(this));
		this.wrapperElement.on('click', '.cn-no', this.no_click.bind(this));
		this.wrapperElement.on('click', '.cn-revoke', this.revoke_click.bind(this));
	},

	setWrapperElement: function() {
		$('body').append('<div id="aqsCookieNotice"></div>');
		this.wrapperElement = $('#aqsCookieNotice');
	},

	showMessage: function() {
		var message = '<div class="cn-message row">';
		message += '<div class="cn-text col-sm-8">'+this.opts.message+'</div>';
		message += '<div class="cn-btns col-sm-4"><button class="cn-yes">yes</button>&nbsp;';
		message += '<button class="cn-no">no</button></div>';
		message += '</div>';
		this.wrapperElement.html(message);
	},

	hideMessage: function() {
		var revoke = '<button class="cn-revoke">revoke</button>';
		this.wrapperElement.html(revoke);
	},

	yes_click: function() {
		this.hideMessage();
		localStorage.setItem('aqsCookieNotice', 'yes');
		this.execOnce();
	},

	no_click: function() {
		this.hideMessage();
		localStorage.setItem('aqsCookieNotice', 'no');
	},

	revoke_click: function() {
		this.showMessage();
	},

	execOnce: function() {
		if(this.executed) return;
		this.executed = true;
		// code below
		// execAnalytics();
	}
}

// aqsCookieNotice.init();

var btn = document.getElementById('btn_id');

var jq_btn = $('#btn_id')

var jq_btn2 = $(btn);

var running = true;

function __ajax() {

		var itemIdEbay = $(this).attr('itemIdEbay');
		var titleEbay = $(this).attr('titleEbay');
		var titleEbaySha1 = $(this).attr('titleEbaySha1');
		var priceEbay = $(this).attr('priceEbay');
		var formula = $( "#formula" ).val();
		var $this = this;


		// Returns successful data submission message when the entered information is stored in database.
		var dataString = 'titleEbaySha1='+ titleEbaySha1 + '&titleEbay='+ titleEbay + '&itemIdEbay='+ itemIdEbay + '&priceEbay=' + priceEbay + '&formula='+ formula;
		if(itemIdEbay==''||titleEbay=='')
		{
			alert("Please Fill All Fields");
		}
		else
		{
		// AJAX Code To Submit Form.
		$.ajax({
			type: "POST",
			url: "/ajax/array",
			data: dataString,
			cache: false,
			success: function(result){ 
			  result = $.parseJSON(result);
			 
			    $('#'+itemIdEbay).text(result.auctionIdHood);
			    $('#hprice_'+itemIdEbay).text(result.priceNew);
			    $('#res_'+itemIdEbay).text(result.result);
			//alert(result.auctionIdHood);
				var next_btn_id = $this.attr('next_btn_id');
				if(next_btn_id && running) __ajax.call(document.getElementById(next_btn_id));
			}
		});
		}
		return false;
}


$(document).ready(function(){


	$( "#start" ).click(function(){
		running = true;
		var first_btn = $( "input[value='Change']" )[0];
		__ajax.call(first_btn);
	});

	$( "input[value='Change']" ).click(function(){
		running = true;
		__ajax.call(this);
	});


	$('#stop').click(function() {
		running = false;
	});



});