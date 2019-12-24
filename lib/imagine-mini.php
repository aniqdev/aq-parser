<?php

use Imagine\Gd\Font;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Color; 
use Imagine\Image\Point;
use Imagine\Image\ImageInterface;

$imagine = new Imagine();
	$ramka = $imagine->open('images/mini.jpg');
	$inner = $imagine->open('images/test1.jpg');
	$inner->resize(new Box(177, 178));
	$point = new Point(20, 22);
	$ramka->paste($inner, $point);
	$ramka->save('images/minitest1.jpg', ['jpeg_quality' => 100]);

function addTextToImage(){

	$imagine = new Imagine();

	$image = $imagine->open('images/mini.jpg');

	$text = "text testov\n testinase";
	$font = new Font('images/prsans.ttf', 24, new Color('#555'));
	$point = new Point(25, 210);

	$image->draw()->text($text, $font, $point);


	$text2 = '€24.99';
	$font2 = new Font('images/prsans.ttf', 24, new Color('#eaeaea'));
	$point2 = new Point(57, 335);

	$image->draw()->text($text2, $font2, $point2);

	$opts = array('jpeg_quality' => 100);
	$image->save('images/mini3.jpg', $opts);
}




?>
<br><br>
<img src="images/mini.jpg">
<img src="images/minitest1.jpg">
<?php





?><br>