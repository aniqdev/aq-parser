<?php
phpinfo();
// $imagine = new Imagine\Gd\Imagine();

$from = 'pictures/Gruppenbild_LunaLupis.tif';
$to = 'pictures/Gruppenbild_LunaLupis.png';

// $imagine->open($from)->save($to);


 // $image = new Imagick($from);
 //    $image->setImageFormat('png');

 //    echo $image;
    // or
    // $image->writeImage('something.png');

return;

  $ord_obj = new EbayOrders;

  $ord_arr = $ord_obj->getOrders(['NumberOfDays'=>3,'SortingOrder'=>'Ascending','PageNumber'=>'1']);

sa($ord_arr);

return;

$url = 'http://store.steampowered.com/app/375850/Island_Defense/';
  $game_id = preg_replace('/.*\/(\d+)\/.*/', '\1', $url);
  sa($game_id);
  $app_sub = preg_replace('/.*\/(\w+)\/\d+\/.*/', '\1', $url);
  sa($app_sub);

return;

  $Woo = new WooCommerceApi();
  $woo_item = $Woo->checkProductById('138');
  // $woo_item = $Woo->updateProductPrice('7428', 2.1);

  sa($woo_item);


return;
$res = arrayDB("SELECT item_id FROM ebay_games");

foreach ($res as $k => &$v) $v = $v['item_id'];

$res = array_flip($res);

sa($res);


$ids_arr = include(__DIR__.'/../../settings/ids_arr.php');

sa($ids_arr);


return;

$automaticArr = arrayDB("SELECT 
    ebay_orders.ExecutionMethod,
    ebay_orders.goods,
    ebay_orders.BuyerUserID,
    ebay_orders.BuyerEmail,
    ebay_order_items.title,
    ebay_order_items.shipped_time,
    ebay_automatic_log.*
FROM ebay_automatic_log
JOIN ebay_orders
ON ebay_automatic_log.order_id=ebay_orders.id
JOIN ebay_order_items
ON ebay_automatic_log.order_item_id=ebay_order_items.id
ORDER BY ebay_automatic_log.id DESC limit 0,30");

sa($automaticArr);


return;

$order = new GigOrder();
return;
$title = 'Lethal League PC spiel Steam Download Digital Link DE/EU/USA Key Code Gift';

sa(cut_steam_from_title($title));
sa(clean_ebay_title2($title));

return;
    $continue = false;
  $suitables = get_suitables2('112127391106');
  $suitable = ['item1_id' => '0']; // костыль
  if (count($suitables) > 1) {

    $continue = true;
  }elseif (count($suitables) < 1) {

    $continue = true;
  }else $suitable = $suitables[0];

  var_dump($continue);
  sa($suitable);

return;

$two_week_res = arrayDB("SELECT tt.*, ebay_games.title_clean, ebay_games.picture_hash FROM (select title,price,ebay_id,shipped_time,count(*) as count from ebay_order_items group by ebay_id) tt
JOIN ebay_games
ON tt.ebay_id = ebay_games.item_id
WHERE picture_hash <> '' AND shipped_time > NOW() - INTERVAL 14 DAY
order by count desc
limit 10");

sa($two_week_res);

return;
    $email_body = file_get_contents('http://info-rim.ru/mail2017/res2.html');

    $mail = get_a3_smtp_object();
    $mail->addAddress('nameaniq@gmail.com');
    $mail->addBCC('thenav@mail.ru');
    $mail->addBCC('store@gig-games.de');
    $mail->Subject = 'res2 test 7';
    $mail->Body    = $email_body;
    $mail->AltBody = strip_tags($email_body);

    $is_email_sent = $mail->send();
    var_dump($is_email_sent);


return;
sa(date('m/d/Y', time()-60*60*24));

return;
sa((new DateTime(str_replace(["{ts '","'}"], '', "{ts '2017-06-29 12:46:32'}")))->format('d-m-Y H:i:s'));


return;
?><style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }
    </style>
  </head>
  <body>
    <input id="pac-input" class="controls" type="text"
        placeholder="Enter a location">
    <div id="type-selector" class="controls">
      <input type="radio" name="type" id="changetype-all" checked="checked">
      <label for="changetype-all">All</label>

      <input type="radio" name="type" id="changetype-establishment">
      <label for="changetype-establishment">Establishments</label>

      <input type="radio" name="type" id="changetype-address">
      <label for="changetype-address">Addresses</label>

      <input type="radio" name="type" id="changetype-geocode">
      <label for="changetype-geocode">Geocodes</label>
    </div>
    <div id="map"></div>

    <script>
      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -33.8688, lng: 151.2195},
          zoom: 13
        });
        var input = /** @type {!HTMLInputElement} */(
            document.getElementById('pac-input'));

        var types = document.getElementById('type-selector');
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29)
        });

        autocomplete.addListener('place_changed', function() {
          infowindow.close();
          marker.setVisible(false);
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
          }

          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
          }
          marker.setIcon(/** @type {google.maps.Icon} */({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
          }));
          marker.setPosition(place.geometry.location);
          marker.setVisible(true);

          var address = '';
          if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
          }

          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
          infowindow.open(map, marker);
        });

        // Sets a listener on a radio button to change the filter type on Places
        // Autocomplete.
        function setupClickListener(id, types) {
          var radioButton = document.getElementById(id);
          radioButton.addEventListener('click', function() {
            autocomplete.setTypes(types);
          });
        }

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-address', ['address']);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);
      }
    </script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD563r49NBGTlbq5l8xtTYXytMbkCWyjC0&libraries=places&callback=initMap"
        async defer></script>