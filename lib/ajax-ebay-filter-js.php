<?php

header('Content-Type: application/javascript');
header('Access-Control-Allow-Origin: *');

echo file_get_contents(ROOT.'/js/ebay-filter.js');

sa($_ERRORS);