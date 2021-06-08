<?php



use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;
use spekulatius\phpscraper;


$url = 'https://b2b.cdvet.de/veavet-augenpflege-20-ml';
$url = 'https://www.cdvet.de/zeckex-herbal-oel';

$result = parse($url, $type = 'product', $keywords = 'j,j,j,j');

sa($result);

// $res = file_get_contents($url, false, $context);
// sa(htmlspecialchars($res));
// return;

function parse($url, $type, $keywords)
{
    if($type == 'product') {
        $dom = dom_by_url($url);
        if(!$dom || !preg_match('~window\.dataLayer\.push~s', $dom->innertext)) return [];
        $selectfield = $dom->find('.product--configurator .select-field', 0);
        $breadcrumbs_obj = $dom->find('.breadcrumb--list .breadcrumb--entry');
        foreach($breadcrumbs_obj as $item) {
            $breadcrumbs[] = trim($item->plaintext);
        }
        if($selectfield) {
            foreach($selectfield->find('option') as $s) {
                $result[] = parse_product(rtrim($url, '/') . "?&template=ajax" . $s->getAttribute('value'), $keywords,$breadcrumbs);
            }
        } else {
            $result[] = parse_product(rtrim($url, '/'), $keywords,$breadcrumbs);
        }
    }
    return $result ?? [];
}

function parse_product($url, $keywords = '',$breadcrumbs = [])
{
    $dom = dom_by_url($url);
    preg_match('~window\.dataLayer\.push\((\{.*?\})\);~s', $dom->innertext, $m);
    if(!isset($m[1])) {
        return [];
    }
    $r = json_decode($m[1], 1);
    $images = $dom->find('.image-slider', 0);
    if($images) {
        foreach($images->find('.image--element') as $item) {
            $r2[] = $item->getAttribute('data-img-original');
        }
    }
    $result = $r['ecommerce']['detail']['products'][0];
    $result['url'] = rtrim(explode('?', $url)[0], '/') . "?number=" . $r['ecommerce']['detail']['products'][0]['id'];
    $result['keywords'] = $keywords;
    $result['price_unit'] =  ($price_unit = $dom->find('.price--unit', 0)) ? $price_unit->plaintext : '';
    $result['delivery_text'] = $dom->find('.delivery--text', 0)->plaintext;
    $result['images'] = $r2;
    $result['breadcrumbs'] = $breadcrumbs;
    $result['description_html'] = html_entity_decode($dom->find('.product--details .content--description', 0)->innertext);
    return $result;
}


function dom_by_url($url)
{
	$opts = [
	  'http'=>[
	    'method'=>"GET",
	    'header'=>"Cookie: session-13=3c0l2ee807fsui2olj23p7iqq1\r\n"
	  ]
	];
	$context = stream_context_create($opts);
	return file_get_html($url, false, $context);
}