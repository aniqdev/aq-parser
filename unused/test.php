<?php
ini_get('safe_mode') or set_time_limit(1800); // Указываем скрипту, чтобы не обрывал связь.
header('Content-Type: text/html; charset=utf-8');
?>
<meta charset="utf-8">
<pre><?php
define('DOCROOT', 'E:\xamp\htdocs\parser\www\test.php');
define('ROOT', __DIR__);
require_once 'lib/PHPExcel.php';
require_once 'lib/array_DB.php';
require_once 'lib/simple_html_dom.php';


function addFooter($desc)
{

  if(stripos($desc, '<small class="triple">') !== false){

    $desc = substr($desc, 0, $len);
    $add_to_desc = '<a href="http://gig-games.de" class="gig-copy" target="_blank"><small class="triple">Copyright © g.i.g-group 2014</small></a></p>
  <div class="gig-created">
     <a href="http://koeln-webstudio.de/" title="koeln-webstudio"> created by
      <img src="http://hot-body.net/gig-less/images/visit_card.png" alt="koeln-webstudio">
     </a>
    </div>
    <!--dreamrobot-->
      <div class="gig-dreamrobot">
    <div>
     <p>
      Die Vertragsabwicklung erfolgt über
        <a target="_blank" href="https://www.dreamrobot.de/pdf/DreamRobot_Datenschutzerklaerung.pdf" style="color:#A4A3A5;">DreamRobot</a> im
      Auftrag des Verkäufers. <br> Dazu werden die personenbezogenen Daten des Käufers an DreamRobot (Betreiber:
      DreamRobot GmbH, Eckendorfer Str. 2-4, 33609 Bielefeld, Deutschland)
      weitergeleitet. Hier finden Sie die
        <a target="_blank" href="https://www.dreamrobot.de/pdf/DreamRobot_Datenschutzerklaerung.pdf" style="color:#A4A3A5;"> DreamRobot Datenschutzerklärung</a>.
     </p>
    </div>
   </div>
    <!--/dreamrobot-->
   </div>';
   $desc = $desc . $add_to_desc;
  }

  return $desc;
}

function addSupport($desc)
{
  if (stripos($desc, 'gig-support') === false) {
    $desc = str_replace('<p><b>Skype</b> g.i.g-group</p>', "<p><b>Skype</b> g.i.g-group</p>\r\n<p><a href='http://gig-games.de' class='gig-support' target='_blank'>Online Support</a></p>", $desc);
  }
  return $desc;
}

function getItemDescription($itemId){
    $url = 'http://open.api.ebay.com/shopping';
    $url .= '?callname=GetSingleItem';
    $url .= '&responseencoding=JSON';
    $url .= '&appid=Aniq6478a-a8de-47dd-840b-8abca107e57';
    $url .= '&siteid=77';
    $url .= '&version=515';
    $url .= '&ItemID='.$itemId;
//   $url .= '&IncludeSelector=Details';
    $url .= '&IncludeSelector=Description';
//  $url .= '&IncludeSelector=Details,Description';
//  $url .= '&IncludeSelector=Details,TextDescription';


    // Открываем файл с помощью установленных выше HTTP-заголовков
    $json = file_get_contents($url);
    //var_dump($json);
    return json_decode($json, true)['Item']['Description'];
}

