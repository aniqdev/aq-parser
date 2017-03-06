<?php

if (isset($_GET['del'])) {
	$plati_id = _esc($_GET['del']);
	arrayDB("DELETE FROM gig_trustee_items
			WHERE plati_id='$plati_id'");
}

$res = arrayDB("SELECT plati_id,item1_name,item1_price,counter
				from gig_trustee_items
				join ((select * from items group by item1_id) union (select * from steam_items group by item1_id)) it
				on gig_trustee_items.plati_id = it.item1_id
				group by plati_id");

if(defined('DEV_MODE')) sa($res);

view('trustees/trust-list',['res'=>$res]);

?>

<script>
$('#js-del-deligator').on('click', '.js-del', function() {
	if (!confirm("Уверен?")) return false;
});
</script>