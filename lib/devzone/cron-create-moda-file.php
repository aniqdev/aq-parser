<?php ini_get('safe_mode') or set_time_limit(1300);

/*
Формат файла - каждая новая строка это json объект с moda данными
*/

$start_time = time();

$items = arrayDB("SELECT * from moda_list where flag = 'dataparsed1' and updated_at > (now() - interval 70 MINUTE)");

$keys_arr = [
    'itemId',
    'title',
    'Description',
    'categoryId',
    'PictureURL',
    'QuantitySold',
    'HitCount',
    'currentPrice',
    'Seller.FeedbackScore', // seller FeedbackScore
    'ItemSpecifics',
    'VariationsPics',
    'post_id',
];

echo "<pre>";
// $final_arr = [];
$handle = fopen(ROOT . '/moda-files/moda-arr.txt',"w");
foreach ($items as &$moda) {
    $moda_meta = get_moda_meta($moda['id']);
    if($moda_meta) $moda += $moda_meta;
    // sa($moda);
    $temp_arr = [];
    foreach ($keys_arr as $key) {
        $temp_arr[$key] = isset($moda[$key]) ? $moda[$key] : '';
    }
    // $final_arr[] = $temp_arr;
    $bytes = fwrite($handle, json_encode($temp_arr).PHP_EOL);
    // var_export(PHP_EOL.$bytes.': ');
    // print_r($temp_arr);
}
fclose($handle);
// $str_to_file = var_export($final_arr, true);
// $bytes_count = file_put_contents(ROOT . '/Files/moda-arr.eval.txt', 'return ' . $str_to_file . ';');


// var_dump($bytes_count);
echo "</pre>";
// $hours = floor($seconds / 3600);
// $mins = floor($seconds / 60 % 60);
// $secs = floor($seconds % 60);
// $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
sa('Time: ' . gmdate("H:i:s", time() - $start_time));
// sa('Seconds: ' . (time() - $start_time));
sa('Items: ' . count($items));



