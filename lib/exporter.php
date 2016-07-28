<?php
header('Content-Type: text/html; charset=utf-8');
ini_get('safe_mode') or set_time_limit(0);
include_once('lib/simple_html_dom.php');
include_once('lib/PHPExcel.php');
// В следующей строчке Steam_Language=german, можно указывать другие языки вместо german
// $options = array('http' => array('method' => "GET", 'header' => "Accept-language: de\r\n" . "Cookie: Steam_Language=german; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
// $context = stream_context_create($options);


?>
<style>
	table{
		border-collapse: collapse;
	}
	td,th{
		border: 1px solid #777;
		padding: 0 5px;
	}
</style>

<form action="index.php?action=exporter" method="POST">
	<br><button name="send">Сгенерировать CSV</button>
</form><br>

<?php
	$idArr = array( 
121792558010,
121691110294,
111799351342,
111795367215,
121785653514,
111771509140,
121785571352,
111795391291,
111795419194,
111795424986,
111795426823,
121785626770,
111795430274,
121785640966,
121785642938,
111795448813,
121785648921,
111795452565
				);
$resArr = array();
if (isset($_POST['send'])) {

	$resArr[0] = array( 'post_content'   => 'post_content',
						'post_title'     => 'post_title',
						'post_excerpt'   => 'post_excerpt',
						'ping_status'    => 'ping_status',
						'post_name'      => 'post_name',
						'regular_price'  => 'regular_price',
						'stock_status'   => 'stock_status',
						'virtual'        => 'virtual',
						'product_gallery'=> 'product_gallery',
						'featured_image' => 'featured_image',
						'category'       => 'category'
		);

	foreach ($idArr as $k => $id) {
		$k++;
		$resArr[$k] = array();

		$stroka = file_get_contents('http://www.ebay.de/itm/'.$id);
		$html = str_get_html($stroka);

		$title = $html->find("title", 0)->plaintext;
		$pos = strpos($title, '(PC)');
		$title = substr($title, 0, $pos);

		$post_name = $html->find("#icImg ", 0)->alt;

		$price = $html->find("#prcIsum ", 0)->plaintext;
		$price = str_replace('EUR', '', $price);
		$price = (float)str_replace(',', '.', $price);
		$price = round($price*0.96, 1);

		$mainIMG = $html->find("#icImg ", 0)->src;

		$stroka = file_get_contents('http://vi.vipr.ebaydesc.com/ws/eBayISAPI.dll?ViewItemDescV4&item='.$id);
		$html = str_get_html($stroka);

		$img1 = $html->find('img', 1)->src;
		$img2 = $html->find('img', 2)->src;
		$img3 = $html->find('img', 3)->src;

		$start = strpos($stroka, 'class="midinfo">') + 16;
		$end   = strpos($stroka, '<a href="');
		$info = substr($stroka, $start, $end - $start);

		$start = strpos($stroka, 'ibung</h3>') + 10;
		$end   = strpos($stroka, '<h3>Download</h3>', $start) - 7;
		$descr = substr($stroka, $start , $end - $start);
		//echo $descr,'<hr>';

		$resArr[$k] = array( 'post_content'  => trim($descr),
							'post_title'     => trim($title),
							'post_excerpt'   => trim($info),
							'ping_status'    => 'closed',
							'post_name'      => trim($post_name),
							'regular_price'  => trim($price),
							'stock_status'   => 'instock',
							'virtual'        => 'yes',
							'product_gallery'=> $img1.'|'.$img2.'|'.$img3,
							'featured_image' => trim($mainIMG),
							'category'       => 'Games|Steam'
			);

	} // foreach ($idArr
		// echo "<pre>";
		// print_r($resArr);
		// echo "</pre>";

// Создание Exel файла
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getActiveSheet()->fromArray($resArr, null, 'A1');
			// Название страницы в Excel, по умолчанию Spiele
			$objPHPExcel->getActiveSheet()->setTitle('Spiele');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

			// Папка для сохранения файлов
			$dir = 'Files';
			// Префикс для файлов
			$file_prefix = 'export';

			$objWriter->save($dir . '/' . $file_prefix . '.xlsx');

		echo '<br><a href="Files/export.xlsx">Скачать Exel</a><br>';	

// Создание CSV файла
			$objPHPcsv = new PHPExcel();
			$objPHPcsv->getActiveSheet()->fromArray($resArr, null, 'A1');
			// Название страницы в Excel, по умолчанию Spiele
			$objPHPcsv->getActiveSheet()->setTitle('Spiele');

			$objWriterC = PHPExcel_IOFactory::createWriter($objPHPcsv, 'CSV');

			// Папка для сохранения файлов
			$dir = 'Files';
			// Префикс для файлов
			$file_prefix = 'export';

			$objWriterC->save($dir . '/' . $file_prefix . '.csv');

		echo '<br><a href="Files/export.csv">Скачать CSV</a><br>';		    
} // if (isset($_POST['send']))
?>