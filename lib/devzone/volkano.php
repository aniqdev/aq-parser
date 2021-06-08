<?php


$smarty = new Smarty;
// $smarty->register_modifier('currency', function($str='')
// {
// 	return $str;
// });
$smarty->assign('code','ASDF1234');
// $smarty->assign('affiliate_code','foodeffect');
$smarty->assign('value','13,44');

// $smarty->assign("config", "s_config");
// $smarty->registerFunction("config", "s_config");

function s_config($params)
{
    return 'VulcanoVet';
}

$html = $smarty->fetch(ROOT.'/volkano/sale/New/html/page-1.tpl', 0);

$html = str_replace('<body>', '<body><link rel="stylesheet" href="/volkano/sale/New/css/global.css">', $html);


$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

// echo $html;
// return;

$mpdf = new \Mpdf\Mpdf([
                'mode' => 'BLANK',
                'format' => 'A4',
                'default_font_size' => 0,
                'default_font' => '',
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 0,
                'margin_header' => 0,
                'margin_footer' => 0,
                'orientation' => 'P',
       //          'fontDir' => array_merge($fontDirs, [
			    //     ROOT . '/volkano/fonts',
			    // ]),
			    // 'fontdata' => $fontData + [
			    //     'impact' => [
			    //         'R' => 'ofont.ru_Impact.ttf',
			    //         'I' => 'ofont.ru_Impact.ttf',
			    //     ]
			    // ],
			    // 'default_font' => 'impact'
            ]);
$mpdf->WriteHTML($html);
$mpdf->Output();
// include ROOT.'/volkano/Demo/css/global.css';