function doChanges($itemid,$desc){

$headers = array
    (
    'X-EBAY-API-COMPATIBILITY-LEVEL: ' . '837',
    'X-EBAY-API-DEV-NAME: ' . 'c1f2f124-1232-4bc4-bf9e-8166329ce649',
    'X-EBAY-API-APP-NAME: ' . 'Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
    'X-EBAY-API-CERT-NAME: ' . 'PRD-ae576df59071-a52d-4e1b-8b78-9156',
    'X-EBAY-API-CALL-NAME: ' . 'ReviseItem',
    'X-EBAY-API-SITEID: ' . '77',
    'X-EBAY-API-RESPONSE-ENCODING: ' . 'JSON',
);

$endpoint = 'https://api.ebay.com/ws/api.dll';//https://api.sandbox.ebay.com/ws/api.dll

$xml = '<?xml version="1.0" encoding="utf-8"?>
<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <RequesterCredentials>
    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
  </RequesterCredentials>
  <Item ComplexType="ItemType">
    <ItemID>'.$itemid.'</ItemID>
    <Title>BlazBlue: Calamity Trigger (PC) Steam Regfree MULTILANG</Title>
    <Quantity>0</Quantity>
    <StartPrice>4.55</StartPrice>
    <Description>'.htmlspecialchars ($desc) .'</Description>
  </Item>
  <MessageID>1</MessageID>
  <WarningLevel>High</WarningLevel>
  <Version>837</Version>
</ReviseItemRequest>​';

$xml = '<?xml version="1.0" encoding="utf-8"?>
<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <RequesterCredentials>
    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
  </RequesterCredentials>
  <Item ComplexType="ItemType">
    <ItemID>'.$itemid.'</ItemID>
    <Description>'.htmlspecialchars ($desc) .'</Description>
  </Item>
  <MessageID>1</MessageID>
  <WarningLevel>High</WarningLevel>
  <Version>837</Version>
</ReviseItemRequest>​';

$ch  = curl_init($endpoint);     
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);                  
curl_setopt($ch, CURLOPT_POST, true);              
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$responseXML = curl_exec($ch);
curl_close($ch);

//var_dump($responseXML);

$responseObj = simplexml_load_string($responseXML);

echo "<hr>";
var_dump((string)$responseObj->Ack);
//print_r($responseObj);


}



// $itemid = '121658238763';
// doChanges($itemid);

$itemArr = readExcel('csv/FileExchange_Response_11.10.16.xlsx');

// $l = count($itemArr);
// var_dump($l);
// for ($i=2; $i <= 2; $i++) { 
//   var_dump($i);
//   doChanges($itemArr[$i]['A']);
//   echo '<a href="http://www.ebay.de/itm/',$itemArr[$i]['A'],'" target="_blank">',$itemArr[$i]['B'],'</a><br>';
// }



$l = count($itemArr);
var_dump($l);
for ($i=6709; $i <= 751; $i++) { 
  var_dump($i);
  //doChanges2($itemArr[$i]['A']);
  $itemid = $itemArr[$i]['A'];
  //if($i=='2') $itemid = '121503896106';
  $desc = getItemDescription($itemid);

  file_put_contents('desc-backup/'.$itemid.'_'.time().'.html', $desc);
  //var_dump($desc);
  $dom = str_get_html($desc);

  $title = $dom->find('.gig-tittle',0)->plaintext;
  $title = trim($title);

  $img3d = '';
  if($img3d = $dom->find('img[src$="3d.png"]',0))$img3d=$img3d->getAttribute('src');
  if($img1  = $dom->find('img[src$="/1.jpg"]',0)) $img1=$img1->getAttribute('src');
  if($img2  = $dom->find('img[src$="/2.jpg"]',0)) $img2=$img2->getAttribute('src');
  if($img3  = $dom->find('img[src$="/3.jpg"]',0)) $img3=$img3->getAttribute('src');

  if (!$img3d) {
    if($img3d = $dom->find('img[src*="funkyimg.com"]',0)) $img3d=$img3d->getAttribute('src');
    if($img1  = $dom->find('img[src*="funkyimg.com"]',1)) $img1=$img1->getAttribute('src');
    if($img2  = $dom->find('img[src*="funkyimg.com"]',2)) $img2=$img2->getAttribute('src');
    if($img3  = $dom->find('img[src*="funkyimg.com"]',3)) $img3=$img3->getAttribute('src');
  }

  $about = $dom->find('.triple',1);
  if($about){
    $about = $about->innertext;
    $about = trim($about);
  }else{
    AqsBot::sendMessage(['text'=>print_r([$itemid,$about],true),]);
  }

  $new_desc = file_get_contents('lib/adds/responsive.html');

  $new_desc = str_replace('{{TITLE}}', $title, $new_desc);
  $new_desc = str_replace('{{IMG3D}}', $img3d, $new_desc);
  $new_desc = str_replace('{{IMG1}}', $img1, $new_desc);
  $new_desc = str_replace('{{IMG2}}', $img2, $new_desc);
  $new_desc = str_replace('{{IMG3}}', $img3, $new_desc);
  $new_desc = str_replace('{{ABOUT}}', $about, $new_desc);
  //var_dump($new_desc);

  var_dump($title);
  var_dump($img3d);
  var_dump($img1);
  var_dump($img2);
  var_dump($img3);
  //var_dump($about);
  echo '<a href="http://www.ebay.de/itm/',$itemArr[$i]['A'],'" target="_blank">',$itemArr[$i]['N'],'</a><hr>';
  doChanges($itemid,$new_desc);
}
//showArray($itemArr);


