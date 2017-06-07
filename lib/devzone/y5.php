<?php



function round_price($price)
    {

        $array_round = array(
            1 => 0,
            2 => 0,
            3 => 5,
            4 => 5,
            5 => 5,
            6 => 5,
            7 => 5,
            8 => 9,
            9 => 9,
            0 => 0,
        );
        if ($price == '') return FALSE;
        $price = str_replace(",", ".", $price);

        if ($price * 0.01 < 0.05) {
            return $price - 0.05;
        }

        $price_new = $price - ($price * 0.01);

        $res = round($price_new, 2);
        $end_price = explode('.', $res);

        $rest = substr($end_price[1], -1);
        $replace = $array_round[$rest];
        if (strlen($end_price[1]) == 2) {
            $rest = substr_replace($end_price[1], $replace, -1);
            return $end_price[0] . '.' . $rest;
        }
        $price_new = strtr($rest, $array_round);
        $price_new = $end_price[0] . '.' . $end_price[1];
        return $price_new;
    }

function aqs_round_price($price='')
{
	if (!$price) {
		return false;
	}
	$price = round($price, 2);
	$minus = $price*1000 % 50;
	$price = $price - ($minus===0?0.05:$minus/1000);
	return $price;
}

sa([aqs_round_price('5.01'),round_price('5.01')]);
sa([aqs_round_price('5.14'),round_price('5.14')]);
sa([aqs_round_price('5.23'),round_price('5.23')]);
sa([aqs_round_price('5.30'),round_price('5.30')]);
sa([aqs_round_price('5.49'),round_price('5.49')]);
sa([aqs_round_price('5.51'),round_price('5.51')]);
sa([aqs_round_price('5.65'),round_price('5.65')]);
sa([aqs_round_price('5.76'),round_price('5.76')]);
sa([aqs_round_price('5.83'),round_price('5.83')]);
sa([aqs_round_price('5.99'),round_price('5.99')]);

var_dump(5.1*100%5);

// function printArray($array) 
// { 
// 	$result = ""; 
// 	for($i=0;$i<count($array);$i++) 
// 	$result.=$array[$i]." "; 
// 	return $result."<br>"; 
// } 
// echo "Треугольник Паскаля<br><br>";
// $tri = array(1,array(1,1)); 
// echo $tri[0]."<br>"; 
// for($i=1;$i<7;$i++) 
// {
// 	$last = $tri[count($tri)-1];
// 	echo printArray($last); 
// 	$added = array();
// 	array_push($added,1); 
// 	for($j=0;$j<count($last)-1;$j++) 
// 	array_push($added, $last[$j]+$last[$j+1]); 
// 	array_push($added,1); 
// 	array_push($tri,$added); 
// }

// sa(hoodItemSync(['122478213637','112394797943','112334597943']));


	// $ch = curl_init('http://hood.gig-games.de/api/getItemPrice');
	// curl_setopt_array($ch, [
	//     CURLOPT_RETURNTRANSFER => true,
	//     CURLOPT_POST => true,
	//     CURLOPT_POSTFIELDS => http_build_query(['hood_id' => '697305312'])
	// ]);
	// $resp = curl_exec($ch);
	// curl_close($ch);

	// sa($resp);


// $a = 0;

// switch($a) {
// case 0.01:
//         echo 'answer1';
// case $arr['1']:
//         echo 'answer2';
// case 0:
//         echo 'answer3';
// case 'true':
//         echo 'answer4'; continue;
// case NULL:
//         echo 'answer5'; break;
// default:
//         echo 'default';

// }
// echo '<br />';





// $a1 = "2abcde0";
// $a2 = true;

// settype($a1, integer);
// settype($a2, string);
// echo $a1, $a2;





// class Clazz { 
//     public $value; 
// } 
 
// $b = new Clazz; 
// $b->newValue = 1; 
 
// $a = $b; 
// $a->newValue = 2; 
 
// echo $b->newValue; 








// class c{ 
//     private $a = 42; 
//     function &a(){ 
//         return $this->a; 
//     } 
//     function print_a(){ 
//         echo $this->a; 
//     } 
// } 
// $c = new c; 
// $d = &$c->a(); 
// echo $d; 
// $d = 2; 
// $c->print_a();






// class People { 
//  public function greeting() { 
//    echo "A nice smile for you. "; 
//  } 
// } 
// trait MyParents { 
//   public function greeting() { 
//   parent::greeting(); 
//   $this->greeting2(); 
//   echo "A big hug for you. "; 
//  } 
// } 
// class MyMom extends People { 
//  use MyParents; 
//  public function greeting2() { 
//     echo "A big kiss for you. "; 
//  } 
// } 
// class MyDad extends People { 
//  use MyParents; 
//  public function greeting2() { 
//     echo "A strong handshake for you. "; 
//  } 
// } 
 
// $person = new MyMom(); 
// $person->greeting(); 











return;

	$res = getSingleItem('121966139435',['as_array'=>true,'IncludeSelector'=>'Description']);

	$full_desc = $res['Item']['Description'];

	$dom = str_get_html($full_desc);

	$images = [];

	if($img1 = @$dom->find('[src$="/1.jpg"]',0)->src) $images[] = $img1;
	if($img2 = @$dom->find('[src$="/2.jpg"]',0)->src) $images[] = $img2;
	if($img3 = @$dom->find('[src$="/3.jpg"]',0)->src) $images[] = $img3;

	sa($images);

?>