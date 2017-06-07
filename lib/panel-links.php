<?php

$settings = get_settins_by_category('panels2017');

$block = $_GET['block'];

$ebay_id = $settings[$block.'_id'];

$link = 'http://www.ebay.de/itm/'.$ebay_id;

header("Location: $link");

?>