//doChanges('112090853589',file_get_contents('desc-backup/112090853589_1476200986.html'));
$dirdesc = scandir('desc-backup');
$ddcount = count($dirdesc);
var_dump($ddcount);
//print_r($dirdesc);

$dir_keys = [];
for ($i=2; $i < $ddcount; $i++) {
  $id = explode('_', $dirdesc[$i])[0];
  $dir_keys[$id] = $dirdesc[$i];
}
var_dump(count($dir_keys));
//print_r($dir_keys);

$count = count($itemArr);
var_dump($count);
$search = '<div class="midinfo">
<p>Sprache: <b>FR, EN</b></p>
<p>Region: <b>free</b></p>
<p>Plattform: <b>Steam</b></p>
<a href="" class="gig-button">Buy with Discount</a>
</div>';
for ($i=7909; $i <= 751; $i++) { 

  $itemid = (string)$itemArr[$i]['A'];
  //if($i=='2') $itemid = '121503896106';
  $curr_desc = getItemDescription($itemid);
  $old_desc = file_get_contents('desc-backup/'.$dir_keys[$itemid]);

  file_put_contents('desc-backup2/'.$itemid.'_'.time().'.html', $curr_desc);

  if (strpos($curr_desc, 'gig-about') !== false) {
    //var_dump($dir_keys[$itemid]);

    $dom_old = str_get_html($old_desc);
    $replace = $dom_old->find('.midinfo',0)->innertext;
    $dom_curr = str_get_html($curr_desc);
    $dom_curr->find('.midinfo',0)->innertext = $replace;
    $new_desc = $dom_curr->save();

    doChanges($itemid,$new_desc);
    var_dump('done!');
  }else{
    var_dump('skipped!');
  }
    echo '<a href="http://www.ebay.de/itm/',$itemArr[$i]['A'],'" target="_blank">',$itemArr[$i]['N'],'</a><hr>';
}
// =======================================================

// $old_desc = file_get_contents('desc-backup/'.$dir_keys['112026272410']);
// $curr_desc = file_get_contents('lib/adds/responsive.html');
//echo $curr_desc;
// $curr_desc = getItemDescription('112026272410');
// echo $curr_desc;
// $dom_old = str_get_html($old_desc);
// $replace = $dom_old->find('.midinfo',0)->innertext;
// $dom_curr = str_get_html($curr_desc);
// $dom_curr->find('.midinfo',0)->innertext = $replace;
// $new_desc = $dom_curr->save();
//var_dump($replace);
// $search = '<div class="midinfo">
// <p>Sprache: <b>FR, EN</b></p>
// <p>Region: <b>free</b></p>
// <p>Plattform: <b>Steam</b></p>
// <a href="" class="gig-button">Buy with Discount</a>
// </div>';
//echo str_ireplace($search, $replace, $curr_desc);
//echo $new_desc;
?></pre>