<div class="ppp-right">
	<div class="ppp-block">
		<h4>order by</h4>
		<ul class="ppp-parses">
			<li><a href="/index.php?action=slistshow&limit=2000">Последнее сканирование(2000)</a></li>
			<li><a href="/index.php?action=slistshow&option=day_changes&limit=1000">Последние изменения</a></li>
			<li><a href="/index.php?action=slistshow&option=day_rating&limit=1000">по рейтингу от большего</a></li>
			<li><a href="/index.php?action=slistshow&option=day_views&limit=1000">по отзывам от большего(1000)</a></li>
		</ul>
	</div>
	<div class="ppp-block">
		<ul class="ppp-parses">
			<?php 
				include ROOT.'/lib/slist_functions.php';
				$marks = arrayDB('SELECT DISTINCT mark FROM slist ORDER BY mark DESC');
					// echo "<br><pre>\n";
					// print_r($scans);
					// echo '</pre>';
				echo $mark1 = $marks[0]['mark'];
				echo "<br>";
				echo $mark2 = $marks[1]['mark'];
			foreach ($marks as $value) {
				$mark = $value['mark'];
				$date = date("d-m-Y H:i", $mark*60);
				echo '<li><a href="/index.php?action=slistshow&mark=',$mark,'" class="ppp-link">Парс ',$mark,' От ',$date,' </a></li>';
			}
				
			?>
		</ul>
	</div>
</div>
<div class="ppp-block ppp-left" id="games">

  <input class="search" placeholder="Search" />
  <button class="sort" data-sort="name">
    Sort by name
  </button>

<?php	
	if (isset($_GET['mark'])) {
		$mark = mysql_escape_string(trim(strip_tags($_GET['mark'])));
	}else{
		$mark = $marks[0]['mark'];
	}

	isset($_GET['order'])  ?  $order  =  $_GET['order']       :  $order = 'id';
	isset($_GET['by'])     ?  $by     =  $_GET['by']          :  $by    = 'ASC';
	isset($_GET['limit'])  ?  $limit  =  (int)$_GET['limit']  :  $limit = 1000;

	

	$query2 = " SELECT t1.title as title,t1.price-t2.price as differ,t1.link as link,t1.price as P1,t2.price as P2
				FROM 
					(SELECT * FROM slist WHERE mark=$mark1) as t1 
				INNER JOIN 
					(SELECT * FROM slist WHERE mark=$mark2) as t2 
				ON t1.appid=t2.appid AND t1.appsub=t2.appsub
				WHERE t1.price<>t2.price
				ORDER BY differ ASC
				LIMIT $limit";

	$query3 = " SELECT t1.title,t1.rating-t2.rating as differ,t1.link,t1.rating as R1,t2.rating as R2 
				FROM 
					(SELECT * FROM slist WHERE mark=$mark1) as t1 
				INNER JOIN 
					(SELECT * FROM slist WHERE mark=$mark2) as t2 
				ON t1.appid=t2.appid AND t1.appsub=t2.appsub
				WHERE t1.rating<>t2.rating 
				ORDER BY differ DESC
				LIMIT $limit";

	$query4 = " SELECT t1.title,t1.views-t2.views as differ,t1.link,t1.views as V1,t2.views as V2 
				FROM 
					(SELECT * FROM slist WHERE mark=$mark1) as t1 
				INNER JOIN 
					(SELECT * FROM slist WHERE mark=$mark2) as t2 
				ON t1.appid=t2.appid AND t1.appsub=t2.appsub
				WHERE t1.views<>t2.views 
				ORDER BY differ DESC
				LIMIT $limit";


if (isset($_GET['option']))
	
	switch ($_GET['option']) {
		case 'day_changes':
			$res = arrayDB($query2);
			break;
		
		case 'day_rating':
			$res = arrayDB($query3);
			break;
		
		case 'day_views':
			$res = arrayDB($query4);
			break;
		
		default:
			$res = lastChanges($mark,$order,$by,$limit);
			break;
	}

else	    $res = lastChanges($mark,$order,$by,$limit);

$n = 1;

	// echo "<pre>";
	// print_r($res);
	// echo "</pre>";

	if (is_array($res)) {
		echo "<table class='ppp-table'><tr><th>№</th>";
		foreach ($res[0] as $key => $value) {
			echo "<th>",$key,"</th>";
		}
		echo "</tr><tbody class='list'>";
		foreach ($res as $kr => $row) {
			echo '<tr><td>',$kr+1,'</td>';
			foreach ($row as $kc => $col) {
				echo '<td>',$col,'</td>';
			}
			echo '</tr></tbody>';
		}
	}else{
		echo $res;
	}
		// echo "<br><pre>\n";
		// print_r($res);
		// echo '</pre>';
?>
<script>
	var options = {
	  valueNames: [ 'name', 'born' ]
	};

	var userList = new List('games', options);
</script>
</div>