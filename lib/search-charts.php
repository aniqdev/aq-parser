

<form action="index.php?action=search-charts" method="GET" align="center" class="search-charts">
    <input type="hidden" name="action" value="search-charts">
    <input type="text" name="s" placeholder="please type here" value="<?php if (isset($_GET['s'])) echo $_GET['s'];?>">
    <input type="submit" value="Search">
</form>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload= 
{'modules':[{'name':'visualization','version':'1.1','packages':
['corechart']}]}"></script>
<?php

echo "<hr>";
//print_r($_GET);
if (isset($_GET['s'])) {
    $search = $_GET['s'];
    $search = substr($search, 0, 64);
    $search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search);
    $good = trim(preg_replace("/\s(\S{1,2})\s/", " ", ereg_replace(" +", "  "," $search ")));
    echo 'По запросу: <b>"',$good = ereg_replace(" +", " ", $good);
    $query = "SELECT appid,price,title FROM slist WHERE title LIKE '%{$good}%' AND mark=(SELECT mark FROM slist ORDER BY id DESC LIMIT 1) LIMIT 100";
    $result = arrayDB($query);
    echo "\"</b> Найдено: <b>",count($result),'</b> штук<br><br>';

if (isset($result))foreach ($result as $k => $v) {


?>

<div class="res-container">
    <div class="res-item clearfix" data-appid="<?php echo $v['appid'];?>">
        <img src="http://cdn.akamai.steamstatic.com/steam/apps/<?php echo $v['appid'];?>/capsule_sm_120.jpg" alt="<?php echo $v['title']?>" class="res-item-left">
        <div class="res-title res-item-left">[<?php echo $v['appid'];?>] </div>
        <div class="res-title res-item-left"><?php echo $v['title'];?></div>
        <div class="res-price res-item-right"><?php echo $v['price'];?> €</div>
    </div>
</div>





<?php 
} //foreach
    // echo "<pre>";
    // print_r($result);
    // echo "</pre>";
} // if (isset($_GET['s']))?>

    <!--Div that will hold the pie chart-->
    <div id="chart_div"></div>