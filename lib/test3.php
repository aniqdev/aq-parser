<pre><?php
$db = new DB();
$res = $db->get_results("SHOW COLUMNS FROM blacklist");

$a = [
	'^ http://bilder.afterbuy.de/images/NPZZNW/produktbild_hirseflocken_1kg_1200x1200_72dpi_deko.jpg',
	'http://bilder.afterbuy.de/images/NPZZNW/deko_vitalsnack_leinsamenriegel_pferde_1kg_1200x1200_72dpi.jpgs/NPZZNW/produktbild_leinsamen_riegel_800g_1200x1200_72dpi.jpg',
	'http://bilder.afterbuy.de/images/NPZZNW/neu11aniforte_sortiment_symbol_1200x1200_72dpi.jpghttp://bilder.afterbuy.de/images/NPZZNW/neu11aniforte_sortiment_symbol_1200x1200_72dpi.jpg',
	'p://bilder.afterbuy.de/images/NPZZNW/etikett_insekt_stop_pulver_5kg_1200x1200_72dpi.jpg',
	'vhttp://bilder.afterbuy.de/images/NPZZNW/produktbild_durchblutungsbalsam_300g_1200x1200_72dpi.jpg'
];

print_r($a);
foreach ($a as &$path) {
	preg_match('(//(.+)\.jpg)iU', $path, $b);
	$path = 'http://'.$b[1].'.jpg';
	//$v = parse_url($v);
	// var_dump($v);
}

print_r($a);

?>
</pre>