<?php

echo json_encode(arrayDB("SELECT item_id as id,title,price FROM ebay_prices WHERE status = 'active'"));

// sa(arrayDB("SELECT item_id as id,title,price FROM ebay_prices WHERE status = 'active'"));