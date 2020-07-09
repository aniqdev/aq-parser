<style>
.moda-cat-lists ul{
	padding-left: 50px;
}
.moda-cat-lists ul ul{
	 border-left: 1px dashed #888;
}   
</style>
<div class="container moda-cat-lists">
	<h2 class="text-center">Fashion categories</h2><hr>
	<div class="row">
		<div class="col-sm-6">
<?php

// CategoryID,CategoryLevel,CategoryName,CategoryName_DE,CategoryParentID

$res3 = arrayDB("SELECT * from moda_cats where CategoryParentID= '260010'"); // CategoryLevel = 3
echo "<ul>";
foreach ($res3 as $val3) {
	echo "<li>".$val3['CategoryName'];
	$res4 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val3['CategoryID']}'"); // CategoryLevel = 4
	if ($res4) {
		echo "<ul>";
		foreach ($res4 as $val4) {
			echo "<li>".$val4['CategoryName'];
			$res5 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val4['CategoryID']}'"); // CategoryLevel = 5
			if ($res5) {
				echo "<ul>";
				foreach ($res5 as $val5) {
					echo "<li>".$val5['CategoryName'];
					$res6 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val5['CategoryID']}'"); // CategoryLevel = 6
					if ($res6) {
						echo "<ul>";
						foreach ($res6 as $val6) {
							echo "<li>".$val6['CategoryName']."</li>";
						}
						echo "</ul>";
					}
					echo "</li>";
				}
				echo "</ul>";
			}
			echo "</li>";
		}
		echo "</ul>";
	}
	echo "</li>";
}
echo "</ul>";
?>
		</div>
		<div class="col-sm-6">
<?php
$res3 = arrayDB("SELECT * from moda_cats where CategoryParentID= '260010'"); // CategoryLevel = 3
echo "<ul>";
foreach ($res3 as $val3) {
	echo "<li>".$val3['CategoryName_DE'];
	$res4 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val3['CategoryID']}'"); // CategoryLevel = 4
	if ($res4) {
		echo "<ul>";
		foreach ($res4 as $val4) {
			echo "<li>".$val4['CategoryName_DE'];
			$res5 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val4['CategoryID']}'"); // CategoryLevel = 5
			if ($res5) {
				echo "<ul>";
				foreach ($res5 as $val5) {
					echo "<li>".$val5['CategoryName_DE'];
					$res6 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val5['CategoryID']}'"); // CategoryLevel = 6
					if ($res6) {
						echo "<ul>";
						foreach ($res6 as $val6) {
							echo "<li>".$val6['CategoryName_DE']."</li>";
						}
						echo "</ul>";
					}
					echo "</li>";
				}
				echo "</ul>";
			}
			echo "</li>";
		}
		echo "</ul>";
	}
	echo "</li>";
}
echo "</ul>";
?>
		</div>
	</div>
</div>
<?php
return;
$final_res = get_moda_women_cats();

sa($final_res);
foreach ($final_res as $key => $val) {
	arrayDB("UPDATE moda_cats SET type = 'women' WHERE id = '$val[id]'");
}

function get_moda_women_cats()
{
	$final_res = [];

	$res3 = arrayDB("SELECT * from moda_cats where CategoryParentID= '260010'"); // CategoryLevel = 3
	foreach ($res3 as $val3) {
		$final_res[] = $val3;
		$res4 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val3['CategoryID']}'"); // CategoryLevel = 4
		if ($res4) {
			foreach ($res4 as $val4) {
				$final_res[] = $val4;
				$res5 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val4['CategoryID']}'"); // CategoryLevel = 5
				if ($res5) {
					foreach ($res5 as $val5) {
						$final_res[] = $val5;
						$res6 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val5['CategoryID']}'"); // CategoryLevel = 6
						if ($res6) {
							foreach ($res6 as $val6) {
								$final_res[] = $val6;
							}
						}
					}
				}
			}
		}
	}

	return($final_res);
}


